<?php
if (isset($_REQUEST['id_producto'])) {
    $id_producto = $_REQUEST['id_producto'];
    require_once("conexion.php");
    $registro= mysqli_query($conex, "SELECT * FROM productos WHERE id_producto = $id_producto")
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
    <title>Modificar Producto - Panadería M</title>
</head>
<body>
    <?php include("menu.php"); ?>

    <div class="container" style="max-width: 600px;">
        <h2 style="text-align: center;">Modificar Producto - Panadería M</h2>
        <form action="modi_productos.php" method="post" autocomplete="off">
            <label>ID Producto:</label>
            <input style="background:#F0E6D8;" type="text" name="id_producto" value="<?php echo htmlspecialchars($row_registro['id_producto']);?>" readonly>

            <label>Nombre del Producto:</label>
            <input type="text" name="nombre" maxlength="30" value="<?php echo htmlspecialchars($row_registro['nombre']); ?>" required>

            <label>Tipo de Producto:</label>
            <select name="tipo" required>
                <option value="Pan" <?php if($row_registro['tipo'] == 'Pan') echo 'selected'; ?>>Pan</option>
                <option value="Tortillas" <?php if($row_registro['tipo'] == 'Tortillas') echo 'selected'; ?>>Tortillas</option>
                <option value="Facturas" <?php if($row_registro['tipo'] == 'Facturas') echo 'selected'; ?>>Facturas</option>
                <option value="Especialidades Salados" <?php if($row_registro['tipo'] == 'Especialidades Salados') echo 'selected'; ?>>Especialidades Salados</option>
                <option value="Especialidades Dulces" <?php if($row_registro['tipo'] == 'Especialidades Dulces') echo 'selected'; ?>>Especialidades Dulces</option>
            </select>

            <label>Precio ($):</label>
            <input type="number" step="0.01" name="precio" value="<?php echo htmlspecialchars($row_registro['precio']); ?>" required>

            <div style="text-align: center; margin-top: 20px;">
                <button type="submit" name="enviar"><svg class="ui-icon" aria-hidden="true" viewBox="0 0 24 24"><path d="M5 3h12l4 4v14H3V3zM7 3v6h10V3M7 21v-8h10v8"/></svg>Guardar Cambios</button>
                <a class="button button-outline" href="productos_panel.php">❌ Cancelar</a>
            </div>
        </form>
    </div>

    <?php include("footer.php"); ?>
</body>
</html>