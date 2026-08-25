<?php
if (isset($_REQUEST['id_producto'])) {
    $id_producto = $_REQUEST['id_producto'];
    require_once("conexion.php"); //conexion a la bd
    $registro= mysqli_query($conex, "SELECT * FROM productos 
    WHERE id_producto = $id_producto")
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
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,300italic,700,700italic">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/milligram/1.4.1/milligram.css">
    <title>Modificar Productos - Panadería M</title>
</head>
<body>
    <div class="container" style= "width: 500px">
    <h2>Modificar Productos - Panadería M</h2>
    <form action="modi_productos.php" method="post" autocomplete="off"> <!--autocomplete on/off es para que no se queden guardados datos-->
        <label>ID Producto:</label>
        <input style="background:silver;" type="text" name="id_producto" value="<?php echo $row_registro['id_producto'];?>" readonly>
        <br>
        <label>Nombre del Producto:</label>
        <input type="text" name="nombre" maxlength="30" value="<?php echo $row_registro['nombre']; ?>" required><!-- required es para que el campo si o si este completado, sino no avanza-->
        <br>
        <label>Tipo de Producto:</label>
            <select name="tipo" required>
            <option value="Pan" <?php if($row_registro['tipo'] == 'Pan') echo 'selected'; ?>>Pan</option>                <option value="Tortillas" <?php if($row_registro['tipo'] == 'Tortillas') echo 'selected'; ?>>Tortillas</option>
            <option value="Facturas" <?php if($row_registro['tipo'] == 'Facturas') echo 'selected'; ?>>Facturas</option>
            <option value="Especialidades Salados" <?php if($row_registro['tipo'] == 'Especialidades Salados') echo 'selected'; ?>>Especialidades Salados</option>
            <option value="Especialidades Dulces" <?php if($row_registro['tipo'] == 'Especialidades Dulces') echo 'selected'; ?>>Especialidades Dulces</option>
        </select>
        <br>
        <label>Precio:</label>
        <input type="number" step="0.01" name="precio" value="<?php echo $row_registro['precio']; ?>" required>
        <br>
   
        <input type="submit" name="enviar" value="Grabar">
        <input type="submit"  value="Cancelar" form="volver">
    </form>
    </div>
    <form action="productos_panel.php" id="volver" method="post"></form>        
</body>
</html>