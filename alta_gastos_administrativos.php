<?php
if (isset($_REQUEST['enviar'])) { 
    $descripcion = $_REQUEST['descripcion'];
    $categoria_gasto_id = $_REQUEST['categoria_gasto_id'];
    $monto = $_REQUEST['monto'];
    $turno_id = $_REQUEST['turno_id'];
    $empleado = $_REQUEST['empleado'];
    $fecha = $_REQUEST['fecha'];

    require_once("conexion.php"); 
    mysqli_query($conex,"SET NAMES 'UTF8'");

    // Guardar la información del turno en la base de datos
    $resultado_alta = mysqli_query($conex, "INSERT INTO gastos_administrativos (turno_id, categoria_gasto_id, descripcion, monto, empleado, fecha) 
    VALUE ('$turno_id','$categoria_gasto_id','$descripcion','$monto','$empleado','$fecha')") 
    or die ("problema en la consulta: " . mysqli_error($conex));

    if ($resultado_alta) {
        $gastos_administrivos_grabado = mysqli_insert_id($conex);

        $query_nombre_categoria = "SELECT nombre_categoria FROM categoria_gasto WHERE id_categoria_gasto = '$categoria_gasto_id'";
        $row_nombre_categoria = mysqli_fetch_assoc(mysqli_query($conex, $query_nombre_categoria));    
        $nombre_categoria = $row_nombre_categoria['nombre_categoria'];

        $query_nombre_turno = "SELECT turno FROM turno_caja WHERE id_turno = '$turno_id'";
        $row_nombre_turno = mysqli_fetch_assoc(mysqli_query($conex, $query_nombre_turno));    
        $nombre_turno = $row_nombre_turno['turno'];

        // Mostrar los detalles del turno registrado
        echo '<h2>Descripcion del Gasto:</h2> ' . "<b>" . $descripcion . "</b>" . 
        '<br> <h2>Categoria:</h2> ' . "<b>" . $nombre_categoria . "</b>" . 
        '<br> <h2>Monto:</h2> ' . "<b>$ " . $monto . "</b>" . 
        '<br> <h2>Turno:</h2> ' . "<b>" . $nombre_turno . "</b>" . 
        '<br> <h2>Empleado:</h2> ' . "<b> " . $empleado . "</b>" . 
        '<br> <h2>Fecha:</h2> ' . "<b>" . $fecha . "</b>" . 
        '<br> <h2>Se guardó correctamente.</h2>';
        ?>
        <form action="gastos_administrativos_panel.php">
            <input type="submit" value="Continuar">
        </form>
        <?php
    } else {
        echo 'Error en el proceso';
    }
    mysqli_close($conex);
}
?>