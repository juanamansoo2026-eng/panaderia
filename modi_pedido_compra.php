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