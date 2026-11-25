<?php
require_once __DIR__ . '/auth.php';
?>
<?php
    include_once ('conexion.php');
    require_once ('caja_funciones.php');
    // CAJA ABIERTA (los gastos se pagan con el efectivo de esa caja)
    $caja_abierta = cajaAbierta($conex);
    if ($caja_abierta) {
        $caja_abierta['disponible'] = totalesCaja($conex, $caja_abierta)['esperado'];
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alta Gastos Administrativos - Panadería M</title>
    <link rel="stylesheet" href="css/panaderia-moderna.css">
</head>
<body>
    <?php include("menu.php"); ?>
    <div class="container form-container" style="width: 600px">
        <h2 style="text-align: center">Alta Gastos Administrativos - Panadería M</h2>

        <?php if (!$caja_abierta) { ?>
            <!-- SIN CAJA ABIERTA NO SE PUEDEN REGISTRAR GASTOS -->
            <div class="mensaje advertencia" role="alert">
                No hay ninguna caja abierta. Para registrar gastos primero tenés que abrirla.
            </div>
            <div class="resultado-acciones">
                <a class="button" href="turno_caja_alta.php">Abrir Caja</a>
                <a class="button button-outline" href="gastos_administrativos_panel.php">Volver</a>
            </div>
    </div>
    <?php include("footer.php"); ?>
</body>
</html>
        <?php exit(); } ?>
        <form action="alta_gastos_administrativos.php" method="post" autocomplete="off" required>
            
            <label>Descripcion:</label>
            <input type="text" name="descripcion" maxlength="100" required>
            <br>

            <label>Categoría:</label>
            <select name="categoria_gasto_id" required>
                <?php
                    include_once ('conexion.php'); 
                    $resultado = mysqli_query($conex,"SELECT id_categoria_gasto, nombre_categoria FROM categoria_gasto GROUP BY nombre_categoria"); 
                ?>
                <option value="">Seleccione..</option>
                <?php
                    while ($row = mysqli_fetch_assoc($resultado)) { ?> 
                        <option value="<?php echo $row['id_categoria_gasto'];?>"> <?php echo $row['nombre_categoria'];?></option> 
                <?php } ?>
            </select>
            <br>

            <label>Monto:</label>
            <input type="number" step="0.01" min="0.01" name="monto" required>
            <br>

            <label>Caja:</label>
            <!-- EL GASTO SE PAGA CON LA CAJA ABIERTA -->
            <input type="hidden" name="turno_id" value="<?php echo $caja_abierta['id_turno']; ?>">
            <input type="text" readonly style="background:#F0E6D8;"
                   value="<?php echo 'Caja N° ' . $caja_abierta['id_turno'] . ' - disponible ' . pesos($caja_abierta['disponible']); ?>">
            <br>

            <label>Empleado:</label>
            <input type="text" name="empleado" id="empleado" readonly style="background:#F0E6D8;"
                   value="<?php echo htmlspecialchars($caja_abierta['nombre'] . ' ' . $caja_abierta['apellido']); ?>">
            <br>

            <label>Fecha Pago:</label>
            <input type="date" name="fecha" value="<?php echo date('Y-m-d'); ?>" required>
            <br>

            <div style="text-align: center; margin-top: 20px;">
                <button type="submit" name="enviar"><svg class="ui-icon" aria-hidden="true" viewBox="0 0 24 24"><path d="M5 3h12l4 4v14H3V3zM7 3v6h10V3M7 21v-8h10v8"/></svg>Guardar</button>
                <a class="button button-outline" href="gastos_administrativos_panel.php">Cancelar</a>
            </div>        
        </form>
    </div>
    <?php include("footer.php"); ?>
</body>
</html>