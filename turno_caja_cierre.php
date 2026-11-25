<?php
require_once __DIR__ . '/auth.php';
?>
<?php
    // MUESTRA EL ARQUEO DE UNA CAJA: si está abierta permite cerrarla, si está cerrada muestra el resumen
    $id_turno = isset($_REQUEST['id_turno']) ? (int)$_REQUEST['id_turno'] : 0;

    require_once("conexion.php");
    require_once("caja_funciones.php");

    // DATOS DE LA CAJA
    $caja = cajaPorId($conex, $id_turno);

    if ($caja) {
        $t = totalesCaja($conex, $caja);
        $esperado = $t['esperado'];
        $abierta = ($caja['estado'] == 'ABIERTO');
        $diferencia = $caja['monto_final'] - $esperado;
        $sin_arqueo = !$abierta && (!$caja['fecha_cierre'] || substr($caja['fecha_cierre'], 0, 4) == '0000');

        // VENTAS DE LA CAJA SEPARADAS POR FORMA DE PAGO
        $ventas = mysqli_prepare($conex,
            "SELECT forma_pago, COUNT(*) AS cantidad, IFNULL(SUM(total), 0) AS total
             FROM ventas WHERE turno_id = ? GROUP BY forma_pago ORDER BY total DESC");
        mysqli_stmt_bind_param($ventas, 'i', $id_turno);
        mysqli_stmt_execute($ventas);
        $ventas_por_forma = mysqli_fetch_all(mysqli_stmt_get_result($ventas), MYSQLI_ASSOC);

        // DETALLE DE LOS EGRESOS: COMPRAS A PROVEEDORES Y GASTOS
        $lista_compras = mysqli_prepare($conex,
            "SELECT id_pedido_compra, pedido_compra.fecha, materia_prima.nombre_mp, proveedor.nombre, proveedor.apellido, cantidad, pedido_compra.total
             FROM pedido_compra
             INNER JOIN materia_prima ON materia_prima.id_materia_prima = pedido_compra.materia_prima_id
             INNER JOIN proveedor ON proveedor.id_proveedor = pedido_compra.proveedor_id
             WHERE pedido_compra.turno_id = ?
             ORDER BY id_pedido_compra");
        mysqli_stmt_bind_param($lista_compras, 'i', $id_turno);
        mysqli_stmt_execute($lista_compras);
        $lista_compras = mysqli_fetch_all(mysqli_stmt_get_result($lista_compras), MYSQLI_ASSOC);

        $lista_gastos = mysqli_prepare($conex,
            "SELECT id_gasto, descripcion, monto, fecha FROM gastos_administrativos WHERE turno_id = ? ORDER BY id_gasto");
        mysqli_stmt_bind_param($lista_gastos, 'i', $id_turno);
        mysqli_stmt_execute($lista_gastos);
        $lista_gastos = mysqli_fetch_all(mysqli_stmt_get_result($lista_gastos), MYSQLI_ASSOC);
    }

    mysqli_close($conex);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cierre de Caja - Panadería M</title>
    <link rel="stylesheet" href="css/panaderia-moderna.css">
    <style>
        .arqueo td:last-child { text-align: right; }
        .arqueo .fila-total td { font-weight: 700; font-size: 1.05rem; }
        .sobrante, .monto-ingreso { color: var(--bakery-success, #2E7D32); font-weight: 700; }
        .faltante, .monto-egreso { color: var(--bakery-danger); font-weight: 700; }
    </style>
</head>
<body>
    <?php include("menu.php"); ?>
    <div class="container form-container" style="width: 650px">

    <?php if (!$caja) { ?>

        <h2 style="text-align: center">Cierre de Caja - Panadería M</h2>
        <div class="mensaje error" role="alert">No se encontró la caja indicada.</div>
        <div class="resultado-acciones"><a class="button button-outline" href="turno_caja_panel.php">Volver</a></div>

    <?php } else { ?>

        <h2 style="text-align: center"><?php echo $abierta ? 'Cerrar Caja' : 'Detalle de Caja'; ?> N° <?php echo $caja['id_turno']; ?></h2>

        <p style="text-align:center;">
            Abrió: <b><?php echo htmlspecialchars($caja['nombre'] . ' ' . $caja['apellido']); ?></b><br>
            Apertura: <?php echo mostrarFecha($caja['fecha_apertura']); ?>
            <?php if (!$abierta && !$sin_arqueo) { ?>
                &nbsp;·&nbsp; Cierre: <?php echo mostrarFecha($caja['fecha_cierre']); ?>
            <?php } ?>
        </p>

        <!-- ARQUEO DE CAJA -->
        <table class="arqueo">
            <tbody>
                <tr><td>Monto inicial</td><td><?php echo pesos($caja['monto_inicial']); ?></td></tr>
                <tr><td>+ Ventas en efectivo</td><td class="monto-ingreso">+<?php echo pesos($t['ventas_efectivo']); ?></td></tr>
                <tr><td>− Compras a proveedores (<?php echo $t['cantidad_compras']; ?>)</td><td class="monto-egreso">−<?php echo pesos($t['compras_total']); ?></td></tr>
                <tr><td>− Gastos (<?php echo $t['cantidad_gastos']; ?>)</td><td class="monto-egreso">−<?php echo pesos($t['gastos_total']); ?></td></tr>
                <tr class="fila-total"><td>Efectivo esperado en caja</td><td id="esperado" data-valor="<?php echo $esperado; ?>"><?php echo pesos($esperado); ?></td></tr>
            </tbody>
        </table>

            <!-- VENTAS DE LA CAJA POR FORMA DE PAGO -->
            <h3 style="margin-top: 20px;">Ventas de la caja (<?php echo $t['cantidad_ventas']; ?>)</h3>
            <table class="arqueo">
                <tbody>
                    <?php if (empty($ventas_por_forma)) { ?>
                        <tr><td colspan="2" class="sin-resultados">No hay ventas registradas en esta caja.</td></tr>
                    <?php } ?>
                    <?php foreach ($ventas_por_forma as $fila) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars(ucfirst($fila['forma_pago'])); ?> (<?php echo $fila['cantidad']; ?>)</td>
                            <td class="monto-ingreso">+<?php echo pesos($fila['total']); ?></td>
                        </tr>
                    <?php } ?>
                    <tr class="fila-total"><td>Total vendido</td><td><?php echo pesos($t['ventas_total']); ?></td></tr>
                </tbody>
            </table>

            <!-- EGRESOS DE LA CAJA -->
            <h3 style="margin-top: 20px;">Egresos de la caja (<?php echo $t['cantidad_compras'] + $t['cantidad_gastos']; ?>)</h3>
            <table class="arqueo">
                <tbody>
                    <?php if (empty($lista_compras) && empty($lista_gastos)) { ?>
                        <tr><td colspan="2" class="sin-resultados">No hay compras ni gastos registrados en esta caja.</td></tr>
                    <?php } ?>
                    <?php foreach ($lista_compras as $c) { ?>
                        <tr>
                            <td>Compra: <?php echo htmlspecialchars($c['cantidad'] . ' x ' . $c['nombre_mp'] . ' - ' . $c['nombre'] . ' ' . $c['apellido']); ?> (<?php echo mostrarFecha($c['fecha'], false); ?>)</td>
                            <td class="monto-egreso">−<?php echo pesos($c['total']); ?></td>
                        </tr>
                    <?php } ?>
                    <?php foreach ($lista_gastos as $g) { ?>
                        <tr>
                            <td>Gasto: <?php echo htmlspecialchars($g['descripcion']); ?> (<?php echo mostrarFecha($g['fecha'], false); ?>)</td>
                            <td class="monto-egreso">−<?php echo pesos($g['monto']); ?></td>
                        </tr>
                    <?php } ?>
                    <tr class="fila-total"><td>Total compras y gastos</td><td><?php echo pesos($t['egresos_total']); ?></td></tr>
                </tbody>
            </table>

            <?php if (round($esperado, 2) < 0) { ?>
                <div class="mensaje error" role="alert" style="margin-top: 15px;">
                    El efectivo esperado da negativo: hay egresos por más plata de la que tenía la caja. Revisalos antes de cerrar.
                </div>
            <?php } ?>

        <?php if ($abierta) { ?>

            <!-- FORMULARIO DE CIERRE -->
            <form action="cierre_turno_caja.php" method="post" autocomplete="off" style="margin-top: 20px;">
                <input type="hidden" name="id_turno" value="<?php echo $caja['id_turno']; ?>">

                <label for="monto_final">Efectivo contado en caja ($):</label>
                <input type="number" step="0.01" min="0" name="monto_final" id="monto_final" required oninput="calcularDiferencia()">

                <p id="diferencia" style="font-size: 1.05rem;"></p>

                <label for="observaciones">Observaciones:</label>
                <input type="text" name="observaciones" id="observaciones" maxlength="200"
                       value="<?php echo htmlspecialchars($caja['observaciones']); ?>" placeholder="Opcional">

                <p style="font-size: 0.9rem;">Cierra: <b><?php echo htmlspecialchars($_SESSION['username']); ?></b>. Una vez cerrada, la caja no se puede modificar.</p>

                <input type="submit" name="enviar" value="Cerrar caja">
                <input type="submit" value="Cancelar" form="volver">
            </form>
            <form action="turno_caja_panel.php" id="volver" method="post"></form>

            <script>
                // MUESTRA LA DIFERENCIA MIENTRAS SE ESCRIBE LO CONTADO
                function calcularDiferencia() {
                    var esperado = parseFloat(document.getElementById('esperado').dataset.valor) || 0;
                    var contado = parseFloat(document.getElementById('monto_final').value);
                    var p = document.getElementById('diferencia');
                    if (isNaN(contado)) { p.textContent = ''; return; }
                    var dif = Math.round((contado - esperado) * 100) / 100;
                    var texto = '$' + Math.abs(dif).toLocaleString('es-AR', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                    if (dif > 0) { p.className = 'sobrante'; p.textContent = 'Sobran ' + texto; }
                    else if (dif < 0) { p.className = 'faltante'; p.textContent = 'Faltan ' + texto; }
                    else { p.className = 'sobrante'; p.textContent = 'La caja cierra justa'; }
                }
            </script>

        <?php } else { ?>

            <!-- RESUMEN DE UNA CAJA YA CERRADA -->
            <?php if ($sin_arqueo) { ?>
                <div class="mensaje advertencia" style="margin-top: 20px;">Esta caja se cerró sin arqueo (es anterior al nuevo cierre de caja).</div>
            <?php } else { ?>
            <table class="arqueo" style="margin-top: 20px;">
                <tbody>
                    <tr><td>Efectivo contado al cerrar</td><td><?php echo pesos($caja['monto_final']); ?></td></tr>
                    <tr class="fila-total">
                        <td>Diferencia</td>
                        <td class="<?php echo (round($diferencia, 2) < 0) ? 'faltante' : 'sobrante'; ?>">
                            <?php echo (round($diferencia, 2) < 0 ? '−' : '') . pesos(abs($diferencia)); ?>
                        </td>
                    </tr>
                </tbody>
            </table>
            <?php } ?>
            <?php if ($caja['observaciones'] != '') { ?>
                <p><b>Observaciones:</b> <?php echo htmlspecialchars($caja['observaciones']); ?></p>
            <?php } ?>
            <div class="resultado-acciones">
                <a class="button button-outline" href="turno_caja_panel.php">Ir a Caja</a>
            </div>

        <?php } ?>

    <?php } ?>

    </div>
    <?php include("footer.php"); ?>
</body>
</html>
