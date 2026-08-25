<?php

    require_once("conexion.php");
    $registros= mysqli_query($conex,"SELECT id_categoria_gasto, nombre_categoria FROM categoria_gasto ")
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
        <title>Lista de Categorias de Gastos - Panadería M</title>
    </head>
    <body>
        <div style="text-align: center">
        <h2>Lista de Categoria de Gastos - Panadería M</h2>
        <div>
            <a class="button" href="categoria_gasto_alta.php">Nueva Categoria</a>
            <a class="button" href="gastos_administrativos_panel.php">Volver</a>
            <a class="button" href="page1.php">Volver al Menu</a>
        </div>
        </div>
        <br>
        <div class="table-container">
        <table border=1>
            <tr style="background: #c561ff;">
                <th>ID Categoria</th>
                <th>Nombre Categoria</th>
                <th colspan=2>Acciones</th>
            </tr>
            <?php
            while($row_registros= mysqli_fetch_assoc($registros)) { ?>
                <tr>   
                    <td><?php echo $row_registros['id_categoria_gasto'];?></td> 
                    <td><?php echo $row_registros['nombre_categoria'];?></td>
                    <td align="center">
                        <form action="categoria_gasto_modi.php" method="post">
                            <input type="hidden" name="id_categoria_gasto" value="<?php echo $row_registros['id_categoria_gasto'];?>"> <!-- para enviar datos, hiden es ocultar-->
                            <input type="image" name="editar" src="img/lapiz2.png" width="20" title="Editar">
                        </form>
                    </td>
                    <td align="center">
                        <form action="categoria_gasto_baja.php" method="post">
                        <input type="hidden" name="id_categoria_gasto" value="<?php echo $row_registros['id_categoria_gasto'];?>"> <!-- para enviar datos, hiden es ocultar-->
                        <input type="hidden" name="nombre_categoria" value="<?php echo $row_registros['nombre_categoria']?>">
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