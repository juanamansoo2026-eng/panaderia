<?php
if (isset($_REQUEST['eliminar'])) { //pregunto si le llego la variable 
    $id_venta= $_REQUEST['id_venta']; //la cargamos en una variable

    require_once("conexion.php"); //conexion a la bd

    $resultado_baja= mysqli_query($conex,"DELETE FROM ventas WHERE id_venta = '$id_venta'") //busca la coincidencia del id seleccioando con el id de la db
    or die ("problema en la consulta".mysqli_error($conex)); //mysqli_error me muestra un mensaje con el problema y su linea, "or die" es si sale bien no pasa nada, "O die" o te sale el mensaje error
    if ($resultado_baja) {
        $resultado_baja_detalle= mysqli_query($conex,"DELETE FROM detalle_venta WHERE venta_id = '$id_venta'"); //busca la coincidencia del id seleccioando con el id de la db       
        mysqli_close($conex);
        header('location: ventas_panel.php');
    } else {
        echo "se produjo un error al eliminar";
    }
}
?>