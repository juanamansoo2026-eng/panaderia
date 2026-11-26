<?php
require_once __DIR__ . '/auth.php';
?>
<?php
    $fecha_actual = date('Y-m-d');
    require_once("conexion.php");

    // CONSULTA INICIAL
    $registros = mysqli_query($conex,"SELECT id_cliente, nombre, apellido, direccion, telefono, descripcion, baja FROM clientes ORDER BY baja DESC, id_cliente ASC")
    or die ("problema en la consulta".mysqli_error($conex));

    // FILTROS
    $buscar = isset($_GET['buscar']) ? trim($_GET['buscar']) : '';
    $estado = isset($_GET['estado']) ? $_GET['estado'] : 'todos';

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

            <!-- DIV DE FILTROS -->
            <div>
                <form method="GET" action="clientes_panel.php">
                    <div class="div-filtros">
                        
                        <!-- BUSCAR CLIENTE -->
                        <div>
                            <label for="buscar">Buscar cliente:</label>
                            <input type="text" name="buscar" id="buscar" placeholder="Buscar por nombre o apellido..." value="<?php echo htmlspecialchars($buscar); ?>">
                        </div>

                        <!-- FILTRAR ESTADO -->
                        <div>
                            <label for="estado">Estado:</label>
                            <select name="estado" id="estado">
                                <option value="todos" <?php if($estado == 'todos') echo 'selected'; ?>>Todos</option>
                                <option value="1" <?php if($estado == '1') echo 'selected'; ?>>Activos</option>
                                <option value="0" <?php if($estado == '0') echo 'selected'; ?>>Inactivos</option>
                            </select>
                        </div>

                        <!-- BOTONES -->
                        <div style="padding-top: 30px;">
                            <button type="submit" class="button">BUSCAR</button>
                            <a href="clientes_panel.php" class="button button-outline">LIMPIAR</a>
                        </div>
                    </div>
                </form>
            </div>

            <div class="div-mayor">
                <div class="b1">
                    <a class="button" href="clientes_alta.php">Nuevo Cliente</a>
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
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Dirección</th>
                    <th>Teléfono</th>
                    <th>Descripción</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php /* while($row_registros= mysqli_fetch_assoc($registros)) { ?>
                <tr align="center">   
                    <td><?php echo htmlspecialchars($row_registros['id_cliente']);?></td> 
                    <td><strong><?php echo htmlspecialchars($row_registros['nombre']);?></strong></td>
                    <td><?php echo htmlspecialchars($row_registros['apellido']);?></td>
                    <td><?php echo htmlspecialchars($row_registros['direccion']);?></td>
                    <td><?php echo htmlspecialchars($row_registros['telefono']);?></td>
                    <td><?php echo htmlspecialchars($row_registros['descripcion']);?></td>
                    <td> 
                        <select onchange="actualizarBaja(<?php echo $row_registros['id_cliente']; ?>, this.value)" style="margin-bottom:0; padding: 4px 8px; font-size:0.85rem;">
                            <option value="1" <?php echo ($row_registros['baja'] == 1) ? 'selected' : ''; ?>>Activo</option>
                            <option value="0" <?php echo ($row_registros['baja'] == 0) ? 'selected' : ''; ?>>No Activo</option>
                        </select>
                    </td> 
                    <td>
                        <form action="clientes_modi.php" method="post" style="margin:0;">
                            <input type="hidden" name="id_cliente" value="<?php echo $row_registros['id_cliente']; ?>">
                            <button type="submit" class="table-action" title="Editar cliente" aria-label="Editar cliente">
                                <svg width="22px" height="22px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                </svg>
                            </button>
                        </form>
                    </td>
                </tr>
            <?php } */ ?>
            <?php while($row_registros = mysqli_fetch_assoc($registros)) { ?>
                <?php
                // COMPROBAR BUSCADOR Y FILTRO
                if (
                    ($buscar == '' || stripos($row_registros['nombre'], $buscar) === 0 || stripos($row_registros['apellido'], $buscar) === 0 || stripos($row_registros['nombre'].' '.$row_registros['apellido'], $buscar) === 0) &&
                    ($estado == 'todos' || $row_registros['baja'] == $estado)
                ) {
                ?>
                <tr align="center">
                    <td><?php echo htmlspecialchars($row_registros['id_cliente']);?></td>
                    <td><strong><?php echo htmlspecialchars($row_registros['nombre']);?></strong></td>
                    <td><?php echo htmlspecialchars($row_registros['apellido']);?></td>
                    <td><?php echo htmlspecialchars($row_registros['direccion']);?></td>
                    <td><?php echo htmlspecialchars($row_registros['telefono']);?></td>
                    <td><?php echo htmlspecialchars($row_registros['descripcion']);?></td>
                    <td>
                        <select onchange="actualizarBaja(<?php echo $row_registros['id_cliente']; ?>, this.value)" style="margin-bottom:0; padding: 4px 8px; font-size:0.85rem;">
                            <option value="1" <?php echo ($row_registros['baja'] == 1) ? 'selected' : ''; ?>>Activo</option>
                            <option value="0" <?php echo ($row_registros['baja'] == 0) ? 'selected' : ''; ?>>No Activo</option>
                        </select>
                    </td>
                    <td>
                        <form action="clientes_modi.php" method="post" style="margin:0;">
                            <input type="hidden" name="id_cliente" value="<?php echo $row_registros['id_cliente']; ?>">
                            <button type="submit" class="table-action" title="Editar cliente" aria-label="Editar cliente">
                                <svg width="22px" height="22px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                </svg>
                            </button>
                        </form>
                    </td>
                </tr>
                <?php } ?>
            <?php } ?>
            </tbody>
        </table>
    </div>

    <?php include("footer.php"); ?>
</body>
</html>