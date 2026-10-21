<?php

if (isset($_REQUEST['enviar'])){

    $id_cliente = $_REQUEST['id_cliente'];
    $nombre = $_REQUEST['nombre'];
    $apellido = $_REQUEST['apellido'];
    $direccion = $_REQUEST['direccion'];
    $telefono = $_REQUEST['telefono'];
    $descripcion = $_REQUEST['descripcion'];

    require_once("conexion.php");
    
    $resultado_modi = mysqli_query($conex,"UPDATE clientes SET nombre = '$nombre', apellido = '$apellido', 
    direccion = '$direccion', telefono = '$telefono', descripcion = '$descripcion' 
    WHERE id_cliente = $id_cliente ") 
    or die ("problema en la consulta".mysqli_error($conex));
    if ($resultado_modi){
        header('location: clientes_panel.php');
    } else {
        echo 'se produjo un error al modificar';
    }      
    mysqli_close($conex);
}

?>