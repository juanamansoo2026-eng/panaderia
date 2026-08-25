<?php

if (isset($_REQUEST['enviar'])){

    $id_proveedor = $_REQUEST['id_proveedor'];
    $nombre = $_REQUEST['nombre'];
    $apellido = $_REQUEST['apellido'];
    $telefono = $_REQUEST['telefono'];
    $descripcion = $_REQUEST['descripcion'];

    require_once("conexion.php");
    
    $resultado_modi = mysqli_query($conex,"UPDATE proveedor SET nombre = '$nombre', apellido = '$apellido', 
    telefono = '$telefono', descripcion = '$descripcion' 
    WHERE id_proveedor = $id_proveedor ") 
    or die ("problema en la consulta".mysqli_error($conex));
    if ($resultado_modi){
        header('location: proveedores_panel.php');
    } else {
        echo 'se produjo un error al modificar';
    }      
    mysqli_close($conex);
}

?>