<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/panaderia-moderna.css">
    <title>Alta de Productos - Panadería M</title>
</head>
<body>
    <?php include("menu.php"); ?>

    <div class="container" style="max-width: 600px;">
        <h2 style="text-align: center;">Alta de Productos - Panadería M</h2>
        <form action="alta_productos.php" method="post" autocomplete="off">
            <label>Nombre del Producto:</label>
            <input type="text" name="nombre" maxlength="30" required placeholder="Ej: Medialuna de Manteca">

            <label>Tipo de Producto:</label>
            <select name="tipo" required>
                <option value="Pan">Pan</option>
                <option value="Tortillas">Tortillas</option>
                <option value="Facturas">Facturas</option>
                <option value="Especialidades Salados">Especialidades Salados</option>
                <option value="Especialidades Dulces">Especialidades Dulces</option>
            </select>

            <label>Precio ($):</label>
            <input type="number" step="0.01" name="precio" required placeholder="Ej: 450.00">

            <div style="text-align: center; margin-top: 20px;">
                <button type="submit" name="enviar"><svg class="ui-icon" aria-hidden="true" viewBox="0 0 24 24"><path d="M5 3h12l4 4v14H3V3zM7 3v6h10V3M7 21v-8h10v8"/></svg>Grabar</button>
                <a class="button button-outline" href="productos_panel.php">❌ Cancelar</a>
            </div>
        </form>
    </div>

    <?php include("footer.php"); ?>
</body>
</html>