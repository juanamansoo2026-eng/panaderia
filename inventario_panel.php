<?php
$fecha_actual = date('Y-m-d');

require_once("conexion.php");
$registros= mysqli_query($conex,"SELECT id_inventario, materia_prima_id, materia_prima.nombre_mp, marca_mp.nombre_marca, cantidad_ingreso_mp, cantidad_salida_mp, stock_total, fecha_modi
FROM inventario
INNER JOIN marca_mp ON marca_mp.id_marca = inventario.marca_id
INNER JOIN materia_prima ON materia_prima.id_materia_prima = inventario.materia_prima_id
ORDER BY id_inventario asc") 
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
    <title>Lista de inventario de Materia Prima - Panadería M</title>
</head>
<body>

    <div style="text-align: center">
        <h2>Lista de inventario de Materia Prima - Panadería M</h2>
        <div>
            <a class="button" href="inventario_alta.php">Nuevo inventario</a>
            <a class="button" href="proveedores_panel.php">Volver</a>
            <a class="button" href="page1.php">Volver al Menu</a>
        </div>
    </div>
    <br>
    <div class="table-container">
        <table border=1 width="80%">
            <tr style="background:#c561ff;">
                <th>ID Inventario</th>
                <th>Nombre</th>
                <th>Marca</th>
                <th>Cantidad de Ingreso</th>
                <th>Cantidad de Salida</th>
                <th>Stock Total</th>
                <th>Fecha Modificación</th>
                <th colspan=2>Acciones</th>
            </tr>
            <?php
            while($row_registros= mysqli_fetch_assoc($registros)) { ?>
                <tr>   
                    <td><?php echo $row_registros['id_inventario'];?></td>
                    <td><?php echo $row_registros['nombre_mp'];?></td>
                    <td><?php echo $row_registros['nombre_marca'];?></td>
                    <td><?php echo $row_registros['cantidad_ingreso_mp'];?></td>
                    <td><?php echo $row_registros['cantidad_salida_mp'];?></td>
                    <td><?php echo $row_registros['stock_total'];?></td>
                    <td><?php echo $row_registros['fecha_modi'];?></td>
                    <td align="center">
                        <form action="inventario_modi.php" method="post">
                            <input type="hidden" name="id_inventario" value="<?php echo $row_registros['id_inventario'];?>"> <!-- para enviar datos, hiden es ocultar-->
                            <!--<input type="submit" name="editar" value="Modificar">-->
                            <input type="image" name="editar" src="img/lapiz2.png" width="20" title="Editar">
                        </form>
                    </td>
                    <td align="center">
                        <form action="inventario_baja.php" method="post">
                        <input type="hidden" name="id_inventario" value="<?php echo $row_registros['id_inventario']; ?>">
                        <input type="hidden" name="nombre_inventario" value="<?php echo $row_registros['nombre_mp']?>">
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