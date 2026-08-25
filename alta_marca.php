<?php

if (isset($_REQUEST['enviar'])) { 
    $nombre_marca=$_REQUEST['nombre_marca'];     

    require_once("conexion.php"); 
    mysqli_query($conex,"SET NAMES 'UTF8'");
    $resultado_alta= mysqli_query($conex,"INSERT INTO marca_mp (nombre_marca) 
    VALUE ('$nombre_marca')") 
    or die ("problema en la consulta".mysqli_error($conex));

    if ($resultado_alta) { // crea un if para verificar si la consulta se ejecutó correctamente
        $marca_grabado= mysqli_insert_id($conex); 
        echo '<h2>La Marca de la Materia Prima:</h2> ' . "<b>" . $nombre_marca . "</b>" . 
        '<br> <h2>Se guardó correctamente.</h2>'; // Muestra un mensaje confirmando que el registro se guardó y muestra el ID del registro con el que se grabo
        ?>
        <form action="marca_panel.php">
            <input type="submit" value="Continuar">
        </form>
        <?php
    
    } else {
        echo 'error en el proceso';
    }
    mysqli_close($conex);
}

?>