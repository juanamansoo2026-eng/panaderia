<?php
require_once __DIR__ . '/auth.php';
?>
<?php
// LAS CAJAS NO SE ELIMINAN NI SE MODIFICAN A MANO: se abren y se cierran con su arqueo.
// (borrar una caja borraría también sus compras y cambiaría cierres ya hechos)
require_once("caja_funciones.php");
paginaAviso('No se puede eliminar la caja', 'Las cajas no se eliminan ni se modifican. Se abren y se cierran desde el panel de Caja, y una caja cerrada queda tal como se cerró.', 'turno_caja_panel.php', 'Ir al historial de cajas');
if (isset($_REQUEST['eliminar'])) { //pregunto si le llego la variable 
    $id_turno= $_REQUEST['id_turno']; //la cargamos en una variable

    require_once("conexion.php"); //conexion a la bd

    $resultado_baja= mysqli_query($conex,"DELETE FROM turno_caja WHERE id_turno = '$id_turno'") //busca la coincidencia del id seleccioando con el id de la db
    or die ("problema en la consulta".mysqli_error($conex)); //mysqli_error me muestra un mensaje con el problema y su linea, "or die" es si sale bien no pasa nada, "O die" o te sale el mensaje error
    if ($resultado_baja) {
        mysqli_close($conex);
        header('location: turno_caja_panel.php');
    } else {
        echo "se produjo un error al eliminar";
    }
}
?>