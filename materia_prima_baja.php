<?php
$nombre= strtoupper($_REQUEST["nombre_mp"]);
$id_materia_prima= $_REQUEST["id_materia_prima"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Materia Prima - Panadería M</title>
    <link rel="stylesheet" href="css/panaderia-moderna.css">
</head>
<body>
    <?php include("menu.php"); ?>
    <div class="container form-container" style= "text-align: center">
    <h2>Eliminar Materia Prima - Panadería M</h2>
    <h3>Esta por eliminar la Materia Prima:</h3>
    <form action="baja_materia_prima.php">
        <input type="hidden" name="id_materia_prima" value="<?php echo $id_materia_prima;?>">
        <h3><b><?php echo $nombre; ?></b></h3>
        <h3>¿Esta seguro que desea continuar?</h3>
        <input type="submit" name="eliminar" value="Si">
        <input type="submit" value="No" form="volver">
    </form>
    <form action="materia_prima_panel.php" id="volver">
    </form>
        </div>
    <?php include("footer.php"); ?>
</body>
</html>