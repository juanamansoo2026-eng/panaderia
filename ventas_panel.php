<?php
require_once("conexion.php");
$registros= mysqli_query($conex,"SELECT id_venta, cliente_id, empleado_id, clientes.nombre as c_nombre, clientes.apellido as c_apellido, 
                                empleados.nombre as e_nombre, empleados.apellido as e_apellido, subtotal, descuento, total, forma_pago, fecha 
FROM ventas 
INNER JOIN clientes ON clientes.id_cliente = ventas.cliente_id
INNER JOIN empleados ON empleados.id_empleado = ventas.empleado_id
ORDER BY id_venta")
or die ("problema en la consulta".mysqli_error($conex));
mysqli_close($conex);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/panaderia-moderna.css">
    <title>Historial de Ventas - Panadería M</title>
</head>
<body>
    <?php include("menu.php"); ?>

    <div class="table-container">
        <div style="text-align: center; margin-bottom: 20px;">
            <h2>Historial de Ventas - Panadería M</h2>
            <div style="margin-top: 15px;">
                <a class="button" href="ventas_alta.php">➕ Nueva Venta</a>
                <a class="button" href="clientes_panel.php"><svg class="ui-icon" aria-hidden="true" viewBox="0 0 24 24"><path d="M16 20v-1a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v1"/><circle cx="10" cy="8" r="3"/><path d="M20 20v-1a4 4 0 0 0-3-4"/></svg> Clientes</a>
                <a class="button button-outline" href="page1.php">⬅ Volver al Menú</a>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID Venta</th>
                    <th>Cliente</th>
                    <th>Empleado</th>
                    <th>Subtotal</th>
                    <th>Descuento</th>
                    <th>Total</th>
                    <th>Forma de Pago</th>
                    <th>Fecha</th>
                    <th colspan="2">Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php while($row_registros= mysqli_fetch_assoc($registros)) { ?>
                <tr>   
                    <td><?php echo htmlspecialchars($row_registros['id_venta']);?></td> 
                    <td><strong><?php echo htmlspecialchars($row_registros['c_nombre'] . " " . $row_registros['c_apellido']);?></strong></td>
                    <td><?php echo htmlspecialchars($row_registros['e_nombre'] . " " . $row_registros['e_apellido']);?></td>
                    <td>$<?php echo number_format($row_registros['subtotal'], 2, ',', '.');?></td>
                    <td>$<?php echo number_format($row_registros['descuento'], 2, ',', '.');?></td>
                    <td><strong style="color: var(--bakery-primary);">$<?php echo number_format($row_registros['total'], 2, ',', '.');?></strong></td>
                    <td><span class="bakery-badge"><?php echo htmlspecialchars($row_registros['forma_pago']);?></span></td>
                    <td><?php echo htmlspecialchars($row_registros['fecha']);?></td>
                    <td align="center">
                        <form action="ventas_pdf.php" method="post" target="_blank">
                            <input type="hidden" name="id_venta" value="<?php echo $row_registros['id_venta'];?>">
                            <input type="image" name="editar" src="img/pdf.png" width="22" title="Imprimir PDF / Detalles">
                        </form>
                    </td>
                    <td align="center">
                        <form action="ventas_baja.php" method="post">
                            <input type="hidden" name="id_venta" value="<?php echo $row_registros['id_venta']; ?>">
                            <input type="hidden" name="info_venta" value="<?php echo htmlspecialchars($row_registros['c_nombre'] . ' ' . $row_registros['c_apellido'])?>">
                            <input type="hidden" name="info_total" value="<?php echo $row_registros['total']?>">
                            <input type="hidden" name="info_fecha" value="<?php echo $row_registros['fecha']?>">
                            <input type="image" name="eliminar" src="img/papelera2.png" width="22" title="Eliminar Venta">
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