<?php
include 'conexion.php'; // Incluir la conexión

$registroExitoso = false; // Variable para controlar si el registro fue exitoso

// Obtener la fecha actual en formato YYYY-MM-DD
$fecha_actual = date('Y-m-d'); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $dni = $_POST['dni'];
    $rol = $_POST['id_rol'];
    $telefono = $_POST['telefono'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $fecha_alta = $fecha_actual;
    $fecha_modi = NULL;
    $fecha_baja = NULL;
    $baja = 0;

    $registroExitoso = registrarUsuario($nombre, $apellido, $dni, $rol, $telefono, $username, $password, $email, $fecha_alta, $fecha_modi, $fecha_baja, $baja);
}

function registrarUsuario($nombre, $apellido, $dni, $rol, $telefono, $username, $password, $email, $fecha_alta, $fecha_modi, $fecha_baja, $baja) {
    global $conex;

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    $stmt = $conex->prepare("INSERT INTO empleados (nombre, apellido, dni, id_rol, telefono, username, password, email, fecha_alta, fecha_modi, fecha_baja, baja) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
    die("ERROR AL PREPARAR LA CONSULTA: " . $conex->error);
    }
    $stmt->bind_param("ssssssssssss", $nombre, $apellido, $dni, $rol, $telefono, $username, $hashedPassword, $email, $fecha_alta, $fecha_modi, $fecha_baja, $baja);

    if ($stmt->execute()) {
        $stmt->close();
        return true; // Registro exitoso
    } else {
        $stmt->close();
        return false; // Error en el registro
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panadería M - Registrarse</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,300italic,700,700italic">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/milligram/1.4.1/milligram.css">
    <script>
        // Esta función se ejecuta cada vez que el usuario escribe en "nombre" o "apellido"
        function generarUsuario() {
            var nombre = document.getElementById("nombre").value; // Obtiene el valor de "nombre"
            var apellido = document.getElementById("apellido").value; // Obtiene el valor de "apellido"
            
            // Si ambos campos tienen algo escrito, generamos el nombre de usuario
            if (nombre && apellido) {
                var usuario = apellido.toLowerCase() + nombre.charAt(0).toLowerCase(); // Combina nombre y la primera letra del apellido
                document.getElementById("username").value = usuario; // Coloca el resultado en el campo "usuario"
            }
        }
    </script>
</head>
<body>
    <div class="container">
        <?php if ($registroExitoso): ?>
            <p>Registro exitoso. Puedes iniciar sesión.</p>
            <form action="index.php" method="get">
                <button type="submit" class="link-button">Volver al Menú Principal</button>
            </form>
        <?php else: ?>
            <h2>Registrarse - Panadería M</h2>
            <form action="nuevo_emple.php" method="post">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" required oninput="generarUsuario()"> <!-- Ejecuta la función al escribir -->
                
                <label for="apellido">Apellido:</label>
                <input type="text" id="apellido" name="apellido" required oninput="generarUsuario()"> <!-- Ejecuta la función al escribir -->
                
                <label for="dni">DNI:</label>
                <input type="text" name="dni" maxlength="15" required>

                <label for="id_rol">Rol:</label>

                <select name="id_rol" id="id_rol" required>
                    <option value="">Seleccione un rol</option>

                    <?php
                    $consulta_roles = "SELECT * FROM roles";
                    $resultado_roles = mysqli_query($conex, $consulta_roles);

                    foreach ($resultado_roles as $rol) {
                        echo "<option value='" . $rol['id_rol'] . "'>";
                        echo $rol['nombre_rol'];
                        echo "</option>";
                    }
                    ?>
                </select>
                
                <label for="telefono">Teléfono:</label>
                <input type="text" name="telefono" required>
                
                <label for="username">Usuario:</label>
                <input type="text" id="username" name="username" required readonly> <!-- el Usuario se genera automáticamente -->
                
                <label for="password">Contraseña:</label>
                <input type="password" name="password" required>
                
                <label for="email">Email:</label>
                <input type="email" name="email" required>

                <input type="hidden" name="baja" value="1">

                <br>
                <button type="submit" class="link-button">Registrarse</button>
                <a class="button" href="empleados_panel.php">Cancelar</a>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>