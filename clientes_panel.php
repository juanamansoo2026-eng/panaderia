<?php

    $fecha_actual = date('Y-m-d');
    require_once("conexion.php");
    $registros= mysqli_query($conex,"SELECT id_cliente, nombre, apellido, direccion, telefono, descripcion FROM clientes ")
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
        <title>Panel de clientes - Panadería M</title>
    </head>
    <body>
        <div style="text-align: center">
        <h2>Lista de clientes - Panadería M</h2>
        <div>
            <a class="button" href="clientes_alta.php">Nuevo Cliente</a>
            <a class="button" href="ventas_panel.php">Panel de Ventas</a>
            <a class="button" href="page1.php">Volver al Menu</a>
        </div>
        </div>
        <br>
        <div class="table-container">
        <table border=1>
            <tr style="background: #c561ff;">
                <th>ID</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Dirección</th>
                <th>Telefono</th>
                <th>Descripcion</th>
                <th colspan=2>Acciones</th>
            </tr>
            <?php
            while($row_registros= mysqli_fetch_assoc($registros)) { ?>
                <tr>   
                    <td><?php echo $row_registros['id_cliente'];?></td> 
                    <td><?php echo $row_registros['nombre'];?></td>
                    <td><?php echo $row_registros['apellido'];?></td>
                    <td><?php echo $row_registros['direccion'];?></td>
                    <td><?php echo $row_registros['telefono'];?></td>
                    <td> <?php echo $row_registros['descripcion'];?></td> 
                    <td align="center">
                        <form action="clientes_modi.php" method="post">
                            <input type="hidden" name="id_cliente" value="<?php echo $row_registros['id_cliente'];?>"> <!-- para enviar datos, hiden es ocultar-->
                            <input type="image" name="editar" src="img/lapiz2.png" width="20" title="Editar">
                        </form>
                    </td>
                    <td align="center">
                        <form action="clientes_baja.php" method="post">
                        <input type="hidden" name="id_cliente" value="<?php echo $row_registros['id_cliente'];?>"> <!-- para enviar datos, hiden es ocultar-->
                        <input type="hidden" name="nomyape_cli" value="<?php echo $row_registros['nombre'] . " " . $row_registros['apellido']?>">
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