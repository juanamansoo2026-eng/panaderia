<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alta de Materia Prima - Panadería M</title>
    <link rel="stylesheet" href="css/panaderia-moderna.css">
</head>
<body>
    <?php include("menu.php"); ?>
    <div class="container form-container" style="width: 600px">
    <h2 style="text-align: center">Alta de Materia Prima - Panadería M</h2>
    <form action="alta_materia_prima.php" method="post" autocomplete="off" required> <!--autocomplete on/off es para que no se queden guardados datos-->
        <label>Nombre de la Materia Prima:</label>
        <input type="text" name="nombre_mp" maxlength="50" required><!-- required es para que el campo si o si este completado, sino no avanza-->
        <br>

        <label>Marca:</label>
        <select name="marca" required>
                <?php
                    include_once ('conexion.php'); 
                    $resultado = mysqli_query($conex,"SELECT id_marca, nombre_marca FROM marca_mp"); 
                    ?>
                    <option value="">Seleccione..</option>
                    <?php
                    while ($row= mysqli_fetch_assoc($resultado)) { ?> 
                        <option value="<?php echo $row['id_marca'];?>"> <?php echo $row['nombre_marca'];?></option> 
                    <?php
                    }
                ?>
            </select>
        <br>

        <label>Precio:</label>
        <input type="number" step="0.01" name="precio" required>
        <br>

        <label>Apellido del Proveedor:</label>
            <select name="apellido" required>
                <?php
                    include_once ('conexion.php'); 
                    $resultado = mysqli_query($conex,"SELECT id_proveedor, apellido FROM proveedor"); 
                    ?>
                    <option value="">Seleccione..</option>
                    <?php
                    while ($row= mysqli_fetch_assoc($resultado)) { ?> 
                        <option value="<?php echo $row['id_proveedor'];?>"> <?php echo $row['apellido'];?></option> 
                    <?php
                    }
                    mysqli_close($conex);
                ?>
            </select>
        <br>
        
        <input type="submit" name="enviar" value="Grabar">
        <input type="submit" value="Cancelar" form="volver">
    </form>
    <form action="materia_prima_panel.php" id="volver" method="post"></form>
        </div>
    <?php include("footer.php"); ?>
</body>
</html>