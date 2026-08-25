<?php
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,300italic,700,700italic">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/milligram/1.4.1/milligram.css">
    <title>Panaderia M - Iniciar Sesión</title>
</head>
<body>
    <div class="container" style="width: 500px">
         <br>
        <form action="login.php" method="post">
            <h3>Panaderia M - Iniciar Sesión</h3>
            
            <div>
                <label for="username">Usuario</label>
                <input type="text" name="username">
            </div>

            <div>
                <label for="password">Contraseña</label>
                <!-- Input de contraseña -->
                <input type="password" name="password" id="password">
                
                <!-- Checkbox simple para mostrar/ocultar -->
                <label style="font-size: 15px; cursor: pointer;">
                    <input type="checkbox" onclick="togglePass()"> Ver contraseña
                </label>
            </div>

            <div>
                <button type="submit" class="button boton">Ingresar</button>
                <a class="button" href="form_recuperar_contra.php">¿Olvidaste tu Contraseña?</a>
            </div>
        </form>
    </div>

    <script>
        // Función básica para alternar la visibilidad
        function togglePass() {
            var x = document.getElementById("password");
            if (x.type === "password") {
                x.type = "text";
            } else {
                x.type = "password";
            }
        }
    </script>
</body>
</html>