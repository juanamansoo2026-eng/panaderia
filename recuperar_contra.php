<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,300italic,700,700italic">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/milligram/1.4.1/milligram.css">
    <title>Panadería M - Recuperación de Contraseña</title>
</head>
<body>
    <div class="container" style="width: 500px">
        <br>
        <h3>Panadería M - Recuperación de Contraseña</h3>

        <?php
        $servidor = "localhost";  // Servidor de la base de datos
        $usuario = "root";        // Usuario para la base de datos
        $pass = "";               // Contraseña (vacía en XAMPP por defecto)
        $basedatos = "original"; // Nombre de la base de datos

        // Establecer la conexión a la base de datos
        $conn = mysqli_connect($servidor, $usuario, $pass, $basedatos);

        // Verificar que la conexión haya sido exitosa
        if (!$conn) {
            die("Error en la conexión: " . mysqli_connect_error());
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            //Obtener el nombre de usuario desde el formulario
            $username = $_POST['username'];

            // 2. Verificar si el usuario existe en la base de datos
            $query = "SELECT * FROM empleados WHERE username = ? and baja = '1' "; // el baja = 1, es el que toma si el usuario se encontro
            $stmt = $conn->prepare($query);
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // El usuario existe, mostramos el formulario para cambiar la contraseña
                echo '
                <form action="recuperar_contra.php" method="post">
                    <label for="new_password">Nueva Contraseña</label>
                    <input type="password" name="new_password" required>
                    <button type="submit" name="submit_new_password">Actualizar Contraseña</button>
                    <input type="hidden" name="username" value="' . htmlspecialchars($username) . '">
                </form>
                ';
            } else {
                // El usuario no existe
                echo '<h4>El nombre de usuario no existe.</h4>';
                echo '<a class="button" href="form_recuperar_contra.php">Volver</a>';
            }

            // 3. Procesar la nueva contraseña si el formulario para actualizar fue enviado
            if (isset($_POST['submit_new_password'])) {
                $new_password = $_POST['new_password'];

                // Validar que la contraseña no esté vacía
                if (empty($new_password)) {
                    echo '<p>La nueva contraseña no puede estar vacía.</p>';
                } else {
                    // Encriptar la nueva contraseña
                    $hashedPassword = password_hash($new_password, PASSWORD_DEFAULT);

                    // Actualizar la contraseña en la base de datos
                    $updateQuery = "UPDATE empleados SET password = ? WHERE username = ?";
                    $updateStmt = $conn->prepare($updateQuery);
                    $updateStmt->bind_param("ss", $hashedPassword, $username);
                    $updateStmt->execute();

                    if ($updateStmt->affected_rows > 0) {
                        // Si la actualización fue exitosa
                        echo '<h3>Usuario: ' . '<b>' . htmlspecialchars($username) . '</b>' . '</h3>';
                        echo '<h3>Contraseña actualizada! <br> Su nueva contraseña es: ' . '<b>' . htmlspecialchars($new_password) . '</b></h3>';
                        echo '<a class="button" href="index.php">Volver al Menú Principal</a>';
                    } else {
                        // Si no se actualizó nada
                        echo '<p>No se pudo actualizar la contraseña. Por favor, intente nuevamente.</p>';
                    }
                }
            }
        }

        // Cerrar la conexión
        mysqli_close($conn);
        ?>

    </div>
</body>
</html>