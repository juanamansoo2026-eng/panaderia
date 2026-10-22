<?php
if (isset($_REQUEST['eliminar'])) { //pregunto si le llego la variable 
    $id_producto= $_REQUEST['id_producto']; //la cargamos en una variable

    require_once("conexion.php"); //conexion a la bd

    $resultado_baja= mysqli_query($conex,"DELETE FROM productos WHERE id_producto = $id_producto") //busca la coincidencia del id seleccioando con el id de la db
    or die ("problema en la consulta".mysqli_error($conex)); //mysqli_error me muestra un mensaje con el problema y su linea, "or die" es si sale bien no pasa nada, "O die" o te sale el mensaje error
    if ($resultado_baja) {
        mysqli_close($conex);
        header('location: productos_panel.php');
    } else {
        echo "se produjo un error al eliminar";
    }
}
?>