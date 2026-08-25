<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,300italic,700,700italic">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/milligram/1.4.1/milligram.css">
    <title>Alta de Proveedores - Panadería M</title>
</head>
<body>
    <div class="container" style="width: 600px">
    <h2 style="text-align: center">Alta de Proveedores - Panadería M</h2>
    <form action="alta_proveedores.php" method="post" autocomplete="off" required> <!--autocomplete on/off es para que no se queden guardados datos-->
        <label>Nombre:</label>
        <input type="text" name="nombre" maxlength="20" required>
        <br>
        <label>Apellido:</label>
        <input type="text" name="apellido" maxlength="20" required>
        <br>
        <label>Telefono:</label>
        <input type="int" name="telefono" maxlength="20" required>
        <br>
        <label>¿Que Vende?:</label>
        <input type="text" name="descripcion" maxlength="50" required>
        <br>
        <input type="submit" name="enviar" value="Grabar">
        <input type="submit" value="Cancelar" form="volver">
    </form>
    <form action="productos_panel.php" id="volver" method="post"></form>
</body>
</html>