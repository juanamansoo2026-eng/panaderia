<?php

if (isset($_REQUEST['enviar'])){

    $id_producto = $_REQUEST['id_producto'];
    $nombre = $_REQUEST['nombre'];
    $tipo = $_REQUEST['tipo'];
    $precio = $_REQUEST['precio'];

    require_once("conexion.php");
    
    $resultado_modi = mysqli_query($conex,"UPDATE productos SET nombre = '$nombre', tipo = '$tipo', precio = '$precio' 
    WHERE id_producto = $id_producto ") // acordarse de poner where id con su id para que se cambia el id o usuario seleccionado, pq si no lo tiene se cambia y guardad todo los registros/usuarios de la bd
    or die ("problema en la consulta".mysqli_error($conex));
    if ($resultado_modi){
        header('location: productos_panel.php');
    } else {
        echo 'se produjo un error al modificar';
    }      
    mysqli_close($conex);
}

?>