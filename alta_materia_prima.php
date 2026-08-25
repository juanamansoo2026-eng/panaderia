<?php

if (isset($_REQUEST['enviar'])) { 
    $apellido_prov=$_REQUEST['apellido'];
    $nombre=$_REQUEST['nombre_mp']; 
    $marca=$_REQUEST['marca'];
    $precio=$_REQUEST['precio'];
    

    require_once("conexion.php"); 
    mysqli_query($conex,"SET NAMES 'UTF8'");
    $resultado_alta= mysqli_query($conex,"INSERT INTO materia_prima (proveedor_id, nombre_mp, marca_id, precio) 
    VALUE ('$apellido_prov','$nombre','$marca','$precio')") 
    or die ("problema en la consulta".mysqli_error($conex));

    if ($resultado_alta) { // crea un if para verificar si la consulta se ejecutó correctamente
        $materia_prima_grabado= mysqli_insert_id($conex); 

        $query_marca = "SELECT nombre_marca FROM marca_mp WHERE id_marca = '$marca'";
        $resultado_marca = mysqli_query($conex, $query_marca);
        $row_marca = mysqli_fetch_assoc($resultado_marca);
        $nombre_marca = $row_marca['nombre_marca']; // Nombre de la materia prima

        echo '<h2>La Materia Prima:</h2> ' . "<b>" . $nombre . "</b>" . 
        '<br> <h2>Marca:</h2> ' . "<b>" . $nombre_marca . "</b>" . 
        '<br> <h2>Precio:</h2> ' . "<b>$ " . $precio . "</b>" . 
        '<br> <h2>Se guardó correctamente.</h2>'; // Muestra un mensaje confirmando que el registro se guardó y muestra el ID del registro con el que se grabo
        ?>
        <form action="materia_prima_panel.php">
            <input type="submit" value="Continuar">
        </form>
        <?php
    
    
    } else {
        echo 'error en el proceso';
    }
    mysqli_close($conex);
}

?>