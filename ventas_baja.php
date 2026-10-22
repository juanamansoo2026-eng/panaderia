<?php
$info_venta= strtoupper($_REQUEST["info_venta"]);
$info_total= strtoupper($_REQUEST["info_total"]);
$info_fecha= strtoupper($_REQUEST["info_fecha"]);
$id_venta= $_REQUEST["id_venta"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Venta - Panadería M</title>
    <link rel="stylesheet" href="css/panaderia-moderna.css">
</head>
<body>
    <?php include("menu.php"); ?>
    <div class="container form-container" style= "text-align: center">
    <h2>Eliminar Venta - Panadería M</h2>
    <h3>Esta por eliminar la Venta... :</h3>
    <form action="baja_ventas.php">
        <input type="hidden" name="id_venta" value="<?php echo $id_venta;?>">
        <h3><b><?php echo "Del Cliente: " . $info_venta; ?></b></h3> 
        <h3><b><?php echo "Precio Total: $" . $info_total; ?></b></h3> 
        <h3><b><?php echo "Fecha: " . $info_fecha; ?></b></h3>
        <h3>¿Esta seguro que desea continuar?</h3>
        <input type="submit" name="eliminar" value="Si">
        <input type="submit" value="No" form="volver">
    </form>
    <form action="ventas_panel.php" id="volver">
    </form>
        </div>
    <?php include("footer.php"); ?>
</body>
</html>