<?php

if (isset($_REQUEST['enviar'])) { 
    // Obtiene los valores del formulario
    $nombre_mp = $_REQUEST['nombre_mp'];
    $nombre_marca = $_REQUEST['nombre_marca']; 
    $cant_ingreso = $_REQUEST['cant_ingreso']; 
    $cant_salida = $_REQUEST['cant_salida'];
    $stock_total = $_REQUEST['stock_total'];
    $fecha_modi = $_REQUEST['fecha_modi'];

    require_once("conexion.php"); 
    mysqli_query($conex, "SET NAMES 'UTF8'");
    $resultado_alta = mysqli_query($conex, "INSERT INTO inventario (materia_prima_id, marca_id, cantidad_ingreso_mp, cantidad_salida_mp, stock_total, fecha_modi) 
    VALUES ('$nombre_mp', '$nombre_marca', '$cant_ingreso', '$cant_salida', '$stock_total', '$fecha_modi')") 
    or die ("Problema en la consulta: ".mysqli_error($conex));

    if ($resultado_alta) {
        // Obtiene el ID del último registro insertado
        $ingreso_salida_mp_grabado = mysqli_insert_id($conex); 
        
        // Obtener el nombre de la materia prima a partir del ID
        $query_nombre_mp = "SELECT nombre_mp FROM materia_prima WHERE id_materia_prima = '$nombre_mp'";
        $resultado_nombre_mp = mysqli_query($conex, $query_nombre_mp);
        $row_nombre_mp = mysqli_fetch_assoc($resultado_nombre_mp);
        $nombre_producto = $row_nombre_mp['nombre_mp']; // Nombre de la materia prima

        $query_marca = "SELECT nombre_marca FROM marca_mp WHERE id_marca = '$nombre_marca'";
        $resultado_marca = mysqli_query($conex, $query_marca);
        $row_marca = mysqli_fetch_assoc($resultado_marca);
        $nombre_de_la_marca = $row_marca['nombre_marca']; // Nombre de la materia prima

        // Mostrar mensaje de confirmación
        echo '<h2>La Materia Prima:</h2> ' . "<b>" . $nombre_producto . "</b>" .
        '<br> <h2>Marca:</h2> ' . "<b>" . $nombre_de_la_marca . "</b>" . 
        '<br> <h2>Cantidad de Ingreso:</h2> ' . "<b>" . $cant_ingreso . "</b>" . 
        '<br> <h2>Cantidad de Salida: </h2>' . "<b>" . $cant_salida . "</b>" . 
        '<br> <h2>Stock Total: </h2>' . "<b>" . $stock_total . "</b>" . 
        '<br> <h2>Fecha Modificación: </h2>' . "<b>" . $fecha_modi . "</b>" . 
        '<br> <h2>Se guardó correctamente.</h2>';
        
        // Mostrar botón para continuar
        ?>
        <form action="inventario_panel.php">
            <input type="submit" value="Continuar">
        </form>
        <?php
    } else {
        echo 'Error en el proceso';
    }

    // Cerrar la conexión
    mysqli_close($conex);
}
?>