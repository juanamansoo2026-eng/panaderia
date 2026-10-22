<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/panaderia-moderna.css">
    <title>Alta de Proveedores - Panadería M</title>
</head>
<body>
    <?php include("menu.php"); ?>

    <div class="container" style="max-width: 600px;">
        <h2 style="text-align: center;">Alta de Proveedores - Panadería M</h2>
        <form action="alta_proveedores.php" method="post" autocomplete="off">
            <label>Nombre:</label>
            <input type="text" name="nombre" maxlength="20" required placeholder="Ej: Carlos">

            <label>Apellido:</label>
            <input type="text" name="apellido" maxlength="20" required placeholder="Ej: Gómez">

            <label>Teléfono:</label>
            <input type="text" name="telefono" maxlength="20" required placeholder="Ej: 3875998877">

            <label>¿Qué Vende?:</label>
            <input type="text" name="descripcion" maxlength="50" required placeholder="Ej: Harina y Levadura">

            <div style="text-align: center; margin-top: 20px;">
                <button type="submit" name="enviar"><svg class="ui-icon" aria-hidden="true" viewBox="0 0 24 24"><path d="M5 3h12l4 4v14H3V3zM7 3v6h10V3M7 21v-8h10v8"/></svg>Grabar</button>
                <a class="button button-outline" href="proveedores_panel.php">❌ Cancelar</a>
            </div>
        </form>
    </div>

    <?php include("footer.php"); ?>
</body>
</html>