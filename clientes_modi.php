<?php
if (isset($_REQUEST['id_cliente'])) {
    $id_cliente = $_REQUEST['id_cliente'];
    require_once("conexion.php");
    $registro= mysqli_query($conex, "SELECT * FROM clientes WHERE id_cliente = $id_cliente")
    or die ("problema en la consulta".mysqli_error($conex));
    if ($registro) {
        $row_registro= mysqli_fetch_assoc($registro);
    } else {
        echo 'error en la conexion';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/panaderia-moderna.css">
    <title>Modificar Cliente - Panadería M</title>
</head>
<body>
    <?php include("menu.php"); ?>

    <div class="container" style="max-width: 600px;">
        <h2 style="text-align: center;">Modificar Cliente - Panadería M</h2>
        <form action="modi_clientes.php" method="post" autocomplete="off">
            <label>ID Cliente:</label>
            <input style="background:#F0E6D8;" type="text" name="id_cliente" value="<?php echo htmlspecialchars($row_registro['id_cliente']);?>" readonly>

            <label>Nombre:</label>
            <input type="text" name="nombre" maxlength="20" value="<?php echo htmlspecialchars($row_registro['nombre']); ?>" required>

            <label>Apellido:</label>
            <input type="text" name="apellido" maxlength="20" value="<?php echo htmlspecialchars($row_registro['apellido']); ?>" required>

            <label>Dirección:</label>
            <input type="text" name="direccion" maxlength="50" value="<?php echo htmlspecialchars($row_registro['direccion']); ?>" required>

            <label>Teléfono:</label>
            <input type="text" name="telefono" maxlength="20" value="<?php echo htmlspecialchars($row_registro['telefono']); ?>" required>

            <label>Descripción:</label>
            <input type="text" name="descripcion" maxlength="150" value="<?php echo htmlspecialchars($row_registro['descripcion']); ?>" required>

            <div style="text-align: center; margin-top: 20px;">
                <button type="submit" name="enviar"><svg class="ui-icon" aria-hidden="true" viewBox="0 0 24 24"><path d="M5 3h12l4 4v14H3V3zM7 3v6h10V3M7 21v-8h10v8"/></svg>Guardar Cambios</button>
                <a class="button button-outline" href="clientes_panel.php">❌ Cancelar</a>
            </div>
        </form>
    </div>

    <?php include("footer.php"); ?>
</body>
</html>