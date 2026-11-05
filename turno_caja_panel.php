<?php
require_once("conexion.php");
$registros= mysqli_query($conex,"SELECT id_turno, empleado_id, empleados.nombre, empleados.apellido, turno, turno_caja.fecha, total_recaudado 
FROM turno_caja 
INNER JOIN empleados ON empleados.id_empleado = turno_caja.empleado_id")
or die ("problema en la consulta".mysqli_error($conex));
mysqli_close($conex);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/panaderia-moderna.css">
    <title>Turnos de Caja - Panadería M</title>
</head>
<body>
    <?php include("menu.php"); ?>

    <div class="table-container">
        <div style="text-align: center; margin-bottom: 20px;">
            <h2>Turnos de Caja - Panadería M</h2>
            <div class="div-mayor">
                <div class="b1">
                    <a class="button" href="turno_caja_alta.php">Nuevo Turno</a>
                </div>

                <div class="b1">
                    <a class="button button-outline" href="ventas_panel.php">Volver</a>
                </div>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID Turno</th>
                    <th>Empleado</th>
                    <th>Turno</th>
                    <th>Fecha</th>
                    <th>Total Recaudado</th>
                    <th colspan="2">Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php while($row_registros= mysqli_fetch_assoc($registros)) { ?>
                <tr>   
                    <td><?php echo htmlspecialchars($row_registros['id_turno']);?></td> 
                    <td><strong><?php echo htmlspecialchars($row_registros['nombre'] . ' ' . $row_registros['apellido']);?></strong></td>
                    <td><span class="bakery-badge"><?php echo htmlspecialchars($row_registros['turno']);?></span></td>
                    <td><?php echo htmlspecialchars($row_registros['fecha']);?></td>
                    <td><strong style="color:var(--bakery-primary);">$<?php echo number_format($row_registros['total_recaudado'], 2, ',', '.');?></strong></td>
                    <td align="center">
                        <form action="turno_caja_modi.php" method="post">
                            <input type="hidden" name="id_turno" value="<?php echo $row_registros['id_turno'];?>">
                            <input type="image" name="editar" src="img/lapiz2.png" width="22" title="Editar">
                        </form>
                    </td>
                    <!-- <td align="center">
                        <form action="turno_caja_baja.php" method="post">
                            <input type="hidden" name="id_turno" value="<?php echo $row_registros['id_turno']; ?>">
                            <input type="hidden" name="nomyape_emple" value="<?php echo htmlspecialchars($row_registros['nombre'] . ' ' . $row_registros['apellido']);?>">
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