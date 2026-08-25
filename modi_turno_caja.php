<?php

if (isset($_REQUEST['enviar'])){

    $id_turno = $_REQUEST['id_turno'];
    $empleado_id = $_REQUEST['id_empleado'];
    $turno = $_REQUEST['turno']; 
    $fecha = $_REQUEST['fecha'];
    $total_recaudado = $_REQUEST['total_recaudado'];

    require_once("conexion.php");
    
    $resultado_modi = mysqli_query($conex,"UPDATE turno_caja SET empleado_id = '$empleado_id', turno = '$turno', fecha = '$fecha', total_recaudado = '$total_recaudado'
    WHERE id_turno = $id_turno") 
    or die ("problema en la consulta".mysqli_error($conex));
    if ($resultado_modi){
        header('location: turno_caja_panel.php');
    } else {
        echo 'se produjo un error al modificar';
    }      
    mysqli_close($conex);
}

?>