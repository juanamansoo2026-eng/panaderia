<?php
require_once __DIR__ . '/auth.php';
?>
<?php
if (isset($_REQUEST['enviar'])) { 
    $proveedor_id = $_REQUEST['id_proveedor'];
    $materia_prima_id = $_REQUEST['id_materia_prima'];
    $marca_pc = $_REQUEST['marca_pc'];
    $cantidad = $_REQUEST['cantidad'];
    $precio = $_REQUEST['precio'];
    $total = $cantidad * $precio;
    $fecha = $_REQUEST['fecha'];

    require_once("conexion.php"); 
    mysqli_query($conex,"SET NAMES 'UTF8'");

    // LA COMPRA SE PAGA CON LA CAJA ABIERTA (se resta en su arqueo)
    require_once("caja_funciones.php");
    $caja_abierta = cajaAbierta($conex);
    if (!$caja_abierta) {
        mysqli_close($conex);
        paginaAviso('No se guardó el pedido', 'No hay ninguna caja abierta. Abrila para poder registrar compras.', 'turno_caja_alta.php', 'Abrir Caja');
    }
    $disponible = totalesCaja($conex, $caja_abierta)['esperado'];

    // NO SE PUEDE PAGAR CON LA CAJA MÁS EFECTIVO DEL QUE HAY
    if ($total <= 0) {
        mysqli_close($conex);
        paginaAviso('No se guardó el pedido', 'La cantidad tiene que ser mayor a cero.', 'pedido_compra_alta.php', 'Volver al formulario');
    }
    if (round($total, 2) > round($disponible, 2)) {
        mysqli_close($conex);
        paginaAviso('No se guardó el pedido',
            'La compra es de <b>' . pesos($total) . '</b> y en la caja N° ' . $caja_abierta['id_turno'] . ' hay <b>' . pesos($disponible) . '</b> en efectivo.',
            'pedido_compra_alta.php', 'Volver al formulario');
    }
    $turno_id = (int)$caja_abierta['id_turno'];

    // Guardar la información del turno en la base de datos
    $resultado_alta = mysqli_query($conex, "INSERT INTO pedido_compra (proveedor_id, materia_prima_id, marca_pc, cantidad, precio, total, fecha, turno_id) 
    VALUE ('$proveedor_id','$materia_prima_id','$marca_pc','$cantidad','$precio','$total','$fecha', $turno_id)") 
    or die ("problema en la consulta: " . mysqli_error($conex));

    if ($resultado_alta) {
        $pedido_compra_grabado = mysqli_insert_id($conex);

        // Obtener los detalles del empleado que se acaba de registrar
        $query_proveedor = "SELECT nombre, apellido FROM proveedor WHERE id_proveedor = '$proveedor_id'";
        $resultado_proveedor = mysqli_query($conex, $query_proveedor);
        $row_proveedor = mysqli_fetch_assoc($resultado_proveedor);
        $nombre_proveedor = $row_proveedor['nombre'];
        $apellido_proveedor = $row_proveedor['apellido'];

        $query_nombre_mp = "SELECT nombre_mp FROM materia_prima WHERE id_materia_prima = '$materia_prima_id'";
        $row_nombre_mp = mysqli_fetch_assoc(mysqli_query($conex, $query_nombre_mp));    
        $nombre_mp = $row_nombre_mp['nombre_mp'];

        // MOSTRAR LOS DETALLES DEL PEDIDO REGISTRADO
        ?>
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Pedido de compra guardado - Panadería M</title>
            <link rel="stylesheet" href="css/panaderia-moderna.css">
        </head>
        <body>
            <?php include("menu.php"); ?>
            <main class="container form-container resultado-container">
                <h2>Pedido de compra registrado</h2>
                <div class="mensaje exito" role="status">
                    <svg class="ui-icon" aria-hidden="true" viewBox="0 0 24 24"><path d="m5 12 4 4L19 6"/></svg>
                    El pedido se guardó correctamente.
                </div>
                <table>
                    <tbody>
                        <tr><td>Proveedor</td><td><b><?php echo htmlspecialchars($nombre_proveedor . ' ' . $apellido_proveedor); ?></b></td></tr>
                        <tr><td>Producto</td><td><?php echo htmlspecialchars($nombre_mp); ?></td></tr>
                        <tr><td>Marca</td><td><?php echo htmlspecialchars($marca_pc); ?></td></tr>
                        <tr><td>Cantidad</td><td><?php echo htmlspecialchars($cantidad); ?></td></tr>
                        <tr><td>Precio unitario</td><td>$<?php echo number_format((float)$precio, 2, ',', '.'); ?></td></tr>
                        <tr><td>Total</td><td><b>$<?php echo number_format((float)$total, 2, ',', '.'); ?></b></td></tr>
                        <tr><td>Fecha</td><td><?php echo date('d/m/Y', strtotime($fecha)); ?></td></tr>
                        <tr><td>Pago</td><td><?php echo 'Con el efectivo de la caja N° ' . $turno_id; ?></td></tr>
                    </tbody>
                </table>
                <div class="resultado-acciones">
                    <a class="button" href="pedido_compra_panel.php">Continuar</a>
                    <a class="button button-outline" href="pedido_compra_alta.php">Cargar otro pedido</a>
                    <a class="button button-outline" href="turno_caja_panel.php">Ir a Caja</a>
                </div>
            </main>
            <?php include("footer.php"); ?>
        </body>
        </html>
        <?php
    } else {
        echo 'Error en el proceso';
    }
    mysqli_close($conex);
}
?>