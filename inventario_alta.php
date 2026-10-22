<?php
$fecha_actual = date('Y-m-d');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/panaderia-moderna.css">
    <title>Alta de Inventario - Panadería M</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function actualizarStock() {
            var ingreso = parseInt(document.getElementsByName('cant_ingreso')[0].value) || 0;
            var salida = parseInt(document.getElementsByName('cant_salida')[0].value) || 0;
            var stockTotal = ingreso - salida;
            document.getElementsByName('stock_total')[0].value = stockTotal;
        }

        window.onload = function() {
            document.getElementsByName('cant_ingreso')[0].addEventListener('input', actualizarStock);
            document.getElementsByName('cant_salida')[0].addEventListener('input', actualizarStock);
        };
    </script>
</head>
<body>
    <?php include("menu.php"); ?>

    <div class="container" style="max-width: 650px;">
        <h2 style="text-align: center;">Alta de Inventario - Panadería M</h2>
        <form action="alta_inventario.php" method="post" autocomplete="off">
            <label>Materia Prima:</label>
            <select name="nombre_mp" required>
                <option value="">Seleccione una materia prima...</option>
                <?php
                    include_once('conexion.php'); 
                    $resultado = mysqli_query($conex, "SELECT id_materia_prima, nombre_mp FROM materia_prima"); 
                    while ($row = mysqli_fetch_assoc($resultado)) {
                ?>
                    <option value="<?php echo $row['id_materia_prima']; ?>"><?php echo htmlspecialchars($row['nombre_mp']); ?></option> 
                <?php } ?>
            </select>

            <label>Marca:</label>
            <select name="nombre_marca" required>
                <option value="">Seleccione una marca...</option>
                <?php
                    $resultado_m = mysqli_query($conex, "SELECT id_marca, nombre_marca FROM marca_mp"); 
                    while ($row = mysqli_fetch_assoc($resultado_m)) {
                ?>
                    <option value="<?php echo $row['id_marca']; ?>"><?php echo htmlspecialchars($row['nombre_marca']); ?></option> 
                <?php
                    }
                    mysqli_close($conex);
                ?>
            </select>

            <label>Cantidad de Ingreso:</label>
            <input type="number" step="1" name="cant_ingreso" value="0" required>

            <label>Cantidad de Salida:</label>
            <input type="number" step="1" name="cant_salida" value="0" required>

            <label>Stock Total (Calculado Auto):</label>
            <input type="number" step="1" name="stock_total" value="0" readonly style="background:#F0E6D8; font-weight:bold; color:var(--bakery-primary);">

            <label>Fecha Modificación:</label>
            <input type="date" name="fecha_modi" value="<?php echo $fecha_actual; ?>" readonly style="background:#F0E6D8;">
            
            <div style="text-align: center; margin-top: 20px;">
                <button type="submit" name="enviar"><svg class="ui-icon" aria-hidden="true" viewBox="0 0 24 24"><path d="M5 3h12l4 4v14H3V3zM7 3v6h10V3M7 21v-8h10v8"/></svg>Grabar</button>
                <a class="button button-outline" href="inventario_panel.php">❌ Cancelar</a>
            </div>
        </form>
    </div>

    <?php include("footer.php"); ?>
</body>
</html>