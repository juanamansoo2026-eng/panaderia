<?php

if (isset($_REQUEST['enviar'])) { 
    $nombre=$_REQUEST['nombre']; 
    $tipo=$_REQUEST['tipo'];
    $precio=$_REQUEST['precio'];

    require_once("conexion.php"); 
    mysqli_query($conex,"SET NAMES 'UTF8'");

    $resultado_alta= mysqli_query($conex,"INSERT INTO productos (nombre, tipo, precio) 
    VALUE ('$nombre','$tipo','$precio')");

    if ($resultado_alta) { // crea un if para verificar si la consulta se ejecutó correctamente
        $id_grabado= mysqli_insert_id($conex); // Obtiene el ID del último registro insertado en la base de datos, osea el que acabamos de agregar
        echo '<h2>El producto:</h2> ' . "<b>" . $nombre . "</b>" . 
        '<br> <h2>Tipo:</h2> ' . "<b>" . $tipo . "</b>" . 
        '<br> <h2>Precio: </h2>' . "<b>$" . $precio . "</b>" .  
        '<br> <h2>Se guardó correctamente.</h2>'; // Muestra un mensaje confirmando que el registro se guardó y muestra el ID del registro con el que se grabo
        ?>
        <form action="productos_panel.php">
            <input type="submit" value="Continuar">
        </form>
        <?php
    
    } else {
        echo 'error en el proceso';
    }
    mysqli_close($conex);
}

?>