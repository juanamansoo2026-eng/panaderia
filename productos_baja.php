<?php
$apeynom= strtoupper($_REQUEST["nombre_prod"]);
$id_producto= $_REQUEST["id_producto"];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/panaderia-moderna.css">
    <title>Eliminar Producto - Panadería M</title>
</head>
<body>
    <?php include("menu.php"); ?>

    <div class="container" style="max-width: 500px; text-align: center;">
        <h2 style="color: var(--bakery-danger);">Eliminar Producto</h2>
        <p style="margin: 15px 0; font-size: 1.05rem;">Está por eliminar el producto:</p>
        <h3 style="color: var(--bakery-dark); font-size: 1.3rem; margin-bottom: 20px;"><?php echo htmlspecialchars($apeynom); ?></h3>
        <p style="color: var(--bakery-text-muted); font-size: 0.95rem;">¿Está seguro de que desea continuar?</p>

        <form action="baja_productos.php" method="get" style="margin-top: 25px;">
            <input type="hidden" name="id_producto" value="<?php echo htmlspecialchars($id_producto);?>">
            <input type="submit" name="eliminar" value="⚠️ Sí, Eliminar" class="button button-danger">
            <a class="button button-outline" href="productos_panel.php">❌ Cancelar</a>
        </form>
    </div>

    <?php include("footer.php"); ?>
</body>
</html>