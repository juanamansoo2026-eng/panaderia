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

    // Guardar la información del turno en la base de datos
    $resultado_alta = mysqli_query($conex, "INSERT INTO pedido_compra (proveedor_id, materia_prima_id, marca_pc, cantidad, precio, total, fecha) 
    VALUE ('$proveedor_id','$materia_prima_id','$marca_pc','$cantidad','$precio','$total','$fecha')") 
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

        // Mostrar los detalles del turno registrado
        echo '<h2>Pedido de Compra al Proveedor:</h2> ' . "<b>" . $nombre_proveedor . " " . $apellido_proveedor . "</b>" . 
        '<br> <h2>Producto:</h2> ' . "<b>" . $nombre_mp . "</b>" . 
        '<br> <h2>Marca:</h2> ' . "<b>" . $marca_pc . "</b>" . 
        '<br> <h2>Cantidad:</h2> ' . "<b>" . $cantidad . "</b>" . 
        '<br> <h2>Precio Unitario:</h2> ' . "<b>$ " . $precio . "</b>" . 
        '<br> <h2>Total:</h2> ' . "<b>$ " . $total . "</b>" . 
        '<br> <h2>Fecha:</h2> ' . "<b>" . $fecha . "</b>" . 
        '<br> <h2>Se guardó correctamente.</h2>';
        ?>
        <form action="pedido_compra_panel.php">
            <input type="submit" value="Continuar">
        </form>
        <?php
    } else {
        echo 'Error en el proceso';
    }
    mysqli_close($conex);
}
?>