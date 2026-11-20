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
    ?>
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Producto guardado - Panadería M</title>
            <link rel="stylesheet" href="css/panaderia-moderna.css">
        </head>
        <body>
            <?php include("menu.php"); ?>
            <main class="container form-container resultado-container">
                <h2>Producto registrado</h2>
                <div class="mensaje exito" role="status">
                    <svg class="ui-icon" aria-hidden="true" viewBox="0 0 24 24"><path d="m5 12 4 4L19 6"/></svg>
                    El producto se guardó correctamente.
                </div>
                <div class="resultado-acciones">
                    <a class="button" href="productos_panel.php">Continuar</a>
                </div>
            </main>
            <?php include("footer.php"); ?>
        </body>
        </html>
        <?php
        } else {
            ?>
            <!DOCTYPE html>
            <html lang="es">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Error al guardar el producto - Panadería M</title>
                <link rel="stylesheet" href="css/panaderia-moderna.css">
            </head>
            <body>
                <?php include("menu.php"); ?>
                <main class="container form-container resultado-container">
                    <h2>No se pudo registrar el producto</h2>
                    <div class="mensaje error" role="alert">Ocurrió un error durante el proceso. Revisá los datos e intentá nuevamente.</div>
                    <div class="resultado-acciones">
                        <a class="button button-outline" href="productos_alta.php">Volver al formulario</a>
                    </div>
                </main>
                <?php include("footer.php"); ?>
            </body>
            </html>
            <?php
    }
}

?>