<?php
    require_once("conexion.php");
    $registros= mysqli_query($conex,"SELECT id_categoria_gasto, nombre_categoria FROM categoria_gasto ")
    or die ("problema en la consulta".mysqli_error($conex)); 
    mysqli_close($conex);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/panaderia-moderna.css">
    <title>Categorías de Gastos - Panadería M</title>
</head>
<body>
    <?php include("menu.php"); ?>

    <div class="table-container" style="max-width: 800px;">
        <div style="text-align: center; margin-bottom: 20px;">
            <h2>Categorías de Gastos - Panadería M</h2>
            <div class="div-mayor">
                <div class="b1">
                    <a class="button" href="categoria_gasto_alta.php">Nueva Categoría</a>
                </div>

                <div class="b1">
                    <a class="button button-outline" href="gastos_administrativos_panel.php">Volver</a>
                </div>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID Categoría</th>
                    <th>Nombre Categoría</th>
                    <th colspan="2">Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php while($row_registros= mysqli_fetch_assoc($registros)) { ?>
                <tr>   
                    <td align="center"><?php echo htmlspecialchars($row_registros['id_categoria_gasto']);?></td> 
                    <td><strong><?php echo htmlspecialchars($row_registros['nombre_categoria']);?></strong></td>
                    <td align="center">
                        <form action="categoria_gasto_modi.php" method="post">
                            <input type="hidden" name="id_categoria_gasto" value="<?php echo $row_registros['id_categoria_gasto'];?>">
                            <input type="image" name="editar" src="img/lapiz2.png" width="22" title="Editar">
                        </form>
                    </td>
                    <!-- <td align="center">
                        <form action="categoria_gasto_baja.php" method="post">
                            <input type="hidden" name="id_categoria_gasto" value="<?php echo $row_registros['id_categoria_gasto'];?>">
                            <input type="hidden" name="nombre_categoria" value="<?php echo htmlspecialchars($row_registros['nombre_categoria']);?>">
                            <input type="image" name="eliminar" src="img/papelera2.png" width="22" title="Eliminar">
                        </form>
                    </td> -->
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>

    <?php include("footer.php"); ?>
</body>
</html>