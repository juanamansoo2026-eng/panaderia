<?php
if (isset($_REQUEST['id_categoria_gasto'])) {
    $id_categoria_gasto = $_REQUEST['id_categoria_gasto'];
    require_once("conexion.php"); //conexion a la bd
    $registro= mysqli_query($conex, "SELECT * FROM categoria_gasto 
    WHERE id_categoria_gasto = $id_categoria_gasto")
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
    <title>Modificar las Categorias de Gastos - Panadería M</title>
    <link rel="stylesheet" href="css/panaderia-moderna.css">
</head>
<body>
    <?php include("menu.php"); ?>
    <div class="container form-container" style= "width: 500px">
    <h2>Modificar las Categoria de Gastos- Panadería M</h2>
    <form action="modi_categoria_gasto.php" method="post" autocomplete="off"> <!--autocomplete on/off es para que no se queden guardados datos-->
        <label>ID Categoria:</label>
        <input style="background:silver;" type="text" name="id_categoria_gasto" value="<?php echo $row_registro['id_categoria_gasto'];?>" readonly>
        <br>
        <label>Nombre de la Categoria:</label>
        <input type="text" name="nombre_categoria" maxlength="50" value="<?php echo $row_registro['nombre_categoria']; ?>" required><!-- required es para que el campo si o si este completado, sino no avanza-->
        <br>
   
        <input type="submit" name="enviar" value="Grabar">
        <input type="submit"  value="Cancelar" form="volver">
    </form>
    </div>
    <form action="categoria_gasto_panel.php" id="volver" method="post"></form>        
    <?php include("footer.php"); ?>
</body>
</html>