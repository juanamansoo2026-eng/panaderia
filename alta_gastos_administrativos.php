<?php
require_once __DIR__ . '/auth.php';
?>
<?php
if (isset($_REQUEST['enviar'])) { 
    $descripcion = $_REQUEST['descripcion'];
    $categoria_gasto_id = $_REQUEST['categoria_gasto_id'];
    $monto = $_REQUEST['monto'];
    $turno_id = $_REQUEST['turno_id'];
    $empleado = $_REQUEST['empleado'];
    $fecha = $_REQUEST['fecha'];

    require_once("conexion.php"); 
    mysqli_query($conex,"SET NAMES 'UTF8'");

    // EL GASTO SE PAGA CON LA CAJA ABIERTA
    require_once("caja_funciones.php");
    $caja = cajaAbierta($conex);
    if (!$caja) {
        mysqli_close($conex);
        paginaAviso('No se guardó el gasto', 'No hay ninguna caja abierta. Abrila para poder registrar gastos.', 'turno_caja_alta.php', 'Abrir Caja');
    }
    $turno_id = (int)$caja['id_turno']; // siempre la caja abierta, aunque el formulario mande otra
    $disponible = totalesCaja($conex, $caja)['esperado'];

    // NO SE PUEDE PAGAR CON LA CAJA MÁS EFECTIVO DEL QUE HAY
    if ((float)$monto <= 0) {
        mysqli_close($conex);
        paginaAviso('No se guardó el gasto', 'El monto tiene que ser mayor a cero.', 'gastos_administrativos_alta.php', 'Volver al formulario');
    }
    if (round((float)$monto, 2) > round($disponible, 2)) {
        mysqli_close($conex);
        paginaAviso('No se guardó el gasto',
            'El gasto es de <b>' . pesos($monto) . '</b> y en la caja N° ' . $turno_id . ' hay <b>' . pesos($disponible) . '</b> en efectivo.',
            'gastos_administrativos_alta.php', 'Volver al formulario');
    }

    // Guardar la información del turno en la base de datos
    $resultado_alta = mysqli_query($conex, "INSERT INTO gastos_administrativos (turno_id, categoria_gasto_id, descripcion, monto, empleado, fecha) 
    VALUE ('$turno_id','$categoria_gasto_id','$descripcion','$monto','$empleado','$fecha')") 
    or die ("problema en la consulta: " . mysqli_error($conex));

    if ($resultado_alta) {
        $gastos_administrivos_grabado = mysqli_insert_id($conex);

        $query_nombre_categoria = "SELECT nombre_categoria FROM categoria_gasto WHERE id_categoria_gasto = '$categoria_gasto_id'";
        $row_nombre_categoria = mysqli_fetch_assoc(mysqli_query($conex, $query_nombre_categoria));    
        $nombre_categoria = $row_nombre_categoria['nombre_categoria'];

        $query_nombre_turno = "SELECT turno FROM turno_caja WHERE id_turno = '$turno_id'";
        $row_nombre_turno = mysqli_fetch_assoc(mysqli_query($conex, $query_nombre_turno));    
        $nombre_turno = $row_nombre_turno['turno'];

        // MOSTRAR LOS DETALLES DEL GASTO REGISTRADO
        ?>
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Gasto guardado - Panadería M</title>
            <link rel="stylesheet" href="css/panaderia-moderna.css">
        </head>
        <body>
            <?php include("menu.php"); ?>
            <main class="container form-container resultado-container">
                <h2>Gasto registrado</h2>
                <div class="mensaje exito" role="status">
                    <svg class="ui-icon" aria-hidden="true" viewBox="0 0 24 24"><path d="m5 12 4 4L19 6"/></svg>
                    El gasto se guardó correctamente.
                </div>
                <table>
                    <tbody>
                        <tr><td>Descripción</td><td><b><?php echo htmlspecialchars($descripcion); ?></b></td></tr>
                        <tr><td>Categoría</td><td><?php echo htmlspecialchars($nombre_categoria); ?></td></tr>
                        <tr><td>Monto</td><td><b>$<?php echo number_format((float)$monto, 2, ',', '.'); ?></b></td></tr>
                        <tr><td>Caja</td><td>Caja N° <?php echo (int)$turno_id; ?></td></tr>
                        <tr><td>Empleado</td><td><?php echo htmlspecialchars($empleado); ?></td></tr>
                        <tr><td>Fecha</td><td><?php echo date('d/m/Y', strtotime($fecha)); ?></td></tr>
                    </tbody>
                </table>
                <div class="resultado-acciones">
                    <a class="button" href="gastos_administrativos_panel.php">Continuar</a>
                    <a class="button button-outline" href="gastos_administrativos_alta.php">Cargar otro gasto</a>
                    <a class="button button-outline" href="turno_caja_panel.php">Ir a Caja</a>
                </div>
            </main>
            <?php include("footer.php"); ?>
        </body>
        </html>
        <?php
    } else {
        echo 'Error en el proceso';
    }
    mysqli_close($conex);
}
?>