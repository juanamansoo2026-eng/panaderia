<?php

require_once("conexion.php");
$registros= mysqli_query($conex,"SELECT id_turno, empleado_id, empleados.nombre, empleados.apellido, turno, turno_caja.fecha, total_recaudado 
FROM turno_caja 
INNER JOIN empleados ON empleados.id_empleado = turno_caja.empleado_id") //el ON es la tabla que vamos a relacionar osea la clave primaria con la clave foranea, osea nombretabla.claveprimaria = con la nombretabla.claveforanea
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
    <title>Lista de Turnos de Caja - Panadería M</title>
</head>
<body>

    <div style="text-align: center">
        <h2>Lista de Turnos de Caja - Panadería M</h2>
        <div>
            <a class="button" href="turno_caja_alta.php">Nuevo Turno</a>
            <a class="button" href="empleados_panel.php">Volver</a>
            <a class="button" href="page1.php">Volver al Menu</a>
        </div>
    </div>
    <br>
    <div class="table-container">
        <table border=1 width="80%">
            <tr style="background:#c561ff;">
                <th>ID Turno</th>
                <th>Nombre Empleado</th>
                <th>Apellido Empleado</th>
                <th>Turno</th>
                <th>Fecha</th>
                <th>Total Recaudado</th>
                <th colspan=2>Acciones</th>
            </tr>
            <?php
            while($row_registros= mysqli_fetch_assoc($registros)) { ?>
                <tr>   
                    <td><?php echo $row_registros['id_turno'];?></td> 
                    <td><?php echo $row_registros['nombre'];?></td>
                    <td><?php echo $row_registros['apellido'];?></td>
                    <td><?php echo $row_registros['turno'];?></td>
                    <td><?php echo $row_registros['fecha'];?></td>
                    <td><?php echo $row_registros['total_recaudado'];?></td>
                    <td align="center">
                        <form action="turno_caja_modi.php" method="post">
                            <input type="hidden" name="id_turno" value="<?php echo $row_registros['id_turno'];?>"> <!-- para enviar datos, hiden es ocultar-->
                            <!--<input type="submit" name="editar" value="Modificar">-->
                            <input type="image" name="editar" src="img/lapiz2.png" width="20" title="Editar">
                        </form>
                    </td>
                    <td align="center">
                        <form action="turno_caja_baja.php" method="post">
                        <input type="hidden" name="id_turno" value="<?php echo $row_registros['id_turno']; ?>">
                        <input type="hidden" name="nomyape_emple" value="<?php echo $row_registros['nombre'] . ' ' . $row_registros['apellido']?>">
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