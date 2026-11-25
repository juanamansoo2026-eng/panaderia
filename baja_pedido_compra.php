<?php
require_once __DIR__ . '/auth.php';
?>
<?php
if (isset($_REQUEST['eliminar'])) { //pregunto si le llego la variable 
    $id_pedido_compra= $_REQUEST['id_pedido_compra']; //la cargamos en una variable

    require_once("conexion.php"); //conexion a la bd

    // NO SE PUEDEN MODIFICAR NI BORRAR MOVIMIENTOS DE UNA CAJA CERRADA (así el cierre no cambia)
    require_once("caja_funciones.php");
    $caja_mov = cajaDelMovimiento($conex, 'pedido_compra', (int)$id_pedido_compra);
    if ($caja_mov && $caja_mov['estado'] != 'ABIERTO') {
        mysqli_close($conex);
        paginaAviso('No se puede eliminar el pedido de compra', 'Pertenece a la caja N° ' . $caja_mov['id_turno'] . ', que ya está cerrada. Los movimientos de una caja cerrada no se pueden modificar ni eliminar.', 'pedido_compra_panel.php');
    }

    $resultado_baja= mysqli_query($conex,"DELETE FROM pedido_compra WHERE id_pedido_compra = '$id_pedido_compra'") //busca la coincidencia del id seleccioando con el id de la db
    or die ("problema en la consulta".mysqli_error($conex)); //mysqli_error me muestra un mensaje con el problema y su linea, "or die" es si sale bien no pasa nada, "O die" o te sale el mensaje error
    if ($resultado_baja) {
        mysqli_close($conex);
        header('location: pedido_compra_panel.php');
    } else {
        echo "se produjo un error al eliminar";
    }
}
?>