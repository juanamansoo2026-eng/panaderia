<?php
require_once __DIR__ . '/auth.php';
?>
<?php
require_once("conexion.php");
require_once __DIR__ . '/usuarios_funciones.php';

// Restablecer la clave de un usuario: genera una clave nueva, la guarda cifrada
// y obliga al usuario a cambiarla en su próximo login.

$id_empleado = filter_input(INPUT_POST, 'id_empleado', FILTER_VALIDATE_INT);
if (!$id_empleado) {
    header("Location: empleados_panel.php");
    exit;
}

$consulta = mysqli_prepare($conex, "SELECT id_empleado, dni, apellido, nombre, email, username, baja
    FROM empleados WHERE id_empleado = ?");
mysqli_stmt_bind_param($consulta, 'i', $id_empleado);
mysqli_stmt_execute($consulta);
$row_registro = mysqli_fetch_assoc(mysqli_stmt_get_result($consulta));

if (!$row_registro) {
    die("No se encontró el empleado solicitado.");
}

$claveNueva = "";
$mensajeError = "";

if (isset($_POST['Restablecer'])) {
    $claveNueva = generarClaveTemporal();
    $hashedPassword = hashClaveTemporal($claveNueva);
    $fecha_modi = date('Y-m-d');

    $actualizar = mysqli_prepare($conex, "UPDATE empleados
        SET password = ?, fecha_modi = ?
        WHERE id_empleado = ?");
    mysqli_stmt_bind_param($actualizar, 'ssi', $hashedPassword, $fecha_modi, $id_empleado);

    if (!mysqli_stmt_execute($actualizar)) {
        $mensajeError = "No se pudo restablecer la clave: " . mysqli_error($conex);
        $claveNueva = "";
    }
}

mysqli_close($conex);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer Clave - Panadería M</title>
    <link rel="stylesheet" href="css/panaderia-moderna.css">
    <style>
        .datos-acceso { background: #fff8e1; border: 1px solid #e0c97f; border-radius: 8px; padding: 15px; margin: 15px 0; }
        .datos-acceso strong { font-size: 1.2rem; letter-spacing: 1px; }
    </style>
</head>
<body>
    <?php include("menu.php"); ?>
    <div class="container form-container" style="width: 500px">
        <h2>Restablecer Clave - Panadería M</h2>

        <?php if ($mensajeError != ""): ?>
            <p class="mensaje error"><?php echo htmlspecialchars($mensajeError); ?></p>
        <?php endif; ?>

        <label>Usuario:</label>
        <input style="background: #e0e0e0;" type="text" value="<?php echo htmlspecialchars($row_registro['username']); ?>" readonly>

        <label>Apellido y Nombre:</label>
        <input style="background: #e0e0e0;" type="text" value="<?php echo htmlspecialchars($row_registro['apellido'] . ', ' . $row_registro['nombre']); ?>" readonly>

        <label>DNI:</label>
        <input style="background: #e0e0e0;" type="text" value="<?php echo htmlspecialchars($row_registro['dni']); ?>" readonly>

        <?php if ($claveNueva != ""): ?>

            <div class="datos-acceso">
                <p>Nueva clave temporal: <strong><?php echo htmlspecialchars($claveNueva); ?></strong></p>
                <p>Entréguela al empleado. No se vuelve a mostrar y se le pedirá
                   que la cambie la próxima vez que inicie sesión.</p>
            </div>

            <a class="button" href="empleados_panel.php">Volver al Listado de Empleados</a>

        <?php else: ?>

            <?php if ($row_registro['baja'] != 1): ?>
                <p class="mensaje error">Este usuario está inactivo: no podrá ingresar hasta que se lo vuelva a activar.</p>
            <?php endif; ?>

            <p>Se va a generar una clave nueva al azar. La clave actual deja de funcionar.</p>

            <form action="empleados_clave.php" method="post">
                <input type="hidden" name="id_empleado" value="<?php echo $row_registro['id_empleado']; ?>">
                <div style="text-align: center; margin-top: 20px;">
                    <button type="submit" name="Restablecer" class="button">Generar Nueva Clave</button>
                    <a class="button button-outline" href="empleados_panel.php">Cancelar</a>
                </div>
            </form>

        <?php endif; ?>
    </div>

    <?php include("footer.php"); ?>
</body>
</html>
