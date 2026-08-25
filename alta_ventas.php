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
        
        echo '<h2>La Venta se guardó correctamente.</h2>'; 
        ?>
        <form action="ventas_panel.php">
            <input type="submit" value="Continuar">
        </form>
        <?php
        } else {
            echo 'error en el proceso';
    }
}

?>