<?php

if (isset($_REQUEST['enviar'])){

    $id_categoria_gasto = $_REQUEST['id_categoria_gasto'];
    $nombre_categoria = $_REQUEST['nombre_categoria'];

    require_once("conexion.php");
    
    $resultado_modi = mysqli_query($conex,"UPDATE categoria_gasto SET nombre_categoria = '$nombre_categoria' 
    WHERE id_categoria_gasto = $id_categoria_gasto ") 
    or die ("problema en la consulta".mysqli_error($conex));
    if ($resultado_modi){
        header('location: categoria_gasto_panel.php');
    } else {
        echo 'se produjo un error al modificar';
    }      
    mysqli_close($conex);
}

?>