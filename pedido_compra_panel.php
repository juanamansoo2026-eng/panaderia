<?php
require_once("conexion.php");
$registros= mysqli_query($conex,"SELECT id_pedido_compra, pedido_compra.proveedor_id, proveedor.nombre, proveedor.apellido, materia_prima_id, materia_prima.nombre_mp, marca_pc, cantidad, pedido_compra.precio, total, fecha 
FROM pedido_compra 
INNER JOIN proveedor ON proveedor.id_proveedor = pedido_compra.proveedor_id
INNER JOIN materia_prima ON materia_prima.id_materia_prima = pedido_compra.materia_prima_id
ORDER BY fecha DESC") 
or die ("problema en la consulta".mysqli_error($conex)); 
mysqli_close($conex);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/panaderia-moderna.css">
    <title>Pedidos de Compra - Panadería M</title>
</head>
<body>
    <?php include("menu.php"); ?>

    <div class="table-container">
        <div style="text-align: center; margin-bottom: 20px;">
            <h2>Pedidos de Compra - Panadería M</h2>
            <div style="margin-top: 15px;">
                <a class="button" href="pedido_compra_alta.php">➕ Nuevo Pedido</a>
                <a class="button" href="gastos_administrativos_panel.php"><svg class="ui-icon" aria-hidden="true" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M15 8.5c-.7-.6-1.7-1-3-1-1.7 0-3 .8-3 2s1.3 2 3 2 3 .8 3 2-1.3 2-3 2c-1.3 0-2.3-.4-3-1M12 6v12"/></svg> Gastos Administrativos</a>
                <a class="button button-outline" href="page1.php">⬅ Volver al Menú</a>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID Pedido</th>
                    <th>Proveedor</th>
                    <th>Materia Prima</th>
                    <th>Marca</th>
                    <th>Cantidad</th>
                    <th>P. Unitario</th>
                    <th>Total</th>
                    <th>Fecha</th>
                    <th colspan="2">Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php while($row_registros= mysqli_fetch_assoc($registros)) { ?>
                <tr>   
                    <td><?php echo htmlspecialchars($row_registros['id_pedido_compra']);?></td> 
                    <td><strong><?php echo htmlspecialchars($row_registros['nombre'] . ' ' . $row_registros['apellido']);?></strong></td>
                    <td><?php echo htmlspecialchars($row_registros['nombre_mp']);?></td>
                    <td><?php echo htmlspecialchars($row_registros['marca_pc']);?></td>
                    <td><?php echo htmlspecialchars($row_registros['cantidad']);?></td>
                    <td>$<?php echo number_format($row_registros['precio'], 2, ',', '.');?></td>
                    <td><strong style="color:var(--bakery-primary);">$<?php echo number_format($row_registros['total'], 2, ',', '.');?></strong></td>
                    <td><?php echo htmlspecialchars($row_registros['fecha']);?></td>
                    <td align="center">
                        <form action="pedido_compra_modi.php" method="post">
                            <input type="hidden" name="id_pedido_compra" value="<?php echo $row_registros['id_pedido_compra'];?>">
                            <input type="image" name="editar" src="img/lapiz2.png" width="22" title="Editar">
                        </form>
                    </td>
                    <td align="center">
                        <form action="pedido_compra_baja.php" method="post">
                            <input type="hidden" name="id_pedido_compra" value="<?php echo $row_registros['id_pedido_compra']; ?>">
                            <input type="hidden" name="nomyape_prov" value="<?php echo htmlspecialchars($row_registros['nombre'] . ' ' . $row_registros['apellido']);?>">
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