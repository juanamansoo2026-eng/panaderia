<?php
if (isset($_REQUEST['id_materia_prima'])) {
    $id_materia_prima = $_REQUEST['id_materia_prima'];
    require_once("conexion.php"); //conexion a la bd
    $registro= mysqli_query($conex, "SELECT id_materia_prima, nombre_mp, marca_id, nombre_marca, proveedor_id, proveedor.apellido, precio
    FROM materia_prima
    INNER JOIN proveedor ON proveedor.id_proveedor = materia_prima.proveedor_id
    INNER JOIN marca_mp ON marca_mp.id_marca = materia_prima.marca_id
    WHERE id_materia_prima = $id_materia_prima")
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
    <title>Modificar Materia Prima - Panadería M</title>
</head>
<body>
    <div class="container" style= "width: 500px">
    <h2>Modificar Materia Prima - Panadería M</h2>
    <form action="modi_materia_prima.php" method="post" autocomplete="off">
        <label>ID Materia Prima:</label>
        <input style="background:silver;" type="text" name="id_materia_prima" value="<?php echo $row_registro['id_materia_prima'];?>" readonly>
        <br>

        <label>Nombre de la Materia Prima:</label>
        <input type="text" name="nombre_mp" maxlength="50" value="<?php echo $row_registro['nombre_mp']; ?>" required>
        <br>

        <label>Marca:</label>
        <select name="marca" required>
                <?php
                    include_once ('conexion.php'); 
                    $resultado = mysqli_query($conex,"SELECT id_marca, nombre_marca FROM marca_mp");
                    while ($row= mysqli_fetch_assoc($resultado)) { ?> 
                        <option value="<?php echo $row['id_marca'];?>" <?php if($row['id_marca'] == $row_registro['marca_id']){ echo 'selected';} ?>> <?php echo $row['nombre_marca'];?> </option> 
                    <?php
                    }
                ?>
            </select>
        <br>

        <label>Precio:</label>
        <input type="number" step="0.01" name="precio" value="<?php echo $row_registro['precio']; ?>" required>
        <br>

        <label>Apellido del Proveedor:</label>
            <select name="apellido" required>
                <?php
                    include_once ('conexion.php'); 
                    $resultado = mysqli_query($conex,"SELECT id_proveedor, apellido FROM proveedor GROUP BY apellido");
                    while ($row= mysqli_fetch_assoc($resultado)) { ?> 
                        <option value="<?php echo $row['id_proveedor'];?>" <?php if($row['id_proveedor'] == $row_registro['proveedor_id']){ echo 'selected';} ?>> <?php echo $row['apellido'];?> </option> 
                    <?php
                    }
                    mysqli_close($conex);
                ?>
            </select>
        <br>
        <input type="submit" name="enviar" value="Grabar">
        <input type="submit"  value="Cancelar" form="volver">
    </form>
    </div>
    <form action="materia_prima_panel.php" id="volver" method="post"></form>        
</body>
</html>