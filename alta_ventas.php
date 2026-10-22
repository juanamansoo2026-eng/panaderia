<?php

if (isset($_REQUEST['enviar'])) {
   
    $cliente_id = $_REQUEST['cliente_id'];
    $empleado_id = $_REQUEST['empleado_id'];
    $subtotal = $_REQUEST['subtotal'];
    $descuento = $_REQUEST['descuento'];
    $total = $_REQUEST['total'];
    $forma_pago = $_REQUEST['forma_pago'];
    $fecha = $_REQUEST['fecha'];

    require_once("conexion.php"); 
    mysqli_query($conex,"SET NAMES 'UTF8'");

    // INSERTAR REGISTRO DE LA VENTA EN LA TABLA "VENTAS"
    $insertVentas = mysqli_query($conex,"INSERT INTO ventas (cliente_id, empleado_id, subtotal, descuento, total, forma_pago, fecha) 
    VALUES ('$cliente_id', '$empleado_id', '$subtotal', '$descuento', '$total', '$forma_pago', '$fecha')") or die ("problema en la consulta".mysqli_error($conex));

    // Si la consulta se realiza con éxito la variable $insertVentas se carga con valor TRUE
    if ($insertVentas) {
        $ventas_id = mysqli_insert_id($conex); // Obtener ID de la venta insertada
        
        // INSERTAR REGISTROS EN LA TABLA DE DETALLES
        foreach ($_REQUEST['productos'] as $key => $producto_id) { // Ciclo para recorrer el array PRODUCTOS, obtener el índice del array y almacenar en $key
                $cantidad = $_REQUEST['cantidades'][$key];
                $precio_unitario = $_REQUEST['precio'][$key];
                // insert un registro por cada vuelta del ciclo
                mysqli_query($conex, "INSERT INTO detalle_venta (venta_id, producto_id, cantidad, precio_unitario)
                VALUES ('$ventas_id','$producto_id','$cantidad', '$precio_unitario')") or die ("problema en la consulta".mysqli_error($conex));
        }
        
        ?>
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Venta guardada - Panadería M</title>
            <link rel="stylesheet" href="css/panaderia-moderna.css">
        </head>
        <body>
            <?php include("menu.php"); ?>
            <main class="container form-container resultado-container">
                <h2>Venta registrada</h2>
                <div class="mensaje exito" role="status">
                    <svg class="ui-icon" aria-hidden="true" viewBox="0 0 24 24"><path d="m5 12 4 4L19 6"/></svg>
                    La venta se guardó correctamente.
                </div>
                <div class="resultado-acciones">
                    <a class="button" href="ventas_panel.php">Continuar</a>
                </div>
            </main>
            <?php include("footer.php"); ?>
        </body>
        </html>
        <?php
        } else {
            ?>
            <!DOCTYPE html>
            <html lang="es">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Error al guardar la venta - Panadería M</title>
                <link rel="stylesheet" href="css/panaderia-moderna.css">
            </head>
            <body>
                <?php include("menu.php"); ?>
                <main class="container form-container resultado-container">
                    <h2>No se pudo registrar la venta</h2>
                    <div class="mensaje error" role="alert">Ocurrió un error durante el proceso. Revisá los datos e intentá nuevamente.</div>
                    <div class="resultado-acciones">
                        <a class="button button-outline" href="ventas_alta.php">Volver al formulario</a>
                    </div>
                </main>
                <?php include("footer.php"); ?>
            </body>
            </html>
            <?php
    }
}

?>
