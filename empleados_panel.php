<?php 
    $fecha_actual = date('Y-m-d');

    require_once("conexion.php");
    $registros = mysqli_query($conex, "SELECT empleados.*, roles.nombre_rol
    FROM empleados
    INNER JOIN roles ON roles.id_rol = empleados.id_rol 
    WHERE baja = '1'
    ORDER BY empleados.id_empleado ASC")
        or die("Problema en la consulta: " . mysqli_error($conex));
    mysqli_close($conex);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/panaderia-moderna.css">
    <title>Panel de Empleados - Panadería M</title>
    <style>
        .tachado {
            text-decoration: line-through;
            opacity: 0.6;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function actualizarBaja(id_empleado, baja) {
            $.ajax({
                url: 'actualizar_baja.php',
                type: 'POST',
                data: {
                    id_empleado: id_empleado,
                    baja: baja
                }
            });
        }
    </script>
</head>
<body>
    <?php include("menu.php"); ?>

    <div class="table-container">
        <div style="text-align: center; margin-bottom: 20px;">
            <h2>Lista de Empleados - Panadería M</h2>
            <div class="div-mayor">
                <div class="b1">
                    <a class="button" href="nuevo_emple.php">Nuevo Empleado</a>        
                    <a class="button" href="empleados_panel_baja.php"><svg class="ui-icon" aria-hidden="true" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="m6 6 12 12"/></svg> Empleados Inactivos</a>
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
                    <th>Usuario</th>
                    <th>DNI</th>
                    <th>Nombre</th>
                    <th>Apellido</th>                
                    <th>Teléfono</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($row_registros = mysqli_fetch_assoc($registros)) { ?>
                <tr id="fila_<?php echo $row_registros['id_empleado']; ?>">
                    <td align="center"><?php echo $row_registros['id_empleado']; ?></td>
                    <td><strong><?php echo htmlspecialchars($row_registros['username']); ?></strong></td>
                    <td><?php echo htmlspecialchars($row_registros['dni']); ?></td>
                    <td><?php echo htmlspecialchars($row_registros['nombre']); ?></td>
                    <td><?php echo htmlspecialchars($row_registros['apellido']); ?></td>
                    <td><?php echo htmlspecialchars($row_registros['telefono']); ?></td>
                    <td><?php echo htmlspecialchars($row_registros['email']); ?></td>
                    <td><span style="font-weight:600; color:var(--bakery-primary);"><?php echo htmlspecialchars($row_registros['nombre_rol']); ?></span></td>    
                    <td> 
                        <select onchange="actualizarBaja(<?php echo $row_registros['id_empleado']; ?>, this.value)" style="margin-bottom:0; padding: 4px 8px; font-size:0.85rem;">
                            <option value="1" <?php echo ($row_registros['baja'] == 1) ? 'selected' : ''; ?>>Activo</option>
                            <option value="0" <?php echo ($row_registros['baja'] == 0) ? 'selected' : ''; ?>>No Activo</option>
                        </select>
                    </td>
                    <td align="center">
                        <form action="empleados_modi.php" method="post" style="margin:0;">
                            <input type="hidden" name="id_empleado" value="<?php echo $row_registros['id_empleado']; ?>">
                            <button type="submit" class="table-action" title="Editar empleado" aria-label="Editar empleado">
                                <svg width="22px" height="22px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                </svg>
                            </button>
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
