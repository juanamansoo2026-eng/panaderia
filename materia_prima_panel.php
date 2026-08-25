<?php

require_once("conexion.php");
$registros= mysqli_query($conex,"SELECT id_materia_prima, nombre_mp, marca_id, marca_mp.nombre_marca, proveedor_id, proveedor.apellido, precio
FROM materia_prima
INNER JOIN proveedor ON proveedor.id_proveedor = materia_prima.proveedor_id
INNER JOIN marca_mp ON marca_mp.id_marca = materia_prima.marca_id
order by id_materia_prima asc") //el ON es la tabla que vamos a relacionar osea la clave primaria con la clave foranea, osea nombretabla.claveprimaria = con la nombretabla.claveforanea
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
    <title>Lista de Materia Prima - Panadería M</title>
</head>
<body>

    <div style="text-align: center">
        <h2>Lista de Materia Prima - Panadería M</h2>
        <div>
            <a class="button" href="materia_prima_alta.php">Nueva Materia Prima</a>
            <a class="button" href="marca_panel.php">Panel de Marcas</a>
            <a class="button" href="proveedores_panel.php">Volver</a>
            <a class="button" href="page1.php">Volver al Menu</a>
        </div>
    </div>
    <br>
    <div class="table-container">
        <table border=1 width="80%">
            <tr style="background:#c561ff;">
                <th>ID Materia Prima</th>
                <th>Nombre</th>
                <th>Marca</th>
                <th>Precio</th>
                <th>Apellido Proveedor</th>
                <th colspan=2>Acciones</th>
            </tr>
            <?php
            while($row_registros= mysqli_fetch_assoc($registros)) { ?>
                <tr>   
                    <td><?php echo $row_registros['id_materia_prima'];?></td> 
                    <td><?php echo $row_registros['nombre_mp'];?></td>
                    <td><?php echo $row_registros['nombre_marca'];?></td>
                    <td><?php echo $row_registros['precio'];?></td>
                    <td><?php echo $row_registros['apellido'];?></td>
                    <td align="center">
                        <form action="materia_prima_modi.php" method="post">
                            <input type="hidden" name="id_materia_prima" value="<?php echo $row_registros['id_materia_prima'];?>"> <!-- para enviar datos, hiden es ocultar-->
                            <!--<input type="submit" name="editar" value="Modificar">-->
                            <input type="image" name="editar" src="img/lapiz2.png" width="20" title="Editar">
                        </form>
                    </td>
                    <td align="center">
                        <form action="materia_prima_baja.php" method="post">
                        <input type="hidden" name="id_materia_prima" value="<?php echo $row_registros['id_materia_prima']; ?>">
                        <input type="hidden" name="nombre_mp" value="<?php echo $row_registros['nombre_mp']?>">
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