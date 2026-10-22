<?php
$nombre= strtoupper($_REQUEST["nombre_inventario"]);
$id_inventario= $_REQUEST["id_inventario"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Inventario - Panadería M</title>
    <link rel="stylesheet" href="css/panaderia-moderna.css">
</head>
<body>
    <?php include("menu.php"); ?>
    <div class="container form-container" style= "text-align: center">
    <h2>Eliminar Inventario - Panadería M</h2>
    <h3>Esta por eliminar el Inventario de:</h3>
    <form action="baja_inventario.php">
        <input type="hidden" name="id_inventario" value="<?php echo $id_inventario;?>">
        <h3><b><?php echo $nombre; ?></b></h3>
        <h3>¿Esta seguro que desea continuar?</h3>
        <input type="submit" name="eliminar" value="Si">
        <input type="submit" value="No" form="volver">
    </form>
    <form action="inventario_panel.php" id="volver">
    </form>
        </div>
    <?php include("footer.php"); ?>
</body>
</html>