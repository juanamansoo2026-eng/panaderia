<?php

    $fecha_actual = date('Y-m-d');
    require_once("conexion.php");
    $registros= mysqli_query($conex,"SELECT id_producto, nombre, tipo, precio FROM productos ")
    or die ("problema en la consulta".mysqli_error($conex)); 
    mysqli_close($conex);

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,300italic,700,700italic">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/milligram/1.4.1/milligram.css">
        <style>
            .table-container {
                max-width: 90%; 
                margin: 0 auto;
            }

            table {
                width: 100%; 
                border-collapse: collapse; 
            }

            th, td {
                padding: 5px;
                text-align: center;
                border: 1px solid;
            }

            td form {
                display: flex;
                justify-content: center;
                align-items: center;
                margin: 0;
            }

            td input[type="image"] {
                display: block;
                margin: auto;
            }
        </style>        
        <title>Panel de Productos - Panadería M</title>
    </head>
    <body>
        <div style="text-align: center">
        <h2>Lista de Productos - Panadería M</h2>
        <div>
            <a class="button" href="productos_alta.php">Nuevo Producto</a>
            <a class="button" href="page1.php">Volver al Menu</a>
        </div>
        </div>
        <br>
        <div class="table-container">
        <table border=1>
            <tr style="background: #c561ff;">
                <th>ID</th>
                <th>Nombre</th>
                <th>Tipo</th>
                <th>Precio</th>
                <th colspan=2>Acciones</th>
            </tr>
            <?php
            while($row_registros= mysqli_fetch_assoc($registros)) { ?>
                <tr>   
                    <td><?php echo $row_registros['id_producto'];?></td> 
                    <td><?php echo $row_registros['nombre'];?></td>
                    <td><?php echo $row_registros['tipo'];?></td>
                    <td> <?php echo $row_registros['precio'];?></td> 
                    <td align="center">
                        <form action="productos_modi.php" method="post">
                            <input type="hidden" name="id_producto" value="<?php echo $row_registros['id_producto'];?>"> <!-- para enviar datos, hiden es ocultar-->
                            <input type="image" name="editar" src="img/lapiz2.png" width="20" title="Editar">
                        </form>
                    </td>
                    <td align="center">
                        <form action="productos_baja.php" method="post">
                        <input type="hidden" name="id_producto" value="<?php echo $row_registros['id_producto'];?>"> <!-- para enviar datos, hiden es ocultar-->
                        <input type="hidden" name="nombre_prod" value="<?php echo $row_registros['nombre']?>">
                        <input type="image" name="eliminar" src="img/papelera2.png" width="25" title="Eliminar">
                        </form>
                    </td>
                </tr>
            <?php
            }
            ?>
        </table>
        </div>
    </body>
</html>