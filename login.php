<?php
session_start(); // Iniciar la sesión
include 'conexion.php'; // Incluir la conexión

$loginExitoso = false; // Variable para controlar si el inicio de sesión fue exitoso

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $loginExitoso = loginUsuario($username, $password);
}

function loginUsuario($username, $password) {
    global $conex;

    $stmt = $conex->prepare("SELECT password FROM empleados WHERE username = ? AND baja = 1");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($hashedPassword);
        $stmt->fetch();

        if (password_verify($password, $hashedPassword)) {
            $_SESSION['username'] = $username; // Guardar el nombre de usuario en la sesión

            // Redirigir a page1.php después de un inicio de sesión exitoso
            header("Location: page1.php");
            return true;
            exit(); // Asegurarse de que no se ejecute el código siguiente
        } else {
            echo "<p>Contraseña incorrecta</p>";
        }
    } else {
        echo "<p>Usuario no encontrado</p>";
    }

    $stmt->close();
    return false; // Si no existe inicio de conexion fallida
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/login.css">
    <title>Panaderia M - Iniciar Sesión</title>
</head>
<body>
    <div class="container" style="width: 500px">
        <?php if ($loginExitoso): ?>
            <!-- Este bloque ya no es necesario, ya que se redirige automáticamente -->
            <!-- Redirigiendo a page1.php, no es necesario mostrar el mensaje de bienvenida aquí. -->
        <?php else: ?>
            <h2>Panadería M - Iniciar Sesión</h2>
            <form action="login.php" method="post">
            <div>
                <label for="username">Usuario</label>
                <input type="text" name="username" required>
            </div>
            <div>
                <label for="password">Contraseña</label>
                <input type="password" name="password" required>
                <input type="checkbox" onclick="togglePass()"> Ver contraseña

            </div>
            <div>
                <button type="submit" class="boton">Ingresar</button>
            </div>
            </form>
            <form action="index.php" method="get">
                <button type="submit">Volver al Menú Principal</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>