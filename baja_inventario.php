<?php
if (isset($_REQUEST['eliminar'])) { //pregunto si le llego la variable 
    $id_inventario= $_REQUEST['id_inventario']; //la cargamos en una variable

    require_once("conexion.php"); //conexion a la bd

    $resultado_baja= mysqli_query($conex,"DELETE FROM inventario WHERE id_inventario = '$id_inventario'") 
    or die ("problema en la consulta".mysqli_error($conex));
    if ($resultado_baja) {
        mysqli_close($conex);
        header('location: inventario_panel.php');
    } else {
        echo "se produjo un error al eliminar";
    }
}
?>