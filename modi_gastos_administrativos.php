<?php
require_once __DIR__ . '/auth.php';
?>
<?php

if (isset($_REQUEST['enviar'])){

    $id_gasto = $_REQUEST['id_gasto'];
    $descripcion = $_REQUEST['descripcion'];
    $categoria_gasto_id = $_REQUEST['categoria_gasto_id'];
    $monto = $_REQUEST['monto'];
    $turno_id = $_REQUEST['turno_id'];
    $empleado = $_REQUEST['empleado'];
    $fecha = $_REQUEST['fecha'];

    require_once("conexion.php");

    // NO SE PUEDEN MODIFICAR NI BORRAR MOVIMIENTOS DE UNA CAJA CERRADA (así el cierre no cambia)
    require_once("caja_funciones.php");
    $caja_mov = cajaDelMovimiento($conex, 'gastos_administrativos', (int)$id_gasto);
    if ($caja_mov && $caja_mov['estado'] != 'ABIERTO') {
        mysqli_close($conex);
        paginaAviso('No se puede modificar el gasto', 'Pertenece a la caja N° ' . $caja_mov['id_turno'] . ', que ya está cerrada. Los movimientos de una caja cerrada no se pueden modificar ni eliminar.', 'gastos_administrativos_panel.php');
    }

    // EL GASTO SIGUE EN SU MISMA CAJA (no se puede pasar a otra)
    $actual = mysqli_fetch_assoc(mysqli_query($conex, "SELECT turno_id, monto FROM gastos_administrativos WHERE id_gasto = " . (int)$id_gasto));
    $turno_id = (int)$actual['turno_id'];

    // SI ES DE LA CAJA ABIERTA, EL NUEVO MONTO NO PUEDE SUPERAR EL EFECTIVO DISPONIBLE
    if ($caja_mov && $caja_mov['estado'] == 'ABIERTO') {
        $disponible = totalesCaja($conex, cajaPorId($conex, $turno_id))['esperado'] + $actual['monto'];
        if (round((float)$monto, 2) > round($disponible, 2)) {
            mysqli_close($conex);
            paginaAviso('No se puede modificar el gasto', 'El nuevo monto es de <b>' . pesos($monto) . '</b> y la caja N° ' . $turno_id . ' tiene <b>' . pesos($disponible) . '</b> disponibles para este gasto.', 'gastos_administrativos_panel.php');
        }
    }
    
    $resultado_modi = mysqli_query($conex,"UPDATE gastos_administrativos 
    SET turno_id = '$turno_id', categoria_gasto_id = '$categoria_gasto_id', descripcion = '$descripcion', monto = '$monto', empleado = '$empleado', fecha = '$fecha'
    WHERE id_gasto = $id_gasto") 

    or die ("problema en la consulta".mysqli_error($conex));
    if ($resultado_modi){
        header('location: gastos_administrativos_panel.php');
    } else {
        echo 'se produjo un error al modificar';
    }      
    mysqli_close($conex);
}

?>