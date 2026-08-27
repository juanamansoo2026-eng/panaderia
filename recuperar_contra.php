<?php

$mensaje = "";
$mostrarNueva = false;
$tipoMensaje = "";
$username = "";

$servidor = "localhost";
$usuario = "root";
$pass = "";
$basedatos = "original";

$conn = mysqli_connect($servidor, $usuario, $pass, $basedatos);

if (!$conn) {
    die("Error en la conexión: " . mysqli_connect_error());
}


/* --------------------------------
   BUSCAR USUARIO
--------------------------------- */

if (isset($_POST['buscar_usuario'])) {

    $username = $_POST['username'] ?? '';

    if ($username == '') {

        $mensaje = "Ingrese un nombre de usuario.";

    } else {

        $query = "SELECT username FROM empleados 
                  WHERE username = ? AND baja = 1";

        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {

            
            $mensaje = "Usuario encontrado: " . $username;
            $tipoMensaje = "exito";
            $mostrarNueva = true;

        } else {

            $mensaje = "El nombre de usuario no existe.";
            $tipoMensaje = "error";

        }

        $stmt->close();
    }
}


/* --------------------------------
   ACTUALIZAR CONTRASEÑA
--------------------------------- */

if (isset($_POST['submit_new_password'])) {

    $username = $_POST['username'] ?? '';
    $new_password = $_POST['new_password'] ?? '';

    if ($new_password == '') {

        $mensaje = "La nueva contraseña no puede estar vacía.";
        $tipoMensaje = "advertencia";
        $mostrarNueva = true;

    } else {

        $hashedPassword = password_hash(
            $new_password,
            PASSWORD_DEFAULT
        );

        $updateQuery = "UPDATE empleados 
                        SET password = ? 
                        WHERE username = ?";

        $updateStmt = $conn->prepare($updateQuery);

        $updateStmt->bind_param(
            "ss",
            $hashedPassword,
            $username
        );

        $updateStmt->execute();

        if ($updateStmt->affected_rows > 0) {

            $mensaje = "Contraseña actualizada correctamente.";
            $tipoMensaje = "exito";
            $mostrarNueva = false;

        } else {

            $mensaje = "No se pudo actualizar la contraseña.";
            $tipoMensaje = "error";

        }

        $updateStmt->close();
    }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Panadería M - Recuperación de Contraseña</title>

    <link rel="stylesheet" href="css/login.css">

</head>

<body>

<div class="container">

    <h2>Panadería M</h2>

    <h3>Recuperación de Contraseña</h3>


    <?php if ($mensaje != ""): ?>

        <p class="mensaje <?php echo $tipoMensaje; ?>">
            <?php echo htmlspecialchars($mensaje); ?>
        </p>

    <?php endif; ?>


    <?php if (!$mostrarNueva): ?>

        <!-- FORMULARIO PARA BUSCAR USUARIO -->

        <form action="recuperar_contra.php" method="post">

            <div>

                <label for="username">
                    Nombre de usuario
                </label>

                <input
                    type="text"
                    id="username"
                    name="username"
                    required
                >

            </div>


            <div>

                <button
                    type="submit"
                    name="buscar_usuario"
                    class="boton"
                >
                    Buscar usuario
                </button>

            </div>

        </form>


        <div class="recuperar">

            <a href="login.php">
                Volver al inicio de sesión
            </a>

        </div>


    <?php else: ?>

        <!-- FORMULARIO PARA CAMBIAR CONTRASEÑA -->

        <form action="recuperar_contra.php" method="post">

            <p>
                Usuario encontrado:
                <strong>
                    <?php echo htmlspecialchars($username); ?>
                </strong>
            </p>


            <div>

                <label for="new_password">
                    Nueva contraseña
                </label>

                <input
                    type="password"
                    id="new_password"
                    name="new_password"
                    required
                >

            </div>


            <input
                type="hidden"
                name="username"
                value="<?php echo htmlspecialchars($username); ?>"
            >


            <div>

                <button
                    type="submit"
                    name="submit_new_password"
                    class="boton"
                >
                    Actualizar contraseña
                </button>

            </div>

        </form>


        <div class="recuperar">

            <a href="login.php">
                Volver al inicio de sesión
            </a>

        </div>

    <?php endif; ?>

</div>

</body>

</html>

<?php
mysqli_close($conn);
?>