<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,300italic,700,700italic">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/milligram/1.4.1/milligram.css">
    <title>Alta de Categoria de Gastos - Panadería M</title>
</head>
<body>
    <div class="container" style="width: 600px">
    <h2 style="text-align: center">Alta de Categoria de Gastos - Panadería M</h2>
    <form action="alta_categoria_gasto.php" method="post" autocomplete="off" required> <!--autocomplete on/off es para que no se queden guardados datos-->
        <label>Nombre de la Categoria:</label>
        <input type="text" name="nombre_categoria" maxlength="50" required><!-- required es para que el campo si o si este completado, sino no avanza-->
        <br>

        <input type="submit" name="enviar" value="Grabar">
        <input type="submit" value="Cancelar" form="volver">
    </form>
    <form action="categoria_gasto_panel.php" id="volver" method="post"></form>
</body>
</html>