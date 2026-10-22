<?php
    $fecha_actual = date('Y-m-d');
    require_once("conexion.php");
    $registros= mysqli_query($conex,"SELECT id_marca, nombre_marca FROM marca_mp ")
    or die ("problema en la consulta".mysqli_error($conex)); 
    mysqli_close($conex);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/panaderia-moderna.css">
    <title>Marcas de Materia Prima - Panadería M</title>
</head>
<body>
    <?php include("menu.php"); ?>

    <div class="table-container" style="max-width: 800px;">
        <div style="text-align: center; margin-bottom: 20px;">
            <h2>Marcas de Materia Prima - Panadería M</h2>
            <div style="margin-top: 15px;">
                <a class="button" href="marca_alta.php">➕ Nueva Marca</a>
                <a class="button" href="materia_prima_panel.php"><svg class="ui-icon" aria-hidden="true" viewBox="0 0 24 24"><path d="M12 21V4m0 7c-3 0-5-2-5-5 3 0 5 2 5 5zm0 4c3 0 5-2 5-5-3 0-5 2-5 5z"/></svg> Materia Prima</a>
                <a class="button button-outline" href="page1.php">⬅ Volver al Menú</a>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID Marca</th>
                    <th>Nombre de Marca</th>
                    <th colspan="2">Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php while($row_registros= mysqli_fetch_assoc($registros)) { ?>
                <tr>   
                    <td align="center"><?php echo htmlspecialchars($row_registros['id_marca']);?></td> 
                    <td><strong><?php echo htmlspecialchars($row_registros['nombre_marca']);?></strong></td>
                    <td align="center">
                        <form action="marca_modi.php" method="post">
                            <input type="hidden" name="id_marca" value="<?php echo $row_registros['id_marca'];?>">
                            <input type="image" name="editar" src="img/lapiz2.png" width="22" title="Editar">
                        </form>
                    </td>
                    <td align="center">
                        <form action="marca_baja.php" method="post">
                            <input type="hidden" name="id_marca" value="<?php echo $row_registros['id_marca'];?>">
                            <input type="hidden" name="nombre_marca" value="<?php echo htmlspecialchars($row_registros['nombre_marca']);?>">
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