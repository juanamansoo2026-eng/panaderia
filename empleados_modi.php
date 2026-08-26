
<?php

require_once("./conexion.php");

$row_registro = null;
if (isset($_POST['id_empleado']) && ctype_digit((string) $_POST['id_empleado'])) {
    $id_empleado = (int) $_POST['id_empleado'];
    $registro = mysqli_query($conex, "SELECT * FROM empleados WHERE id_empleado = $id_empleado");

    if (!$registro || !($row_registro = mysqli_fetch_assoc($registro))) {
        die("No se encontró el empleado solicitado.");
    }
}

if (isset($_POST['Guardar'])) {

    $id_empleado = $_POST['id_empleado'];
    $id_rol = $_POST['id_rol'];
    $email = $_POST['email'];

    $fecha_modi = date('Y-m-d');

    $consulta = mysqli_query($conex, "
        UPDATE empleados
        SET 
            id_rol = '$id_rol',
            email = '$email',
            fecha_modi = '$fecha_modi'
        WHERE id_empleado = '$id_empleado'
    ");

    if ($consulta) {
        header("Location: ../empleados/empleados_panel.php");
        exit;
    } else {
        echo "Error al modificar: " . mysqli_error($conex);
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">    
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,300italic,700,700italic">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/milligram/1.4.1/milligram.css">
    <title>Modificar Empleados</title>
</head>
<body>
    <div class="container" style="width: 500px">
        <h2>Modificar los Datos de los Empleados - Panadería M</h2>
        <form action="modificar_emple.php" method="post" autocomplete="off">
            <!-- ID Empleado (solo lectura) -->
            <label>ID Empleado:</label>
            <input style="background: #e0e0e0;" type="text" name="id_empleado" value="<?php echo $row_registro['id_empleado']; ?>" readonly>
            <br>

            <!-- Nombre -->
            <label>Nombre:</label>
            <input type="text" name="nombre" value="<?php echo htmlspecialchars($row_registro['nombre']); ?>" required>
            <br>

            <!-- Apellido -->
            <label>Apellido:</label>
            <input type="text" name="apellido" value="<?php echo htmlspecialchars($row_registro['apellido']); ?>" required>
            <br>

            <!-- DNI -->
            <label>DNI:</label>
            <input type="text" name="dni" value="<?php echo htmlspecialchars($row_registro['dni']); ?>" required>
            <br>

            <!-- Rol (solo lectura) -->
            <!-- Rol -->
        <label>Rol:</label>

        <select name="id_rol" id="id_rol" required>
            <?php
            $consulta_roles = mysqli_query($conex, "SELECT * FROM roles");

            while ($rol = mysqli_fetch_assoc($consulta_roles)) {
                $seleccionado = ($rol['id_rol'] == $row_registro['id_rol']) ? 'selected' : '';

                echo "<option value='" . $rol['id_rol'] . "' $seleccionado>";
                echo $rol['nombre_rol'];
                echo "</option>";
            }
            ?>
        </select>

        <br>

            <!-- Telefono -->
            <label>Telefono:</label>
            <input type="text" name="telefono" value="<?php echo htmlspecialchars($row_registro['telefono']); ?>" required>
            <br>

            <!-- Usuario (solo lectura) -->
            <label>Usuario:</label>
            <input style="background: #e0e0e0;" type="text" name="username" value="<?php echo $row_registro['username']; ?>" readonly>
            <br>

            <!-- Contraseña (solo lectura) -->
            <label>Contraseña:</label>
            <input style="background: #e0e0e0;" type="text" name="password" value="<?php echo $row_registro['password']; ?>" readonly>
            <br>

            <!-- Email (editable) -->
            <label>Email:</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($row_registro['email']); ?>" required>
            <br>

            <!-- Fecha Alta (solo lectura) -->
            <label>Fecha Alta:</label>
            <input style="background: #e0e0e0;" type="date" name="fecha_alta" value="<?php echo $row_registro['fecha_alta']; ?>" readonly>
            <br>

            <!-- Fecha Modificación (solo lectura) -->
            <label>Fecha Modi:</label>
            <input style="background: #e0e0e0;" type="date" name="fecha_modi" value="<?php echo $row_registro['fecha_modi']; ?>" readonly>
            <br>

            <!-- Botones -->
            <input type="submit" name="Guardar" value="Guardar">
            <input type="submit" value="Cancelar" form="volver">
        </form>
    </div>

    <form action="empleados_panel.php" id="volver" method="post"></form>
    
</body>
</html>