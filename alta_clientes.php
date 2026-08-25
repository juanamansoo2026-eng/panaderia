<?php

if (isset($_REQUEST['enviar'])) { 
    $nombre=$_REQUEST['nombre'];
    $apellido=$_REQUEST['apellido'];
    $direccion=$_REQUEST['direccion'];
    $telefono=$_REQUEST['telefono'];
    $descripcion=$_REQUEST['descripcion'];

    require_once("conexion.php"); 
    mysqli_query($conex,"SET NAMES 'UTF8'");

    $resultado_alta= mysqli_query($conex,"INSERT INTO clientes (nombre, apellido, direccion, telefono, descripcion) 
    VALUE ('$nombre','$apellido','$direccion','$telefono','$descripcion')");

    if ($resultado_alta) { // crea un if para verificar si la consulta se ejecutó correctamente
        $id_grabado= mysqli_insert_id($conex); // Obtiene el ID del último registro insertado en la base de datos, osea el que acabamos de agregar
        echo '<h2>El Cliete:</h2> ' . "<b>" . $nombre . " " . $apellido . "</b>" . 
        '<br> <h2>Dirección:</h2> ' . "<b>" . $direccion . "</b>" . 
        '<br> <h2>Telefono:</h2> ' . "<b>" . $telefono . "</b>" . 
        '<br> <h2>Vende: </h2>' . "<b>" . $descripcion . "</b>" .  
        '<br> <h2>Se guardó correctamente.</h2>'; // Muestra un mensaje confirmando que el registro se guardó y muestra el ID del registro con el que se grabo
        ?>
        <form action="clientes_panel.php">
            <input type="submit" value="Continuar">
        </form>
        <?php
    
    } else {
        echo 'error en el proceso';
    }
    mysqli_close($conex);
}

?>