<?php

require_once("conexion.php");
$registros= mysqli_query($conex,"SELECT id_gasto, turno_id, turno_caja.turno, gastos_administrativos.descripcion, categoria_gasto_id, nombre_categoria, monto, empleado, gastos_administrativos.fecha 
FROM gastos_administrativos 
INNER JOIN turno_caja ON turno_caja.id_turno = gastos_administrativos.turno_id
INNER JOIN categoria_gasto ON categoria_gasto.id_categoria_gasto = gastos_administrativos.categoria_gasto_id") //el ON es la tabla que vamos a relacionar osea la clave primaria con la clave foranea, osea nombretabla.claveprimaria = con la nombretabla.claveforanea
or die ("problema en la consulta".mysqli_error($conex)); //mysqli_error me muestra un mensaje con el problema y su linea, "or die" es si sale bien no pasa nada, "O die" o te sale el mensaje error
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
    <title>Lista de Gastos Administrativos - Panadería M</title>
</head>
<body>

    <div style="text-align: center">
        <h2>Lista de Gastos Administrativos - Panadería M</h2>
        <div>
            <a class="button" href="gastos_administrativos_alta.php">Nuevo Gasto</a>
            <a class="button" href="categoria_gasto_panel.php">Panel de Categoria de Gastos</a>
            <a class="button" href="pedido_compra_panel.php">Panel de Compras Proveedores</a>
            <a class="button" href="page1.php">Volver al Menu</a>
        </div>
    </div>
    <br>
    <div class="table-container">
        <table border=1 width="80%">
            <tr style="background:#c561ff;">
                <th>ID Gasto</th>
                <th>Descripcion</th>
                <th>Categoria</th>
                <th>Monto</th>
                <th>Turno</th>
                <th>Empleado</th>
                <th>Fecha</th>
                <th colspan=2>Acciones</th>
            </tr>
            <?php
            while($row_registros= mysqli_fetch_assoc($registros)) { ?>
                <tr>   
                    <td><?php echo $row_registros['id_gasto'];?></td> 
                    <td><?php echo $row_registros['descripcion'];?></td>
                    <td><?php echo $row_registros['nombre_categoria'];?></td>
                    <td><?php echo $row_registros['monto'];?></td>
                    <td><?php echo $row_registros['turno'];?></td>
                    <td><?php echo $row_registros['empleado'];?></td>
                    <td> <?php echo $row_registros['fecha'];?></td>
                    <td align="center">
                        <form action="gastos_administrativos_modi.php" method="post">
                            <input type="hidden" name="id_gasto" value="<?php echo $row_registros['id_gasto'];?>"> <!-- para enviar datos, hiden es ocultar-->
                            <!--<input type="submit" name="editar" value="Modificar">-->
                            <input type="image" name="editar" src="img/lapiz2.png" width="20" title="Editar">
                        </form>
                    </td>
                    <td align="center">
                        <form action="gastos_administrativos_baja.php" method="post">
                        <input type="hidden" name="id_gasto" value="<?php echo $row_registros['id_gasto']; ?>">
                        <input type="hidden" name="nombre_gasto" value="<?php echo $row_registros['descripcion']?>">
                        <input type="hidden" name="precio_gasto" value="<?php echo $row_registros['monto']?>">
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