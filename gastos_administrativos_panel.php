<?php
require_once("conexion.php");
$registros= mysqli_query($conex,"SELECT id_gasto, turno_id, turno_caja.turno, gastos_administrativos.descripcion, categoria_gasto_id, nombre_categoria, monto, empleado, gastos_administrativos.fecha 
FROM gastos_administrativos 
INNER JOIN turno_caja ON turno_caja.id_turno = gastos_administrativos.turno_id
INNER JOIN categoria_gasto ON categoria_gasto.id_categoria_gasto = gastos_administrativos.categoria_gasto_id")
or die ("problema en la consulta".mysqli_error($conex));
mysqli_close($conex);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/panaderia-moderna.css">
    <title>Gastos Administrativos - Panadería M</title>
</head>
<body>
    <?php include("menu.php"); ?>

    <div class="table-container">
        <div style="text-align: center; margin-bottom: 20px;">
            <h2>Gastos Administrativos - Panadería M</h2>

            <div class="div-mayor">
                <div class="b1">
                    <a class="button" href="gastos_administrativos_alta.php">Nuevo Gasto</a>
                    <a class="button" href="categoria_gasto_panel.php"><svg class="ui-icon" aria-hidden="true" viewBox="0 0 24 24"><path d="M20 13 13 20 4 11V4h7z"/><circle cx="8" cy="8" r="1"/></svg> Categorías de Gastos</a>
                    <a class="button" href="pedido_compra_panel.php"><svg class="ui-icon" aria-hidden="true" viewBox="0 0 24 24"><path d="m3 7 9-4 9 4v10l-9 4-9-4zM3 7l9 4 9-4m-9 4v10"/></svg> Compras a Proveedores</a>
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
                    <th>Descripción</th>
                    <th>Categoría</th>
                    <th>Monto</th>
                    <th>Turno</th>
                    <th>Empleado</th>
                    <th>Fecha</th>
                    <th colspan="2">Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php while($row_registros= mysqli_fetch_assoc($registros)) { ?>
                <tr>   
                    <td><?php echo htmlspecialchars($row_registros['id_gasto']);?></td> 
                    <td><strong><?php echo htmlspecialchars($row_registros['descripcion']);?></strong></td>
                    <td><?php echo htmlspecialchars($row_registros['nombre_categoria']);?></td>
                    <td><strong style="color:var(--bakery-danger);">$<?php echo number_format($row_registros['monto'], 2, ',', '.');?></strong></td>
                    <td><?php echo htmlspecialchars($row_registros['turno']);?></td>
                    <td><?php echo htmlspecialchars($row_registros['empleado']);?></td>
                    <td><?php echo htmlspecialchars($row_registros['fecha']);?></td>
                    <td align="center">
                        <form action="gastos_administrativos_modi.php" method="post">
                            <input type="hidden" name="id_gasto" value="<?php echo $row_registros['id_gasto'];?>">
                            <input type="image" name="editar" src="img/lapiz2.png" width="22" title="Editar">
                        </form>
                    </td>
                    <!-- <td align="center">
                        <form action="gastos_administrativos_baja.php" method="post">
                            <input type="hidden" name="id_gasto" value="<?php echo $row_registros['id_gasto']; ?>">
                            <input type="hidden" name="nombre_gasto" value="<?php echo htmlspecialchars($row_registros['descripcion']);?>">
                            <input type="hidden" name="precio_gasto" value="<?php echo $row_registros['monto'];?>">
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