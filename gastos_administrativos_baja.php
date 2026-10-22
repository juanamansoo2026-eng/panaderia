<?php
$nombre_gasto= strtoupper($_REQUEST["nombre_gasto"]);
$precio_gasto= strtoupper($_REQUEST["precio_gasto"]);
$id_gasto= $_REQUEST["id_gasto"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Gastos Administrativos - Panadería M</title>
    <link rel="stylesheet" href="css/panaderia-moderna.css">
</head>
<body>
    <?php include("menu.php"); ?>
    <div class="container form-container" style= "text-align: center">
    <h2>Eliminar Gastos Administrativos - Panadería M</h2>
    <h3>Esta por eliminar el Gasto de:</h3>
    <form action="baja_gastos_administrativos.php">
        <input type="hidden" name="id_gasto" value="<?php echo $id_gasto;?>">
        <h3><b><?php echo $nombre_gasto . " con el precio de $ " . $precio_gasto; ?></b></h3>
        <h3>¿Esta seguro que desea continuar?</h3>
        <input type="submit" name="eliminar" value="Si">
        <input type="submit" value="No" form="volver">
    </form>
    <form action="gastos_administrativos_panel.php" id="volver">
    </form>
        </div>
    <?php include("footer.php"); ?>
</body>
</html>