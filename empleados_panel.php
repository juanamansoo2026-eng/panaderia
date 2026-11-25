<?php
require_once __DIR__ . '/auth.php';
?>
<?php 
    $fecha_actual = date('Y-m-d');

    require_once("conexion.php");
    $registros = mysqli_query($conex, "SELECT empleados.*, roles.nombre_rol
    FROM empleados
    INNER JOIN roles ON roles.id_rol = empleados.id_rol 
    WHERE baja = '1'
    ORDER BY empleados.id_empleado ASC")
        or die("Problema en la consulta: " . mysqli_error($conex));

    // RECIBIR FILTROS
    $nombre = isset($_GET['nombre']) ? trim($_GET['nombre']) : '';
    $apellido = isset($_GET['apellido']) ? trim($_GET['apellido']) : '';
    $rol = isset($_GET['rol']) ? trim($_GET['rol']) : '';

    // CONSULTA DE ROLES
    $roles = mysqli_query($conex, "SELECT id_rol, nombre_rol
                                FROM roles
                                ORDER BY nombre_rol ASC")
        or die("Problema al consultar roles: " . mysqli_error($conex));

    // CONSULTA BASE
    $sql = "SELECT empleados.*, roles.nombre_rol
            FROM empleados
            INNER JOIN roles ON roles.id_rol = empleados.id_rol
            WHERE empleados.baja = '1'";

    // FILTRO POR NOMBRE
    if ($nombre != '') {
        $nombre_sql = mysqli_real_escape_string($conex, $nombre);
        $sql .= " AND empleados.nombre LIKE '$nombre_sql%'";
    }

    // FILTRO POR APELLIDO
    if ($apellido != '') {
        $apellido_sql = mysqli_real_escape_string($conex, $apellido);
        $sql .= " AND empleados.apellido LIKE '$apellido_sql%'";
    }

    // FILTRO POR ROL
    if ($rol != '' && ctype_digit($rol)) {
        $rol_sql = (int)$rol;
        $sql .= " AND empleados.id_rol = $rol_sql";
    }

    // ORDENAR RESULTADOS
    $sql .= " ORDER BY empleados.id_empleado ASC";

    // EJECUTAR CONSULTA
    $registros = mysqli_query($conex, $sql)
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
        // function actualizarBaja(id_empleado, baja) {
        //     $.ajax({
        //         url: 'actualizar_baja.php',
        //         type: 'POST',
        //         data: {
        //             id_empleado: id_empleado,
        //             baja: baja
        //         }
        //     });
        // }
        function actualizarBaja(id_empleado, baja) {
            if (baja == 0 && !confirm('¿Dar de baja a este usuario? Quedará como No Activo y se cargará la fecha de baja.')) {
                location.reload();
                return;
            }
            $.ajax({
                url: 'actualizar_baja.php',
                type: 'POST',
                data: {
                    id_empleado: id_empleado,
                    baja: baja
                },
                success: function(respuesta) {
                    location.reload();
                },
                error: function() {
                    alert('Error al actualizar el estado del empleado.');
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

        <!-- DIV DE FILTROS -->
        <div>
            <form method="GET" action="empleados_panel.php">

                <div class="div-filtros">

                    <!-- BUSCAR POR NOMBRE -->
                    <div>
                        <label for="nombre">Buscar Empleado:</label>
                        <input
                            type="text"
                            name="nombre"
                            id="nombre"
                            placeholder="Buscar nombre..."
                            value="<?php echo htmlspecialchars($nombre); ?>"
                        >
                    </div>

                    <!-- BUSCAR POR APELLIDO -->
                    <div>
                        <label for="apellido">Buscar Empleado:</label>
                        <input
                            type="text"
                            name="apellido"
                            id="apellido"
                            placeholder="Buscar apellido..."
                            value="<?php echo htmlspecialchars($apellido); ?>"
                        >
                    </div>

                    <!-- FILTRAR POR ROL -->
                    <div>
                        <label for="rol">Rol:</label>
                        <select name="rol" id="rol">

                            <option value="">Todos los roles</option>

                            <?php while ($row_rol = mysqli_fetch_assoc($roles)) { ?>

                                <option
                                    value="<?php echo $row_rol['id_rol']; ?>"
                                    <?php echo ($rol == $row_rol['id_rol']) ? 'selected' : ''; ?>
                                >
                                    <?php echo htmlspecialchars($row_rol['nombre_rol']); ?>
                                </option>

                            <?php } ?>

                        </select>
                    </div>

                    <!-- BOTONES -->
                    <div style="padding-bottom: unset; padding-top: 30px;">
                        <button type="submit" class="button">
                            FILTRAR
                        </button>

                        <a href="empleados_panel.php" class="button button-outline">
                            LIMPIAR
                        </a>
                    </div>

                </div>
            </form>
        </div>            
            
            <div class="div-mayor">
                <div class="b1">
                    <a class="button" href="nuevo_emple.php">Alta de Usuario</a>        
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
                    <td align="center"><div style="display:flex; gap:6px; justify-content:center;">
                        <form action="empleados_modi.php" method="post" style="margin:0;">
                            <input type="hidden" name="id_empleado" value="<?php echo $row_registros['id_empleado']; ?>">
                            <button type="submit" class="table-action" title="Modificar correo" aria-label="Modificar correo">
                                <svg width="22px" height="22px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                </svg>
                            </button>
                        </form>
                        <form action="empleados_clave.php" method="post" style="margin:0;">
                            <input type="hidden" name="id_empleado" value="<?php echo $row_registros['id_empleado']; ?>">
                            <button type="submit" class="table-action" title="Restablecer clave" aria-label="Restablecer clave">
                                <svg width="22px" height="22px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="7.5" cy="15.5" r="5.5"></circle>
                                    <path d="m21 2-9.6 9.6"></path>
                                    <path d="m15.5 7.5 3 3L22 7l-3-3"></path>
                                </svg>
                            </button>
                        </form>
                        </div>
                    </td>
                 </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>

    <?php include("footer.php"); ?>
</body>
</html>
