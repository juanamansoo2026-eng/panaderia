
<?php
session_start();
include 'conexion.php';

$loginExitoso = false;
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($username !== '' && $password !== '') {

        $resultado = loginUsuario($username, $password);

        if ($resultado !== true) {
            $error = $resultado;
        }
    }
}
function loginUsuario($username, $password) {
    global $conex;

    $stmt = $conex->prepare(
        "SELECT password FROM empleados WHERE username = ? AND baja = 1"
    );

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {

        $stmt->bind_result($hashedPassword);
        $stmt->fetch();

        if (password_verify($password, $hashedPassword)) {

            $_SESSION['username'] = $username;

            header("Location: page1.php");
            exit();

        } else {

            $stmt->close();
            return "Contraseña incorrecta";
        }

    } else {

        $stmt->close();
        return "Usuario no encontrado";
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="css/login.css">

    <title>Panadería M - Iniciar Sesión</title>

</head>

<body>

    <div class="container">

        <h2>Panadería M - Iniciar Sesión</h2>
        <?php if ($error != ""): ?>

            <p class="mensaje error">
                <?php echo htmlspecialchars($error); ?>
            </p>

        <?php endif; ?>

        <form action="login.php" method="post">

            <div>

                <label for="username">Usuario</label>

                <input
                    type="text"
                    id="username"
                    name="username"
                    required
                >

            </div>

            <div>

                <label for="password">Contraseña</label>

                <input
                    type="password"
                    id="password"
                    name="password"
                    required
                >

                <div class="mostrar">

                    <input
                        type="checkbox"
                        id="mostrarPass"
                        onclick="togglePass()"
                    >

                    <label for="mostrarPass" style="padding-top: 7px;">
                        Ver contraseña
                    </label>

                </div>

            </div>

            <div>

                <button type="submit" class="boton">
                    Ingresar
                </button>

            </div>

        </form>

        <!-- Recuperar contraseña -->
        <div class="recuperar">

            <a href="recuperar_contra.php">
                ¿Olvidaste tu contraseña?
            </a>

        </div>

    </div>

    <script>

        function togglePass() {

            const password = document.getElementById("password");

            if (password.type === "password") {

                password.type = "text";

            } else {

                password.type = "password";

            }

        }

    </script>

</body>

</html>

