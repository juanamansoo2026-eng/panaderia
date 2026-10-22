<?php
    $fecha_actual = date('Y-m-d');
    require_once("conexion.php");
    $registros= mysqli_query($conex,"SELECT id_cliente, nombre, apellido, direccion, telefono, descripcion FROM clientes ")
    or die ("problema en la consulta".mysqli_error($conex)); 
    mysqli_close($conex);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/panaderia-moderna.css">
    <title>Panel de Clientes - Panadería M</title>
</head>
<body>
    <?php include("menu.php"); ?>

    <div class="table-container">
        <div style="text-align: center; margin-bottom: 20px;">
            <h2>Panel de Clientes - Panadería M</h2>
            <div style="margin-top: 15px;">
                <a class="button" href="clientes_alta.php">➕ Nuevo Cliente</a>
                <a class="button" href="ventas_panel.php"><svg class="ui-icon" aria-hidden="true" viewBox="0 0 24 24"><path d="M5 8h14l-1 12H6L5 8z"/><path d="M9 8a3 3 0 0 1 6 0"/></svg> Panel de Ventas</a>
                <a class="button button-outline" href="page1.php">⬅ Volver al Menú</a>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Dirección</th>
                    <th>Teléfono</th>
                    <th>Descripción</th>
                    <th colspan="2">Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php while($row_registros= mysqli_fetch_assoc($registros)) { ?>
                <tr>   
                    <td><?php echo htmlspecialchars($row_registros['id_cliente']);?></td> 
                    <td><strong><?php echo htmlspecialchars($row_registros['nombre']);?></strong></td>
                    <td><?php echo htmlspecialchars($row_registros['apellido']);?></td>
                    <td><?php echo htmlspecialchars($row_registros['direccion']);?></td>
                    <td><?php echo htmlspecialchars($row_registros['telefono']);?></td>
                    <td><?php echo htmlspecialchars($row_registros['descripcion']);?></td> 
                    <td align="center">
                        <form action="clientes_modi.php" method="post">
                            <input type="hidden" name="id_cliente" value="<?php echo $row_registros['id_cliente'];?>">
                            <input type="image" name="editar" src="img/lapiz2.png" width="22" title="Editar">
                        </form>
                    </td>
                    <td align="center">
                        <form action="clientes_baja.php" method="post">
                            <input type="hidden" name="id_cliente" value="<?php echo $row_registros['id_cliente'];?>">
                            <input type="hidden" name="nomyape_cli" value="<?php echo htmlspecialchars($row_registros['nombre'] . " " . $row_registros['apellido']);?>">
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