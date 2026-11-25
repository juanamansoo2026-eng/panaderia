<?php
require_once __DIR__ . '/auth.php';
?>
<?php
    require_once("conexion.php");
    require_once("caja_funciones.php");

    $fecha_actual = date('Y-m-d H:i');

    // EMPLEADO QUE INICIÓ SESIÓN (la caja la abre siempre el usuario logueado)
    $empleado = empleadoSesion($conex);

    // ¿YA HAY UNA CAJA ABIERTA? (no se pueden abrir dos cajas a la vez)
    $caja_abierta = cajaAbierta($conex);

    mysqli_close($conex);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Abrir Caja - Panadería M</title>
    <link rel="stylesheet" href="css/panaderia-moderna.css">
</head>
<body>
    <?php include("menu.php"); ?>
    <div class="container form-container" style="width: 600px">
    <h2 style="text-align: center">Abrir Caja - Panadería M</h2>

    <?php if ($caja_abierta) { ?>

        <!-- YA HAY UNA CAJA ABIERTA -->
        <div class="mensaje advertencia" role="alert">
            Ya hay una caja abierta (N° <?php echo $caja_abierta['id_turno']; ?>)
            de <b><?php echo htmlspecialchars($caja_abierta['nombre'] . ' ' . $caja_abierta['apellido']); ?></b>
            desde el <?php echo mostrarFecha($caja_abierta['fecha_apertura']); ?>.
            Para abrir otra, primero hay que cerrarla.
        </div>
        <div class="resultado-acciones">
            <form action="turno_caja_cierre.php" method="post" style="display:inline;">
                <input type="hidden" name="id_turno" value="<?php echo $caja_abierta['id_turno']; ?>">
                <button type="submit" class="button">Cerrar esa caja</button>
            </form>
            <a class="button button-outline" href="turno_caja_panel.php">Volver</a>
        </div>

    <?php } elseif (!$empleado) { ?>

        <div class="mensaje error" role="alert">No se encontró el empleado del usuario que inició sesión.</div>
        <div class="resultado-acciones">
            <a class="button button-outline" href="turno_caja_panel.php">Volver</a>
        </div>

    <?php } else { ?>

    <form action="alta_turno_caja.php" method="post" autocomplete="off">
        <label>Empleado:</label>
        <div style="padding:11px 16px; background:#F0E6D8; border-radius:8px; font-weight:600; margin-bottom: 12px;">
            <?php echo htmlspecialchars($empleado['nombre'] . ' ' . $empleado['apellido'] . ' (' . $_SESSION['username'] . ')'); ?>
        </div>

        <label>Fecha y hora de apertura:</label>
        <input type="text" value="<?php echo $fecha_actual; ?>" readonly style="background:#F0E6D8;">
        <br>

        <label>Monto inicial en caja ($):</label>
        <input type="number" step="0.01" min="0" name="monto_inicial" required
               placeholder="Efectivo con el que arranca la caja">
        <br>

        <label>Observaciones:</label>
        <input type="text" name="observaciones" maxlength="255" placeholder="Opcional">
        <br>

        <input type="submit" name="enviar" value="Abrir caja">
        <input type="submit" value="Cancelar" form="volver">
    </form>
    <form action="turno_caja_panel.php" id="volver" method="post"></form>

    <?php } ?>

    </div>
    <?php include("footer.php"); ?>
</body>
</html>
