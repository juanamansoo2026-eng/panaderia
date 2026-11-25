<?php
require_once __DIR__ . '/auth.php';
?>
<?php
// YA NO SE USA: las compras se pagan siempre con la caja abierta y no se pueden sacar de una caja.
require_once("caja_funciones.php");
paginaAviso('No se puede sacar la compra', 'Las compras quedan en la caja con la que se pagaron. Si la caja está abierta podés modificar o eliminar la compra desde Pedidos de Compra.', 'turno_caja_panel.php', 'Ir a Caja');
// SACA UNA COMPRA DE LA CAJA: queda como pagada fuera de caja (turno_id vacío)
$id_pedido_compra = isset($_REQUEST['id_pedido_compra']) ? (int)$_REQUEST['id_pedido_compra'] : 0;
$id_turno = isset($_REQUEST['id_turno']) ? (int)$_REQUEST['id_turno'] : 0;
$mensaje = '';

require_once("conexion.php");

try {
    $consulta = mysqli_prepare($conex, "UPDATE pedido_compra SET turno_id = NULL WHERE id_pedido_compra = ? AND turno_id = ?");
    mysqli_stmt_bind_param($consulta, 'ii', $id_pedido_compra, $id_turno);
    mysqli_stmt_execute($consulta);
    if (mysqli_stmt_affected_rows($consulta) != 1) {
        $mensaje = 'No se encontró esa compra en la caja.';
    }
} catch (mysqli_sql_exception $e) {
    // pasa si la columna turno_id todavía no acepta vacío
    $mensaje = 'La base todavía no permite compras fuera de caja. Corré el archivo sql/caja_compras.sql en phpMyAdmin. (' . $e->getMessage() . ')';
}
mysqli_close($conex);

if ($mensaje == '') {
    // volver al detalle de la caja
    ?>
    <form id="volver" action="turno_caja_cierre.php" method="post">
        <input type="hidden" name="id_turno" value="<?php echo $id_turno; ?>">
    </form>
    <script>document.getElementById('volver').submit();</script>
    <?php
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Caja - Panadería M</title>
    <link rel="stylesheet" href="css/panaderia-moderna.css">
</head>
<body>
    <?php include("menu.php"); ?>
    <main class="container form-container resultado-container">
        <h2>No se pudo sacar la compra de la caja</h2>
        <div class="mensaje error" role="alert"><?php echo htmlspecialchars($mensaje); ?></div>
        <div class="resultado-acciones">
            <a class="button button-outline" href="turno_caja_panel.php">Volver</a>
        </div>
    </main>
    <?php include("footer.php"); ?>
</body>
</html>
