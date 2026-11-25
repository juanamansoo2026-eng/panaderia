<?php
require_once __DIR__ . '/auth.php';
require_once("conexion.php");
require_once __DIR__ . '/usuarios_funciones.php';

// Se muestra después del login cuando el usuario tiene una clave temporal
// (recién dado de alta o con la clave restablecida). Puede usarse también para cambiar la clave cuando quiera.

$mensaje = "";
$tipoMensaje = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $clave_actual = $_POST['clave_actual'] ?? '';
    $clave_nueva = $_POST['clave_nueva'] ?? '';
    $clave_repetir = $_POST['clave_repetir'] ?? '';

    $consulta = $conex->prepare("SELECT password FROM empleados WHERE username = ? AND baja = 1");
    $consulta->bind_param("s", $_SESSION['username']);
    $consulta->execute();
    $consulta->bind_result($hashActual);
    $encontrado = $consulta->fetch();
    $consulta->close();

    if (!$encontrado) {
        $mensaje = "No se encontró el usuario.";
        $tipoMensaje = "error";
    } elseif (!password_verify($clave_actual, $hashActual)) {
        $mensaje = "La clave actual no es correcta.";
        $tipoMensaje = "error";
    } elseif (strlen($clave_nueva) < 6) {
        $mensaje = "La clave nueva debe tener al menos 6 caracteres.";
        $tipoMensaje = "error";
    } elseif ($clave_nueva !== $clave_repetir) {
        $mensaje = "Las claves nuevas no coinciden.";
        $tipoMensaje = "error";
    } elseif ($clave_nueva === $clave_actual) {
        $mensaje = "La clave nueva tiene que ser distinta de la actual.";
        $tipoMensaje = "error";
    } else {
        $hashNuevo = hashClaveUsuario($clave_nueva);
        $fecha_modi = date('Y-m-d');

        $actualizar = $conex->prepare("UPDATE empleados SET password = ?, fecha_modi = ? WHERE username = ?");
        $actualizar->bind_param("sss", $hashNuevo, $fecha_modi, $_SESSION['username']);

        if ($actualizar->execute()) {
            unset($_SESSION['cambiar_clave']);
            header("Location: page1.php");
            exit();
        }

        $mensaje = "No se pudo cambiar la clave.";
        $tipoMensaje = "error";
    }
}

$obligatorio = !empty($_SESSION['cambiar_clave']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/login.css">
    <title>Panadería M - Cambiar Clave</title>
</head>
<body>
    <div class="container">
        <h2>Panadería M - Cambiar Clave</h2>

        <?php if ($obligatorio): ?>
            <p class="mensaje advertencia">
                Hola <?php echo htmlspecialchars($_SESSION['username']); ?>, tu clave es temporal.
                Tenés que elegir una clave nueva para seguir.
            </p>
        <?php endif; ?>

        <?php if ($mensaje != ""): ?>
            <p class="mensaje <?php echo $tipoMensaje; ?>"><?php echo htmlspecialchars($mensaje); ?></p>
        <?php endif; ?>

        <form action="cambiar_clave.php" method="post" autocomplete="off">
            <div>
                <label for="clave_actual">Clave actual (la temporal)</label>
                <input type="password" id="clave_actual" name="clave_actual" class="clave" required>
            </div>
            <div>
                <label for="clave_nueva">Clave nueva</label>
                <input type="password" id="clave_nueva" name="clave_nueva" class="clave" minlength="6" required>
            </div>
            <div>
                <label for="clave_repetir">Repetir clave nueva</label>
                <input type="password" id="clave_repetir" name="clave_repetir" class="clave" minlength="6" required>
                <div class="mostrar">
                    <input type="checkbox" id="mostrarPass" onclick="togglePass()">
                    <label for="mostrarPass" style="padding-top: 7px;">Ver claves</label>
                </div>
            </div>
            <div>
                <button type="submit" class="boton">Guardar Clave</button>
            </div>
        </form>

        <div class="recuperar">
            <?php if ($obligatorio): ?>
                <a href="cerrar.php">Cancelar y cerrar sesión</a>
            <?php else: ?>
                <a href="page1.php">Volver al menú</a>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function togglePass() {
            document.querySelectorAll('.clave').forEach(function (campo) {
                campo.type = (campo.type === 'password') ? 'text' : 'password';
            });
        }
    </script>
</body>
</html>
