<?php

require_once("conexion.php");
$registros= mysqli_query($conex,"SELECT id_pedido_compra, pedido_compra.proveedor_id, proveedor.nombre, proveedor.apellido, materia_prima_id, materia_prima.nombre_mp, marca_pc, cantidad, pedido_compra.precio, total, fecha 
FROM pedido_compra 
INNER JOIN proveedor ON proveedor.id_proveedor = pedido_compra.proveedor_id
INNER JOIN materia_prima ON materia_prima.id_materia_prima = pedido_compra.materia_prima_id
ORDER BY fecha DESC") 
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
    <title>Lista de Pedidos de Compra - Panadería M</title>
</head>
<body>

    <div style="text-align: center">
        <h2>Lista de Pedidos de Compra - Panadería M</h2>
        <div>
            <a class="button" href="pedido_compra_alta.php">Nuevo Pedido</a>
            <a class="button" href="gastos_administrativos_panel.php">Volver</a>
            <a class="button" href="page1.php">Volver al Menu</a>
        </div>
    </div>
    <br>
    <div class="table-container">
        <table border=1 width="80%">
            <tr style="background:#c561ff;">
                <th>ID Pedido</th>
                <th>Nombre Proveedor</th>
                <th>Apellido Proveedor</th>
                <th>Nombre del Producto</th>
                <th>Marca</th>
                <th>Cantidad</th>
                <th>Precio Unitario</th>
                <th>Total</th>
                <th>Fecha</th>
                <th colspan=2>Acciones</th>
            </tr>
            <?php
            while($row_registros= mysqli_fetch_assoc($registros)) { ?>
                <tr>   
                    <td><?php echo $row_registros['id_pedido_compra'];?></td> 
                    <td><?php echo $row_registros['nombre'];?></td>
                    <td><?php echo $row_registros['apellido'];?></td>
                    <td><?php echo $row_registros['nombre_mp'];?></td>
                    <td><?php echo $row_registros['marca_pc'];?></td>
                    <td><?php echo $row_registros['cantidad'];?></td>
                    <td><?php echo $row_registros['precio'];?></td>
                    <td><?php echo $row_registros['total'];?></td>
                    <td> <?php echo $row_registros['fecha'];?></td>
                    <td align="center">
                        <form action="pedido_compra_modi.php" method="post">
                            <input type="hidden" name="id_pedido_compra" value="<?php echo $row_registros['id_pedido_compra'];?>"> <!-- para enviar datos, hiden es ocultar-->
                            <!--<input type="submit" name="editar" value="Modificar">-->
                            <input type="image" name="editar" src="img/lapiz2.png" width="20" title="Editar">
                        </form>
                    </td>
                    <td align="center">
                        <form action="pedido_compra_baja.php" method="post">
                        <input type="hidden" name="id_pedido_compra" value="<?php echo $row_registros['id_pedido_compra']; ?>">
                        <input type="hidden" name="nomyape_prov" value="<?php echo $row_registros['nombre'] . ' ' . $row_registros['apellido']?>">
                        <!--<input type="submit" value="Eliminar">-->
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