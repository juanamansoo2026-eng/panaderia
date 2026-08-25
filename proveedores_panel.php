<?php

    $fecha_actual = date('Y-m-d');
    require_once("conexion.php");
    $registros= mysqli_query($conex,"SELECT id_proveedor, nombre, apellido, telefono, descripcion FROM proveedor ")
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
        <title>Panel de Proveedores - Panadería M</title>
    </head>
    <body>
        <div style="text-align: center">
        <h2>Lista de Proveedores - Panadería M</h2>
        <div>
            <a class="button" href="proveedores_alta.php">Nuevo Proveedor</a>
            <a class="button" href="materia_prima_panel.php">Panel de Materia Prima</a>
            <a class="button" href="inventario_panel.php">Panel de Inventario</a>
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
                <th>Telefono</th>
                <th>¿Que Vende?</th>
                <th colspan=2>Acciones</th>
            </tr>
            <?php
            while($row_registros= mysqli_fetch_assoc($registros)) { ?>
                <tr>   
                    <td><?php echo $row_registros['id_proveedor'];?></td> 
                    <td><?php echo $row_registros['nombre'];?></td>
                    <td><?php echo $row_registros['apellido'];?></td>
                    <td><?php echo $row_registros['telefono'];?></td>
                    <td> <?php echo $row_registros['descripcion'];?></td> 
                    <td align="center">
                        <form action="proveedores_modi.php" method="post">
                            <input type="hidden" name="id_proveedor" value="<?php echo $row_registros['id_proveedor'];?>"> <!-- para enviar datos, hiden es ocultar-->
                            <input type="image" name="editar" src="img/lapiz2.png" width="20" title="Editar">
                        </form>
                    </td>
                    <td align="center">
                        <form action="proveedores_baja.php" method="post">
                        <input type="hidden" name="id_proveedor" value="<?php echo $row_registros['id_proveedor'];?>"> <!-- para enviar datos, hiden es ocultar-->
                        <input type="hidden" name="nomyape_prov" value="<?php echo $row_registros['nombre'] . " " . $row_registros['apellido']?>">
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