<?php

if (isset($_REQUEST['enviar'])){

    $id_inventario = $_REQUEST['id_inventario'];
    $nombre_mp = $_REQUEST['nombre_mp'];
    $nombre_marca = $_REQUEST['nombre_marca'];
    $cant_ingreso = $_REQUEST['cant_ingreso'];
    $cant_salida = $_REQUEST['cant_salida'];
    $stock_total = $_REQUEST['stock_total'];
    $fecha_modi = $_REQUEST['fecha_modi'];

    require_once("conexion.php");
    
    $resultado_modi = mysqli_query($conex,"UPDATE inventario SET materia_prima_id = '$nombre_mp', marca_id = '$nombre_marca', cantidad_ingreso_mp = '$cant_ingreso', cantidad_salida_mp = '$cant_salida', stock_total = '$stock_total', fecha_modi = '$fecha_modi'
    WHERE id_inventario = $id_inventario") 
    or die ("problema en la consulta".mysqli_error($conex));
    if ($resultado_modi){
        header('location: inventario_panel.php');
    } else {
        echo 'se produjo un error al modificar';
    }      
    mysqli_close($conex);
}

?>