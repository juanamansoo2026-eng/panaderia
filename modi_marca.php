<?php

if (isset($_REQUEST['enviar'])){

    $id_marca = $_REQUEST['id_marca'];
    $nombre_marca = $_REQUEST['nombre_marca'];

    require_once("conexion.php");
    
    $resultado_modi = mysqli_query($conex,"UPDATE marca_mp SET nombre_marca = '$nombre_marca' 
    WHERE id_marca = $id_marca ") 
    or die ("problema en la consulta".mysqli_error($conex));
    if ($resultado_modi){
        header('location: marca_panel.php');
    } else {
        echo 'se produjo un error al modificar';
    }      
    mysqli_close($conex);
}

?>