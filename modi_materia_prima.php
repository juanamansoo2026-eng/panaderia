<?php

if (isset($_REQUEST['enviar'])){

    $id_materia_prima = $_REQUEST['id_materia_prima'];
    $nombre = $_REQUEST['nombre_mp'];
    $marca = $_REQUEST['marca'];
    $apellido_prov = $_REQUEST['apellido'];
    $precio=$_REQUEST['precio'];

    require_once("conexion.php");
    
    $resultado_modi = mysqli_query($conex,"UPDATE materia_prima SET nombre_mp = '$nombre', proveedor_id = '$apellido_prov', marca_id = '$marca', precio = '$precio'
    WHERE id_materia_prima = $id_materia_prima") 
    or die ("problema en la consulta".mysqli_error($conex));
    if ($resultado_modi){
        header('location: materia_prima_panel.php');
    } else {
        echo 'se produjo un error al modificar';
    }      
    mysqli_close($conex);
}

?>