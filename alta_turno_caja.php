<?php
require_once __DIR__ . '/auth.php';
?>
<?php
require_once("caja_funciones.php");

$ok = false;
$mensaje = '';

if (isset($_REQUEST['enviar'])) {
    $monto_inicial = isset($_REQUEST['monto_inicial']) ? (float)$_REQUEST['monto_inicial'] : 0;
    $observaciones = isset($_REQUEST['observaciones']) ? trim($_REQUEST['observaciones']) : '';

    require_once("conexion.php");

    // EMPLEADO QUE INICIÓ SESIÓN
    $empleado = empleadoSesion($conex);

    // BLOQUEO PARA QUE DOS APERTURAS AL MISMO TIEMPO NO GENEREN CAJAS DUPLICADAS
    mysqli_query($conex, "SELECT GET_LOCK('panaderia_abrir_caja', 10)");

    // VOLVER A CONTROLAR QUE NO HAYA OTRA CAJA ABIERTA (evita duplicados)
    $caja_abierta = cajaAbierta($conex);

    if (!$empleado) {
        $mensaje = 'No se encontró el empleado del usuario que inició sesión.';
    } elseif ($caja_abierta) {
        $mensaje = 'Ya hay una caja abierta (N° ' . $caja_abierta['id_turno'] . '). Cerrala antes de abrir otra.';
    } elseif ($monto_inicial < 0) {
        $mensaje = 'El monto inicial no puede ser negativo.';
    } else {
        // Guardar la apertura de la caja en la base de datos
        $empleado_id = (int)$empleado['id_empleado'];
        $turno = 'CAJA'; // los turnos ya no se usan; la columna no puede quedar vacía
        $observaciones = mysqli_real_escape_string($conex, $observaciones);

        $resultado_alta = mysqli_query($conex, "INSERT INTO turno_caja (empleado_id, turno, fecha_apertura, monto_inicial, total_recaudado, monto_final, estado, observaciones)
        VALUE ('$empleado_id','$turno', NOW(), '$monto_inicial', 0, 0, 'ABIERTO', '$observaciones')")
        or die ("problema en la consulta: " . mysqli_error($conex));

        if ($resultado_alta) {
            $ok = true;
            $turno_caja_grabado = mysqli_insert_id($conex); // número de la caja que se acaba de abrir
        } else {
            $mensaje = 'Ocurrió un error al abrir la caja.';
        }
    }
    mysqli_query($conex, "SELECT RELEASE_LOCK('panaderia_abrir_caja')");
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
    <title>Abrir Caja - Panadería M</title>
    <link rel="stylesheet" href="css/panaderia-moderna.css">
</head>
<body>
    <?php include("menu.php"); ?>
    <main class="container form-container resultado-container">
        <?php if ($ok) { ?>
            <h2>Caja abierta</h2>
            <div class="mensaje exito" role="status">
                <svg class="ui-icon" aria-hidden="true" viewBox="0 0 24 24"><path d="m5 12 4 4L19 6"/></svg>
                <?php echo htmlspecialchars($empleado['nombre'] . ' ' . $empleado['apellido']); ?> abrió la caja N° <?php echo $turno_caja_grabado; ?> con <?php echo pesos($monto_inicial); ?>.
            </div>
            <div class="resultado-acciones">
                <a class="button" href="ventas_alta.php">Registrar una venta</a>
                <a class="button button-outline" href="turno_caja_panel.php">Ir a Caja</a>
            </div>
        <?php } else { ?>
            <h2>No se pudo abrir la caja</h2>
            <div class="mensaje error" role="alert"><?php echo htmlspecialchars($mensaje); ?></div>
            <div class="resultado-acciones">
                <a class="button button-outline" href="turno_caja_panel.php">Volver</a>
            </div>
        <?php } ?>
    </main>
    <?php include("footer.php"); ?>
</body>
</html>
