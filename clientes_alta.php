<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/panaderia-moderna.css">
    <title>Alta de Clientes - Panadería M</title>
</head>
<body>
    <?php include("menu.php"); ?>

    <div class="container" style="max-width: 600px;">
        <h2 style="text-align: center;">Alta de Clientes - Panadería M</h2>
        <form action="alta_clientes.php" method="post" autocomplete="off"> 
            <label>Nombre:</label>
            <input type="text" name="nombre" maxlength="20" required placeholder="Ej: Juan">

            <label>Apellido:</label>
            <input type="text" name="apellido" maxlength="20" required placeholder="Ej: Pérez">

            <label>Dirección:</label>
            <input type="text" name="direccion" maxlength="50" required placeholder="Ej: Av. Belgrano 123">

            <label>Teléfono:</label>
            <input type="text" name="telefono" maxlength="20" required placeholder="Ej: 3874123456">

            <label>Descripción:</label>
            <input type="text" name="descripcion" maxlength="150" required placeholder="Ej: Cliente frecuente">

            <div style="text-align: center; margin-top: 20px;">
                <button type="submit" name="enviar"><svg class="ui-icon" aria-hidden="true" viewBox="0 0 24 24"><path d="M5 3h12l4 4v14H3V3zM7 3v6h10V3M7 21v-8h10v8"/></svg>Grabar</button>
                <a class="button button-outline" href="clientes_panel.php">❌ Cancelar</a>
            </div>
        </form>
    </div>

    <?php include("footer.php"); ?>
</body>
</html>