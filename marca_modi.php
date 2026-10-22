<?php
if (isset($_REQUEST['id_marca'])) {
    $id_marca = $_REQUEST['id_marca'];
    require_once("conexion.php"); //conexion a la bd
    $registro= mysqli_query($conex, "SELECT * FROM marca_mp 
    WHERE id_marca = $id_marca")
    or die ("problema en la consulta".mysqli_error($conex)); //mysqli_error me muestra un mensaje con el problema y su linea, "or die" es si sale bien no pasa nada, "O die" o te sale el mensaje error
    if ($registro) {
        $row_registro= mysqli_fetch_assoc($registro); //carga cada columna/campo como id, nombre, etc, lo guardad aca
    } else {
        echo 'error en la conexion';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar las Marca de Materia Prima - Panadería M</title>
    <link rel="stylesheet" href="css/panaderia-moderna.css">
</head>
<body>
    <?php include("menu.php"); ?>
    <div class="container form-container" style= "width: 500px">
    <h2>Modificar las Marca de Materia Prima - Panadería M</h2>
    <form action="modi_marca.php" method="post" autocomplete="off"> <!--autocomplete on/off es para que no se queden guardados datos-->
        <label>ID Marca:</label>
        <input style="background:silver;" type="text" name="id_marca" value="<?php echo $row_registro['id_marca'];?>" readonly>
        <br>
        <label>Nombre de la Marca:</label>
        <input type="text" name="nombre_marca" maxlength="30" value="<?php echo $row_registro['nombre_marca']; ?>" required><!-- required es para que el campo si o si este completado, sino no avanza-->
        <br>
   
        <input type="submit" name="enviar" value="Grabar">
        <input type="submit"  value="Cancelar" form="volver">
    </form>
    </div>
    <form action="marca_panel.php" id="volver" method="post"></form>        
    <?php include("footer.php"); ?>
</body>
</html>