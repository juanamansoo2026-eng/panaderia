<?php
require_once __DIR__ . '/auth.php';
?>
<?php

if (isset($_REQUEST['enviar'])){

    $id_pedido_compra = $_REQUEST['id_pedido_compra'];
    $proveedor_id = $_REQUEST['id_proveedor'];
    $materia_prima_id = $_REQUEST['id_materia_prima'];
    $marca_pc = $_REQUEST['marca_pc'];
    $cantidad = $_REQUEST['cantidad'];
    $precio = $_REQUEST['precio'];
    $total = $cantidad * $precio;
    $fecha = $_REQUEST['fecha'];

    require_once("conexion.php");

    // NO SE PUEDEN MODIFICAR NI BORRAR MOVIMIENTOS DE UNA CAJA CERRADA (así el cierre no cambia)
    require_once("caja_funciones.php");
    $caja_mov = cajaDelMovimiento($conex, 'pedido_compra', (int)$id_pedido_compra);
    if ($caja_mov && $caja_mov['estado'] != 'ABIERTO') {
        mysqli_close($conex);
        paginaAviso('No se puede modificar el pedido de compra', 'Pertenece a la caja N° ' . $caja_mov['id_turno'] . ', que ya está cerrada. Los movimientos de una caja cerrada no se pueden modificar ni eliminar.', 'pedido_compra_panel.php');
    }

    // SI LA COMPRA ES DE LA CAJA ABIERTA, EL NUEVO TOTAL NO PUEDE SUPERAR EL EFECTIVO DISPONIBLE
    if ($caja_mov && $caja_mov['estado'] == 'ABIERTO') {
        $anterior = mysqli_fetch_assoc(mysqli_query($conex, "SELECT total FROM pedido_compra WHERE id_pedido_compra = " . (int)$id_pedido_compra));
        $disponible = totalesCaja($conex, cajaPorId($conex, $caja_mov['id_turno']))['esperado'] + $anterior['total'];
        if (round($total, 2) > round($disponible, 2)) {
            mysqli_close($conex);
            paginaAviso('No se puede modificar el pedido de compra', 'El nuevo total es de <b>' . pesos($total) . '</b> y la caja N° ' . $caja_mov['id_turno'] . ' tiene <b>' . pesos($disponible) . '</b> disponibles para esta compra.', 'pedido_compra_panel.php');
        }
    }
    
    $resultado_modi = mysqli_query($conex,"UPDATE pedido_compra SET proveedor_id = '$proveedor_id', materia_prima_id = '$materia_prima_id', marca_pc = '$marca_pc', precio = '$precio', cantidad = '$cantidad', total = '$total', fecha = '$fecha'
    WHERE id_pedido_compra = $id_pedido_compra") 
    or die ("problema en la consulta".mysqli_error($conex));
    if ($resultado_modi){
        header('location: pedido_compra_panel.php');
    } else {
        echo 'se produjo un error al modificar';
    }      
    mysqli_close($conex);
}

?>