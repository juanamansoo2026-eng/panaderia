<?php
if (isset($_REQUEST['enviar'])) { 
    $empleado_id = $_REQUEST['id_empleado'];
    $turno = $_REQUEST['turno']; 
    $fecha = $_REQUEST['fecha'];
    $total_recaudado = $_REQUEST['total_recaudado'];
    
    require_once("conexion.php"); 
    mysqli_query($conex,"SET NAMES 'UTF8'");

    // Guardar la información del turno en la base de datos
    $resultado_alta = mysqli_query($conex, "INSERT INTO turno_caja (empleado_id, turno, fecha, total_recaudado) 
    VALUE ('$empleado_id','$turno','$fecha','$total_recaudado')") 
    or die ("problema en la consulta: " . mysqli_error($conex));

    if ($resultado_alta) {
        $turno_caja_grabado = mysqli_insert_id($conex);

        // Obtener los detalles del empleado que se acaba de registrar
        $query_empleado = "SELECT nombre, apellido FROM empleados WHERE id_empleado = '$empleado_id'";
        $resultado_empleado = mysqli_query($conex, $query_empleado);
        $row_empleado = mysqli_fetch_assoc($resultado_empleado);
        $nombre_empleado = $row_empleado['nombre'];
        $apellido_empleado = $row_empleado['apellido'];

        // Mostrar los detalles del turno registrado
        echo '<h2>El Empleado:</h2> ' . "<b>" . $nombre_empleado . " " . $apellido_empleado . "</b>" . 
        '<br> <h2>Turno:</h2> ' . "<b>" . $turno . "</b>" . 
        '<br> <h2>El Día:</h2> ' . "<b>" . $fecha . "</b>" . 
        '<br> <h2>Total Recaudado:</h2> ' . "<b>$ " . $total_recaudado . "</b>" . 
        '<br> <h2>Se guardó correctamente.</h2>';
        ?>
        <form action="turno_caja_panel.php">
            <input type="submit" value="Continuar">
        </form>
        <?php
    } else {
        echo 'Error en el proceso';
    }
    mysqli_close($conex);
}
?>