<?php

if (isset($_REQUEST['enviar'])){

    $id_gasto = $_REQUEST['id_gasto'];
    $descripcion = $_REQUEST['descripcion'];
    $categoria_gasto_id = $_REQUEST['categoria_gasto_id'];
    $monto = $_REQUEST['monto'];
    $turno_id = $_REQUEST['turno_id'];
    $empleado = $_REQUEST['empleado'];
    $fecha = $_REQUEST['fecha'];

    require_once("conexion.php");
    
    $resultado_modi = mysqli_query($conex,"UPDATE gastos_administrativos 
    SET turno_id = '$turno_id', categoria_gasto_id = '$categoria_gasto_id', descripcion = '$descripcion', monto = '$monto', empleado = '$empleado', fecha = '$fecha'
    WHERE id_gasto = $id_gasto") 

    or die ("problema en la consulta".mysqli_error($conex));
    if ($resultado_modi){
        header('location: gastos_administrativos_panel.php');
    } else {
        echo 'se produjo un error al modificar';
    }      
    mysqli_close($conex);
}

?>