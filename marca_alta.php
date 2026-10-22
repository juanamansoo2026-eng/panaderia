<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alta de Marcas de Materia Prima - Panadería M</title>
    <link rel="stylesheet" href="css/panaderia-moderna.css">
</head>
<body>
    <?php include("menu.php"); ?>
    <div class="container form-container" style="width: 600px">
    <h2 style="text-align: center">Alta de Marcas de Materia Prima - Panadería M</h2>
    <form action="alta_marca.php" method="post" autocomplete="off" required> <!--autocomplete on/off es para que no se queden guardados datos-->
        <label>Nombre de la Marca:</label>
        <input type="text" name="nombre_marca" maxlength="50" required><!-- required es para que el campo si o si este completado, sino no avanza-->
        <br>

        <input type="submit" name="enviar" value="Grabar">
        <input type="submit" value="Cancelar" form="volver">
    </form>
    <form action="marca_panel.php" id="volver" method="post"></form>
        </div>
    <?php include("footer.php"); ?>
</body>
</html>