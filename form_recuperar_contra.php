<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panadería M - Recuperación de Contraseña</title>
    <link rel="stylesheet" href="css/panaderia-moderna.css">
</head>
<body>
    <?php include("menu.php"); ?>
    <div class="container form-container" style="width: 500px">
        <br>
        <h3>Panaderia M - Recuperación de Contraseña</h3>
        <form action="recuperar_contra.php" method="post">
            <label for="username">Usuario</label>
            <input type="text" name="username" required>
            <button type="submit">Recuperar Contraseña</button>
        </form>
    </div>
    <?php include("footer.php"); ?>
</body>
</html>