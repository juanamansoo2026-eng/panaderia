<?php
require_once __DIR__ . '/auth.php';
?>
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
    ?>
    <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Categoria de gasto guardada - Panadería M</title>
            <link rel="stylesheet" href="css/panaderia-moderna.css">
        </head>
        <body>
            <?php include("menu.php"); ?>
            <main class="container form-container resultado-container">
                <h2>Categoria de gasto registrada</h2>
                <div class="mensaje exito" role="status">
                    <svg class="ui-icon" aria-hidden="true" viewBox="0 0 24 24"><path d="m5 12 4 4L19 6"/></svg>
                    La categoria de gasto se guardó correctamente.
                </div>
                <div class="resultado-acciones">
                    <a class="button" href="gastos_administrativos_panel.php">Continuar</a>
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
                <title>Error al guardar la categoria de gasto - Panadería M</title>
                <link rel="stylesheet" href="css/panaderia-moderna.css">
            </head>
            <body>
                <?php include("menu.php"); ?>
                <main class="container form-container resultado-container">
                    <h2>No se pudo registrar la categoria de gasto</h2>
                    <div class="mensaje error" role="alert">Ocurrió un error durante el proceso. Revisá los datos e intentá nuevamente.</div>
                    <div class="resultado-acciones">
                        <a class="button button-outline" href="alta_categoria_gasto.php">Volver al formulario</a>
                    </div>
                </main>
                <?php include("footer.php"); ?>
            </body>
            </html>
            <?php
}

?>