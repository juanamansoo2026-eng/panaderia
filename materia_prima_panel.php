<?php
require_once("conexion.php");
$registros= mysqli_query($conex,"SELECT id_materia_prima, nombre_mp, marca_id, marca_mp.nombre_marca, proveedor_id, proveedor.apellido, precio
FROM materia_prima
INNER JOIN proveedor ON proveedor.id_proveedor = materia_prima.proveedor_id
INNER JOIN marca_mp ON marca_mp.id_marca = materia_prima.marca_id
order by id_materia_prima asc")
or die ("problema en la consulta".mysqli_error($conex));
mysqli_close($conex);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/panaderia-moderna.css">
    <title>Panel de Materia Prima - Panadería M</title>
</head>
<body>
    <?php include("menu.php"); ?>

    <div class="table-container">
        <div style="text-align: center; margin-bottom: 20px;">
            <h2>Materia Prima - Panadería M</h2>
            <div style="margin-top: 15px;">
                <a class="button" href="materia_prima_alta.php">➕ Nueva Materia Prima</a>
                <a class="button" href="marca_panel.php"><svg class="ui-icon" aria-hidden="true" viewBox="0 0 24 24"><path d="M20 13 13 20 4 11V4h7z"/><circle cx="8" cy="8" r="1"/></svg> Marcas</a>
                <a class="button" href="proveedores_panel.php"><svg class="ui-icon" aria-hidden="true" viewBox="0 0 24 24"><path d="M3 6h11v11H3zM14 10h4l3 3v4h-7z"/><circle cx="7" cy="19" r="1.5"/><circle cx="18" cy="19" r="1.5"/></svg> Proveedores</a>
                <a class="button button-outline" href="page1.php">⬅ Volver al Menú</a>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Marca</th>
                    <th>Precio</th>
                    <th>Proveedor (Apellido)</th>
                    <th colspan="2">Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php while($row_registros= mysqli_fetch_assoc($registros)) { ?>
                <tr>   
                    <td><?php echo htmlspecialchars($row_registros['id_materia_prima']);?></td> 
                    <td><strong><?php echo htmlspecialchars($row_registros['nombre_mp']);?></strong></td>
                    <td><?php echo htmlspecialchars($row_registros['nombre_marca']);?></td>
                    <td><strong>$<?php echo number_format($row_registros['precio'], 2, ',', '.');?></strong></td>
                    <td><?php echo htmlspecialchars($row_registros['apellido']);?></td>
                    <td align="center">
                        <form action="materia_prima_modi.php" method="post">
                            <input type="hidden" name="id_materia_prima" value="<?php echo $row_registros['id_materia_prima'];?>">
                            <input type="image" name="editar" src="img/lapiz2.png" width="22" title="Editar">
                        </form>
                    </td>
                    <td align="center">
                        <form action="materia_prima_baja.php" method="post">
                            <input type="hidden" name="id_materia_prima" value="<?php echo $row_registros['id_materia_prima']; ?>">
                            <input type="hidden" name="nombre_mp" value="<?php echo htmlspecialchars($row_registros['nombre_mp']);?>">
                            <input type="image" name="eliminar" src="img/papelera2.png" width="22" title="Eliminar">
                        </form>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>

    <?php include("footer.php"); ?>
</body>
</html>