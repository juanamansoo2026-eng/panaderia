<?php
$apeynom= strtoupper($_REQUEST["nomyape_prov"]);
$id_proveedor= $_REQUEST["id_proveedor"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,300italic,700,700italic">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/milligram/1.4.1/milligram.css">
    <title>Eliminar Proveedor - Panadería M</title>
</head>
<body>
    <div class="container" style= "text-align: center">
    <h2>Eliminar Proveedor - Panadería M</h2>
    <h3>Esta por eliminar al Proveedor:</h3>
    <form action="baja_proveedores.php">
        <input type="hidden" name="id_proveedor" value="<?php echo $id_proveedor;?>">
        <h3><b><?php echo $apeynom; ?></b></h3>
        <h3>¿Esta seguro que desea continuar?</h3>
        <input type="submit" name="eliminar" value="Si">
        <input type="submit" value="No" form="volver">
    </form>
    <form action="productos_panel.php" id="volver">
    </form>
</body>
</html>