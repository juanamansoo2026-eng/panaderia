<?php
require_once __DIR__ . '/auth.php';
?>
<?php
require_once("caja_funciones.php");

$ok = false;
$mensaje = '';

if (isset($_REQUEST['enviar'])) {
    $id_turno = (int)$_REQUEST['id_turno'];
    $monto_final = isset($_REQUEST['monto_final']) ? (float)$_REQUEST['monto_final'] : 0;
    $observaciones = isset($_REQUEST['observaciones']) ? trim($_REQUEST['observaciones']) : '';

    require_once("conexion.php");

    // DATOS DE LA CAJA
    $caja = cajaPorId($conex, $id_turno);
    $empleado = empleadoSesion($conex);

    if (!$caja) {
        $mensaje = 'La caja no existe.';
    } elseif ($caja['estado'] != 'ABIERTO') {
        $mensaje = 'La caja N° ' . $id_turno . ' ya estaba cerrada. Un cierre no se puede repetir ni modificar.';
    } elseif ($monto_final < 0) {
        $mensaje = 'El efectivo contado no puede ser negativo.';
    } else {
        // VOLVER A CALCULAR LOS TOTALES EN EL SERVIDOR (no se confía en lo que manda el formulario)
        $t = totalesCaja($conex, $caja);
        $esperado = $t['esperado'];
        $diferencia = $monto_final - $esperado;

        // TOTAL QUE SE GUARDA: lo vendido en la caja
        $total_recaudado = $t['ventas_total'];
        $total_egresos = $t['egresos_total'];

        // QUIÉN CERRÓ LA CAJA (no hay columna para eso: queda al principio de las observaciones)
        $quien = $empleado ? $empleado['nombre'] . ' ' . $empleado['apellido'] : $_SESSION['username'];
        $observaciones = mb_substr('Cerró: ' . $quien . ($observaciones != '' ? ' | ' . $observaciones : ''), 0, 255);

        // CERRAR LA CAJA (solo si sigue abierta: evita cerrarla dos veces)
        $cierre = mysqli_prepare($conex,
            "UPDATE turno_caja
             SET fecha_cierre = NOW(), total_recaudado = ?, monto_final = ?, observaciones = ?, estado = 'CERRADO'
             WHERE id_turno = ? AND estado = 'ABIERTO'")
            or die("problema en la consulta: " . mysqli_error($conex));
        mysqli_stmt_bind_param($cierre, 'ddsi', $total_recaudado, $monto_final, $observaciones, $id_turno);
        $ok = mysqli_stmt_execute($cierre) && mysqli_stmt_affected_rows($cierre) == 1;
        if (!$ok) {
            $mensaje = 'La caja no se pudo cerrar (puede que otra persona la haya cerrado recién).';
        } else {
            // REGISTROS FINANCIEROS DEL CIERRE (como en Casa Segura):
            // un INGRESO con lo vendido y un EGRESO con las compras y gastos pagados con la caja
            $monto_ingreso = (float)$total_recaudado;
            $desripcion = 'Cierre de caja #' . $id_turno . ' - ventas';
            $resultado_registro = mysqli_query($conex, "INSERT INTO registros_financieros (tipo, monto, desripcion, fecha)
            VALUE ('INGRESO', '$monto_ingreso', '$desripcion', NOW())")
            or die ("problema en la consulta: " . mysqli_error($conex));

            $monto_egreso = (float)$total_egresos;
            $desripcion = 'Cierre de caja #' . $id_turno . ' - compras y gastos';
            $resultado_registro = mysqli_query($conex, "INSERT INTO registros_financieros (tipo, monto, desripcion, fecha)
            VALUE ('EGRESO', '$monto_egreso', '$desripcion', NOW())")
            or die ("problema en la consulta: " . mysqli_error($conex));
        }
    }
    mysqli_close($conex);
} else {
    $mensaje = 'No se recibieron datos.';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cierre de Caja - Panadería M</title>
    <link rel="stylesheet" href="css/panaderia-moderna.css">
</head>
<body>
    <?php include("menu.php"); ?>
    <main class="container form-container resultado-container">
        <?php if ($ok) { ?>
            <h2>Caja N° <?php echo $id_turno; ?> cerrada</h2>
            <div class="mensaje <?php echo (round($diferencia, 2) < 0) ? 'advertencia' : 'exito'; ?>" role="status">
                Vendido: <b><?php echo pesos($total_recaudado); ?></b> · Compras y gastos: <b><?php echo pesos($total_egresos); ?></b><br>
                (quedaron guardados en los Registros Financieros)<br>
                Esperado: <b><?php echo pesos($esperado); ?></b> ·
                Contado: <b><?php echo pesos($monto_final); ?></b><br>
                <?php if (round($diferencia, 2) > 0) { ?>
                    Sobran <b><?php echo pesos($diferencia); ?></b>
                <?php } elseif (round($diferencia, 2) < 0) { ?>
                    Faltan <b><?php echo pesos(abs($diferencia)); ?></b>
                <?php } else { ?>
                    La caja cerró justa.
                <?php } ?>
            </div>
            <div class="resultado-acciones">
                <a class="button" href="turno_caja_panel.php">Ir a Caja</a>
                <form action="turno_caja_cierre.php" method="post" style="display:inline;">
                    <input type="hidden" name="id_turno" value="<?php echo $id_turno; ?>">
                    <button type="submit" class="button button-outline">Ver resumen</button>
                </form>
            </div>
        <?php } else { ?>
            <h2>No se pudo cerrar la caja</h2>
            <div class="mensaje error" role="alert"><?php echo htmlspecialchars($mensaje); ?></div>
            <div class="resultado-acciones">
                <a class="button button-outline" href="turno_caja_panel.php">Volver</a>
            </div>
        <?php } ?>
    </main>
    <?php include("footer.php"); ?>
</body>
</html>
