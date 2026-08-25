<?php

require_once("conexion.php");
$registros= mysqli_query($conex,"SELECT id_venta, cliente_id, empleado_id, clientes.nombre as c_nombre, clientes.apellido as c_apellido, 
                                empleados.nombre as e_nombre, empleados.apellido as e_apellido, subtotal, descuento, total, forma_pago, fecha 
FROM ventas 
INNER JOIN clientes ON clientes.id_cliente = ventas.cliente_id
INNER JOIN empleados ON empleados.id_empleado = ventas.empleado_id
ORDER BY id_venta") //el ON es la tabla que vamos a relacionar osea la clave primaria con la clave foranea, osea nombretabla.claveprimaria = con la nombretabla.claveforanea
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
                max-width: 95%; 
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
    <title>Lista de Ventas - Panadería M</title>
</head>
<body>

    <div style="text-align: center">
        <h2>Lista de Ventas - Panadería M</h2>
        <div>
            <a class="button" href="ventas_alta.php">Nueva Venta</a>
            <a class="button" href="clientes_panel.php">Volver</a>
            <a class="button" href="page1.php">Volver al Menu</a>
        </div>
    </div>
    <br>
    <div class="table-container">
        <table border=1 width="80%">
            <tr style="background:#c561ff;">
                <th>ID Venta</th>
                <th>Nombre del Cliente</th>
                <th>Apellido del Cliente</th>
                <th>Nombre del Empleado</th>
                <th>Apellido del Empleado</th>
                <th>Subtotal</th>
                <th>Descuento</th>
                <th>Total</th>
                <th>Forma de Pago</th>
                <th>Fecha</th>
                <th colspan=2>Acciones</th>
            </tr>
            <?php
            while($row_registros= mysqli_fetch_assoc($registros)) { ?>
                <tr>   
                    <td><?php echo $row_registros['id_venta'];?></td> 
                    <td><?php echo $row_registros['c_nombre'];?></td>
                    <td><?php echo $row_registros['c_apellido'];?></td>
                    <td><?php echo $row_registros['e_nombre'];?></td>
                    <td><?php echo $row_registros['e_apellido'];?></td>
                    <td><?php echo $row_registros['subtotal'];?></td>
                    <td><?php echo $row_registros['descuento'];?></td>
                    <td><?php echo $row_registros['total'];?></td>
                    <td><?php echo $row_registros['forma_pago'];?></td>
                    <td> <?php echo $row_registros['fecha'];?></td>
                    <td align="center">
                        <form action="ventas_pdf.php" method="post" target="_blank">
                            <input type="hidden" name="id_venta" value="<?php echo $row_registros['id_venta'];?>"> <!-- para enviar datos, hiden es ocultar-->
                            <!--<input type="submit" name="editar" value="Modificar">-->
                            <input type="image" name="editar" src="img/pdf.png" width="22" title="Detalles">
                        </form>
                    </td>
                    <td align="center">
                        <form action="ventas_baja.php" method="post">
                        <input type="hidden" name="id_venta" value="<?php echo $row_registros['id_venta']; ?>">
                        <input type="hidden" name="info_venta" value="<?php echo $row_registros['c_nombre'] . ' ' . $row_registros['c_apellido']?>">
                        <input type="hidden" name="info_total" value="<?php echo $row_registros['total']?>">
                        <input type="hidden" name="info_fecha" value="<?php echo $row_registros['fecha']?>">
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