<?php
require_once __DIR__ . '/auth.php';
?>

<?php

require_once("conexion.php");

$row_registro = null;
if (isset($_POST['id_empleado']) && ctype_digit((string) $_POST['id_empleado'])) {
    $id_empleado = (int) $_POST['id_empleado'];
    $registro = mysqli_query($conex, "SELECT empleados.*, roles.nombre_rol
        FROM empleados
        LEFT JOIN roles ON roles.id_rol = empleados.id_rol
        WHERE id_empleado = $id_empleado");

    if (!$registro || !($row_registro = mysqli_fetch_assoc($registro))) {
        die("No se encontró el empleado solicitado.");
    }
} else {
    header("Location: empleados_panel.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Usuario - Panadería M</title>
    <link rel="stylesheet" href="css/panaderia-moderna.css">
</head>
<body>
    <?php include("menu.php"); ?>
    <div class="container form-container" style="width: 500px">
        <h2>Modificación de Usuario - Panadería M</h2>
        <p>Solo se puede modificar el correo. La fecha de última modificación se actualiza sola.</p>
        <form action="modificar_emple.php" method="post" autocomplete="off">
            <input type="hidden" name="id_empleado" value="<?php echo $row_registro['id_empleado']; ?>">

            <label>DNI:</label>
            <input style="background: #e0e0e0;" type="text" value="<?php echo htmlspecialchars($row_registro['dni']); ?>" readonly>
            <br>

            <label>Apellido:</label>
            <input style="background: #e0e0e0;" type="text" value="<?php echo htmlspecialchars($row_registro['apellido']); ?>" readonly>
            <br>

            <label>Nombre:</label>
            <input style="background: #e0e0e0;" type="text" value="<?php echo htmlspecialchars($row_registro['nombre']); ?>" readonly>
            <br>

            <!-- Correo (único campo editable) -->
            <label for="email">Correo:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($row_registro['email']); ?>" required>
            <br>

            <label>Usuario:</label>
            <input style="background: #e0e0e0;" type="text" value="<?php echo htmlspecialchars($row_registro['username']); ?>" readonly>
            <br>

            <label>Rol:</label>
            <input style="background: #e0e0e0;" type="text" value="<?php echo htmlspecialchars((string) $row_registro['nombre_rol']); ?>" readonly>
            <br>

            <label>Activo:</label>
            <input style="background: #e0e0e0;" type="text" value="<?php echo ($row_registro['baja'] == 1) ? 'Sí' : 'No'; ?>" readonly>
            <br>

            <label>Fecha Alta:</label>
            <input style="background: #e0e0e0;" type="date" value="<?php echo $row_registro['fecha_alta']; ?>" readonly>
            <br>

            <label>Fecha Última Modificación:</label>
            <input style="background: #e0e0e0;" type="date" value="<?php echo $row_registro['fecha_modi']; ?>" readonly>
            <br>

            <label>Fecha Baja:</label>
            <input style="background: #e0e0e0;" type="date" value="<?php echo $row_registro['fecha_baja']; ?>" readonly>
            <br>

            <!-- Botones -->
            <div style="text-align: center; margin-top: 20px;">
                <button type="submit" name="Guardar"><svg class="ui-icon" aria-hidden="true" viewBox="0 0 24 24"><path d="M5 3h12l4 4v14H3V3zM7 3v6h10V3M7 21v-8h10v8"/></svg>Guardar Cambios</button>
                <a class="button button-outline" href="empleados_panel.php">Cancelar</a>
            </div>
        </form>
    </div>

    <?php include("footer.php"); ?>
</body>
</html>
