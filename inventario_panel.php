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
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/panaderia-moderna.css">
    <title>Inventario de Materia Prima - Panadería M</title>
</head>
<body>
    <?php include("menu.php"); ?>

    <div class="table-container">
        <div style="text-align: center; margin-bottom: 20px;">
            <h2>Inventario de Materia Prima - Panadería M</h2>
            <div class="div-mayor">
                <div class="b1">
                    <a class="button" href="inventario_alta.php">Nuevo Registro</a>
                </div>

                <div class="b1">
                    <a class="button button-outline" href="page1.php">Volver al Menú</a>
                </div>
            </div>

        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Materia Prima</th>
                    <th>Marca</th>
                    <th>Ingresos</th>
                    <th>Salidas</th>
                    <th>Stock Total</th>
                    <th>Última Modificación</th>
                    <th colspan="2">Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php while($row_registros= mysqli_fetch_assoc($registros)) { ?>
                <tr>   
                    <td><?php echo htmlspecialchars($row_registros['id_inventario']);?></td>
                    <td><strong><?php echo htmlspecialchars($row_registros['nombre_mp']);?></strong></td>
                    <td><?php echo htmlspecialchars($row_registros['nombre_marca']);?></td>
                    <td style="color:#2E7D32;">+<?php echo htmlspecialchars($row_registros['cantidad_ingreso_mp']);?></td>
                    <td style="color:#C62828;">-<?php echo htmlspecialchars($row_registros['cantidad_salida_mp']);?></td>
                    <td><strong style="color:var(--bakery-primary); font-size:1.05rem;"><?php echo htmlspecialchars($row_registros['stock_total']);?></strong></td>
                    <td><?php echo htmlspecialchars($row_registros['fecha_modi']);?></td>
                    <td align="center">
                        <form action="inventario_modi.php" method="post">
                            <input type="hidden" name="id_inventario" value="<?php echo $row_registros['id_inventario'];?>">
                            <input type="image" name="editar" src="img/lapiz2.png" width="22" title="Editar">
                        </form>
                    </td>
                    <!-- <td align="center">
                        <form action="inventario_baja.php" method="post">
                            <input type="hidden" name="id_inventario" value="<?php echo $row_registros['id_inventario']; ?>">
                            <input type="hidden" name="nombre_inventario" value="<?php echo htmlspecialchars($row_registros['nombre_mp']);?>">
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