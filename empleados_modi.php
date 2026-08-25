<?php
// Asegúrate de que la variable 'id_empleado' esté definida en la solicitud
if (isset($_REQUEST['id_empleado'])) {
    $id_empleado = $_REQUEST['id_empleado'];
    require_once("conexion.php");

    // Realizar la consulta para obtener los datos del empleado
    $registro = mysqli_query($conex, "SELECT * FROM empleados WHERE id_empleado = $id_empleado")
        or die("Problema en la consulta: " . mysqli_error($conex));

    // Verificar si se encontró el registro
    if ($registro) {
        $row_registro = mysqli_fetch_assoc($registro); // Obtener los datos del empleado
    } else {
        echo 'Error en la conexión o no se encontró el registro';
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

            <!-- Nombre (solo lectura) -->
            <label>Nombre:</label>
            <input style="background: #e0e0e0;" type="text" name="nombre" value="<?php echo $row_registro['nombre']; ?>" readonly>
            <br>

            <!-- Apellido (solo lectura) -->
            <label>Apellido:</label>
            <input style="background: #e0e0e0;" type="text" name="apellido" value="<?php echo $row_registro['apellido']; ?>" readonly>
            <br>

            <!-- DNI (solo lectura) -->
            <label>DNI:</label>
            <input style="background: #e0e0e0;" type="text" name="dni" value="<?php echo $row_registro['dni']; ?>" readonly>
            <br>

            <!-- Rol (solo lectura) -->
            <label>Rol:</label>
            <input style="background: #e0e0e0;" type="text" name="rol" value="<?php echo $row_registro['rol']; ?>" readonly>
            <br>

            <!-- Telefono (solo lectura) -->
            <label>Telefono:</label>
            <input style="background: #e0e0e0;" type="text" name="telefono" value="<?php echo $row_registro['telefono']; ?>" readonly>
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
            <input type="email" name="email" value="<?php echo $row_registro['email']; ?>" required>
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