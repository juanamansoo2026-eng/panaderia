<?php

if (isset($_REQUEST['enviar'])) { 
    $nombre_categoria=$_REQUEST['nombre_categoria'];     

    require_once("conexion.php"); 
    mysqli_query($conex,"SET NAMES 'UTF8'");
    $resultado_alta= mysqli_query($conex,"INSERT INTO categoria_gasto (nombre_categoria) 
    VALUE ('$nombre_categoria')") 
    or die ("problema en la consulta".mysqli_error($conex));

    if ($resultado_alta) { // crea un if para verificar si la consulta se ejecutó correctamente
        $categoria_grabado= mysqli_insert_id($conex); 
        echo '<h2>El Nombre de Categoria:</h2> ' . "<b>" . $nombre_categoria . "</b>" . 
        '<br> <h2>Se guardó correctamente.</h2>'; // Muestra un mensaje confirmando que el registro se guardó y muestra el ID del registro con el que se grabo
        ?>
        <form action="categoria_gasto_panel.php">
            <input type="submit" value="Continuar">
        </form>
        <?php
    
    
    } else {
        echo 'error en el proceso';
    }
    mysqli_close($conex);
}

?>