<?php
require_once __DIR__ . '/auth.php';
?>
<?php
include 'conexion.php';
require_once __DIR__ . '/usuarios_funciones.php';

$registroExitoso = false;
$mensajeError = "";
$usuarioCreado = "";
$claveCreada = "";

$fecha_actual = date('Y-m-d');

// Valores para volver a mostrar en el formulario si hay error
$nombre = '';
$apellido = '';
$dni = '';
$rol = '';
$telefono = '';
$email = '';
$activo = '1';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    $dni = trim($_POST['dni'] ?? '');
    $rol = trim($_POST['id_rol'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $activo = (($_POST['activo'] ?? '1') === '0') ? '0' : '1';

    if ($nombre === '' || $apellido === '') {
        $mensajeError = "El nombre y el apellido son obligatorios.";
    } elseif (!ctype_digit($dni)) {
        $mensajeError = "El DNI debe contener solo números.";
    } elseif (!ctype_digit($rol)) {
        $mensajeError = "Seleccione un rol.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $mensajeError = "El correo no es válido.";
    } else {
        // Usuario automático: Apellido + primera letra del primer nombre
        $username = generarUsername($conex, $apellido, $nombre);

        // Clave automática, cifrada con password_hash (se exige cambiarla en el próximo login)
        $clave = generarClaveTemporal();

        $fecha_alta = $fecha_actual;
        $fecha_modi = $fecha_actual;                         // automática del sistema
        $fecha_baja = ($activo === '0') ? $fecha_actual : NULL; // automática si se carga inactivo
        $baja = $activo;                                    // en empleados: baja = 1 es Activo

        $resultado = registrarUsuario(
            $nombre,
            $apellido,
            $dni,
            $rol,
            $telefono,
            $username,
            $clave,
            $email,
            $fecha_alta,
            $fecha_modi,
            $fecha_baja,
            $baja
        );

        if ($resultado === true) {
            $registroExitoso = true;
            $usuarioCreado = $username;
            $claveCreada = $clave;
        } else {
            $mensajeError = $resultado;
        }
    }
}

function registrarUsuario(
    $nombre,
    $apellido,
    $dni,
    $rol,
    $telefono,
    $username,
    $password,
    $email,
    $fecha_alta,
    $fecha_modi,
    $fecha_baja,
    $baja
) {
    global $conex;

    if ($username === '') {
        return "No se pudo generar el usuario con ese apellido y nombre.";
    }

    // Verificar si el DNI ya está cargado
    $verificar = $conex->prepare(
        "SELECT id_empleado FROM empleados WHERE dni = ?"
    );

    $verificar->bind_param("s", $dni);
    $verificar->execute();

    $resultado = $verificar->get_result();

    if ($resultado->num_rows > 0) {
        $verificar->close();

        return "Ya existe un empleado con el DNI $dni.";
    }

    $verificar->close();

    // Encriptar contraseña (clave temporal)
    $hashedPassword = hashClaveTemporal($password);

    // Insertar empleado
    $stmt = $conex->prepare(
        "INSERT INTO empleados
        (nombre, apellido, dni, id_rol, telefono, username, password, email, fecha_alta, fecha_modi, fecha_baja, baja)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
    );

    if (!$stmt) {
        return "Error al preparar el registro: " . $conex->error;
    }

    $stmt->bind_param(
        "ssssssssssss",
        $nombre,
        $apellido,
        $dni,
        $rol,
        $telefono,
        $username,
        $hashedPassword,
        $email,
        $fecha_alta,
        $fecha_modi,
        $fecha_baja,
        $baja
    );

    if ($stmt->execute()) {
        $stmt->close();
        return true;
    }

    $error = $stmt->error;
    $stmt->close();

    return "Error al registrar el usuario: " . $error;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panadería M - Alta de Usuario</title>
    <link rel="stylesheet" href="css/panaderia-moderna.css">
    <style>
        .solo-lectura { background: #e0e0e0; }
        .datos-acceso { background: #fff8e1; border: 1px solid #e0c97f; border-radius: 8px; padding: 15px; margin: 15px 0; }
        .datos-acceso strong { font-size: 1.2rem; letter-spacing: 1px; }
    </style>
</head>
<body>
    <?php include("menu.php"); ?>
    <div class="container form-container" style="width: 500px">
        <?php if ($registroExitoso): ?>

            <h2>Usuario creado - Panadería M</h2>

            <div class="datos-acceso">
                <p>Usuario: <strong><?php echo htmlspecialchars($usuarioCreado); ?></strong></p>
                <p>Clave temporal: <strong><?php echo htmlspecialchars($claveCreada); ?></strong></p>
                <p>Anote o entregue estos datos al empleado. La clave no se vuelve a mostrar y
                   se le pedirá que la cambie la próxima vez que inicie sesión.</p>
            </div>

            <a class="button" href="empleados_panel.php">Volver al Listado de Empleados</a>
            <a class="button button-outline" href="nuevo_emple.php">Cargar otro</a>

        <?php else: ?>

        <?php if (!empty($mensajeError)): ?>

            <p class="mensaje error">
                <?php echo htmlspecialchars($mensajeError); ?>
            </p>

        <?php endif; ?>
            <h2>Alta de Usuario - Panadería M</h2>
            <form action="nuevo_emple.php" method="post" autocomplete="off">
                <label for="dni">DNI:</label>
                <input type="number" id="dni" name="dni" min="1" step="1" value="<?php echo htmlspecialchars($dni); ?>" required>

                <label for="apellido">Apellido:</label>
                <input type="text" id="apellido" name="apellido" value="<?php echo htmlspecialchars($apellido); ?>" oninput="armarUsuario()" required>

                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($nombre); ?>" oninput="armarUsuario()" required>

                <label for="email">Correo:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>

                <label for="id_rol">Rol:</label>
                <select name="id_rol" id="id_rol" required>
                    <option value="">Seleccione un rol</option>

                    <?php
                    $consulta_roles = "SELECT * FROM roles";
                    $resultado_roles = mysqli_query($conex, $consulta_roles);

                    foreach ($resultado_roles as $fila_rol) {
                        $seleccionado = ($rol == $fila_rol['id_rol']) ? 'selected' : '';
                        echo "<option value='" . $fila_rol['id_rol'] . "' $seleccionado>";
                        echo htmlspecialchars($fila_rol['nombre_rol']);
                        echo "</option>";
                    }
                    ?>
                </select>

                <label for="telefono">Teléfono:</label>
                <input type="text" id="telefono" name="telefono" value="<?php echo htmlspecialchars($telefono); ?>" required>

                <label for="username">Usuario (automático):</label>
                <input class="solo-lectura" type="text" id="username" placeholder="Apellido + inicial del nombre" readonly>

                <label>Clave:</label>
                <input class="solo-lectura" type="text" value="Se genera automáticamente (cifrada)" readonly>

                <label for="activo">Activo:</label>
                <select name="activo" id="activo">
                    <option value="1" <?php echo ($activo === '1') ? 'selected' : ''; ?>>Sí</option>
                    <option value="0" <?php echo ($activo === '0') ? 'selected' : ''; ?>>No</option>
                </select>

                <label>Fecha Última Modificación:</label>
                <input class="solo-lectura" type="text" value="Automática del sistema" readonly>

                <label>Fecha Baja:</label>
                <input class="solo-lectura" type="text" value="Automática del sistema" readonly>

                <br>
                <button type="submit" class="button">Dar de Alta</button>
                <a class="button button-outline" href="empleados_panel.php">Cancelar</a>
            </form>
        <?php endif; ?>
    </div>

    <script>
        // Muestra cómo va a quedar el usuario (el definitivo lo arma el servidor, y si ya existe le agrega un número)
        function limpiar(texto) {
            return texto.toLowerCase().normalize('NFD').replace(/[̀-ͯ]/g, '').replace(/[^a-z0-9]/g, '');
        }
        function armarUsuario() {
            var apellido = document.getElementById('apellido');
            var nombre = document.getElementById('nombre');
            if (!apellido || !nombre) return;
            var primerNombre = nombre.value.trim().split(/\s+/)[0] || '';
            document.getElementById('username').value = limpiar(apellido.value) + limpiar(primerNombre).charAt(0);
        }
        armarUsuario();
    </script>
    <?php include("footer.php"); ?>
</body>
</html>
