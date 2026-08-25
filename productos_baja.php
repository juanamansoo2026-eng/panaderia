<?php
$apeynom= strtoupper($_REQUEST["nombre_prod"]);
$id_producto= $_REQUEST["id_producto"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,300italic,700,700italic">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/milligram/1.4.1/milligram.css">
    <title>Eliminar Productos - Panadería M</title>
</head>
<body>
    <div class="container" style= "text-align: center">
    <h2>Eliminar Productos - Panadería M</h2>
    <h3>Esta por eliminar el Producto:</h3>
    <form action="baja_productos.php">
        <input type="hidden" name="id_producto" value="<?php echo $id_producto;?>">
        <h3><b><?php echo $apeynom; ?></b></h3>
        <h3>¿Esta seguro que desea continuar?</h3>
        <input type="submit" name="eliminar" value="Si">
        <input type="submit" value="No" form="volver">
    </form>
    <form action="productos_panel.php" id="volver">
    </form>
</body>
</html>