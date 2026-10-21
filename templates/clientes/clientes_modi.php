<?php
if (isset($_REQUEST['id_cliente'])) {
    $id_cliente = $_REQUEST['id_cliente'];
    require_once("conexion.php"); //conexion a la bd
    $registro= mysqli_query($conex, "SELECT * FROM clientes 
    WHERE id_cliente = $id_cliente")
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
    <title>Modificar Clientes - Panadería M</title>
</head>
<body>
    <div class="container" style= "width: 500px">
    <h2>Modificar Clientes - Panadería M</h2>
    <form action="modi_clientes.php" method="post" autocomplete="off">
        <label>ID Proveedor:</label>
        <input style="background:silver;" type="text" name="id_cliente" value="<?php echo $row_registro['id_cliente'];?>" readonly>
        <br>
        <label>Nombre:</label>
        <input type="text" name="nombre" maxlength="20" value="<?php echo $row_registro['nombre']; ?>" required>
        <br>
        <label>Apellido:</label>
        <input type="text" name="apellido" maxlength="20" value="<?php echo $row_registro['apellido']; ?>" required>
        <br>
        <label>Dirección:</label>
        <input type="text" name="direccion" maxlength="50" value="<?php echo $row_registro['direccion']; ?>" required>
        <br>
        <label>Telefono:</label>
        <input type="int" name="telefono" maxlength="20" value="<?php echo $row_registro['telefono']; ?>" required>
        <br>
        <label>Descripcion:</label>
        <input  type="text" name="descripcion" maxlength="150" value="<?php echo $row_registro['descripcion']; ?>" required>
        <br>
        <input type="submit" name="enviar" value="Grabar">
        <input type="submit"  value="Cancelar" form="volver">
    </form>
    </div>
    <form action="clientes_panel.php" id="volver" method="post"></form>        
</body>
</html>