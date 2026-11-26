<?php
require_once __DIR__ . '/auth.php';
?>
<?php
    $fecha_actual = date('Y-m-d');
    require_once("conexion.php");

    // CONSULTA INICIAL
    $registros = mysqli_query($conex, "SELECT *
    FROM proveedor
    ORDER BY baja DESC, nombre ASC")
    or die("Problema en la consulta " . mysqli_error($conex));

    // FILTROS
    $buscar = isset($_GET['buscar']) ? trim($_GET['buscar']) : '';
    $provincia = isset($_GET['provincia']) ? trim($_GET['provincia']) : '';
    $estado = isset($_GET['estado']) ? $_GET['estado'] : 'todos';

    // OBTENER PROVINCIAS
    $sql_provincias = "SELECT DISTINCT provincia
    FROM proveedor
    WHERE provincia IS NOT NULL AND provincia != ''
    ORDER BY provincia ASC";

    $provincias = mysqli_query($conex, $sql_provincias)
    or die("Problema al consultar provincias: " . mysqli_error($conex));

    mysqli_close($conex);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/panaderia-moderna.css">
    <title>Panel de Proveedores - Panadería M</title>
</head>
<body>
    <?php include("menu.php"); ?>

    <div class="table-container">
        <div style="text-align: center; margin-bottom: 20px;">
            <h2>Lista de Proveedores - Panadería M</h2>

            <!-- DIV DE FILTROS -->
            <div>
                <form method="GET" action="proveedores_panel.php">
                    <div class="div-filtros">

                        <!-- BUSCAR PROVEEDOR -->
                        <div>
                            <label for="buscar">Buscar proveedor:</label>
                            <input
                                type="text"
                                name="buscar"
                                id="buscar"
                                placeholder="Nombre, apellido o cuil..."
                                value="<?php echo htmlspecialchars($buscar); ?>"
                            >
                        </div>

                        <!-- FILTRAR POR PROVINCIA -->
                        <div>
                            <label for="provincia">Provincia:</label>
                            <select name="provincia" id="provincia">
                                <option value="">
                                    Todas las provincias
                                </option>
                                <?php while($row_provincia = mysqli_fetch_assoc($provincias)) { ?>

                                    <option
                                        value="<?php echo htmlspecialchars($row_provincia['provincia']); ?>"
                                        <?php if($provincia == $row_provincia['provincia']) echo 'selected'; ?>
                                    >
                                        <?php echo htmlspecialchars($row_provincia['provincia']); ?>
                                    </option>

                                <?php } ?>
                            </select>
                        </div>

                        <!-- FILTRAR ESTADO -->
                        <div>
                            <label for="estado">Estado:</label>
                            <select name="estado" id="estado">
                                <option value="todos" <?php if($estado == 'todos') echo 'selected'; ?>>
                                    Todos
                                </option>
                                <option value="1" <?php if($estado == '1') echo 'selected'; ?>>
                                    Activos
                                </option>
                                <option value="0" <?php if($estado == '0') echo 'selected'; ?>>
                                    Inactivos
                                </option>
                            </select>
                        </div>

                        <!-- BOTONES -->
                        <div style="padding-top: 30px;">
                            <button type="submit" class="button">
                                BUSCAR
                            </button>
                            <a href="proveedores_panel.php" class="button button-outline">
                                LIMPIAR
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <div class="div-mayor">
                <div class="b1">
                    <a class="button" href="proveedores_alta.php">Nuevo Proveedor</a>
                    <a class="button" href="pedido_compra_panel.php"><svg class="ui-icon" aria-hidden="true" viewBox="0 0 24 24"><path d="m3 7 9-4 9 4v10l-9 4-9-4zM3 7l9 4 9-4m-9 4v10"/></svg> Compras a Proveedores</a>
                    <a class="button" href="materia_prima_panel.php"><svg class="ui-icon" aria-hidden="true" viewBox="0 0 24 24"><path d="M12 21V4m0 7c-3 0-5-2-5-5 3 0 5 2 5 5zm0 4c3 0 5-2 5-5-3 0-5 2-5 5z"/></svg> Materia Prima</a>
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
                    <th>Teléfono</th>
                    <th>Razón Social</th>
                    <th>Cuil</th>
                    <th>Provincia - Localidad</th>
                    <th>¿Qué Vende?</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php /* while($row_registros= mysqli_fetch_assoc($registros)) { ?>
                <tr align="center">   
                    <td><?php echo htmlspecialchars($row_registros['id_proveedor']);?></td> 
                    <td><strong><?php echo htmlspecialchars($row_registros['nombre']);?></strong></td>
                    <td><?php echo htmlspecialchars($row_registros['apellido']);?></td>
                    <td><?php echo htmlspecialchars($row_registros['telefono']);?></td>
                    <td><?php echo htmlspecialchars($row_registros['razon_social']);?></td> 
                    <td><?php echo htmlspecialchars($row_registros['cuil']);?></td> 
                    <td><?php echo htmlspecialchars($row_registros['provincia']);?></td> 
                    <td><?php echo htmlspecialchars($row_registros['descripcion']);?></td>
                    <td> 
                        <select onchange="actualizarBaja(<?php echo $row_registros['id_proveedor']; ?>, this.value)" style="margin-bottom:0; padding: 4px 8px; font-size:0.85rem;">
                            <option value="1" <?php echo ($row_registros['baja'] == 1) ? 'selected' : ''; ?>>Activo</option>
                            <option value="0" <?php echo ($row_registros['baja'] == 0) ? 'selected' : ''; ?>>No Activo</option>
                        </select>
                    </td> 
                    <td>
                        <form action="proveedores_modi.php" method="post" style="margin:0;">
                            <input type="hidden" name="id_proveedor" value="<?php echo $row_registros['id_proveedor']; ?>">
                            <button type="submit" class="table-action" title="Editar proveedor" aria-label="Editar proveedor">
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
                // COMPROBAR LOS FILTROS
                if (
                    ($buscar == '' ||
                    stripos($row_registros['nombre'], $buscar) === 0 ||
                    stripos($row_registros['apellido'], $buscar) === 0 ||
                    stripos($row_registros['cuil'], $buscar) === 0 ||
                    stripos($row_registros['nombre'] . ' ' . $row_registros['apellido'], $buscar) === 0) &&
                    ($provincia == '' || $row_registros['provincia'] == $provincia) &&
                    ($estado == 'todos' || $row_registros['baja'] == $estado)
                ) {
                ?>
                <tr align="center">
                    <td><?php echo htmlspecialchars($row_registros['id_proveedor']); ?></td>
                    <td><strong><?php echo htmlspecialchars($row_registros['nombre']); ?></strong></td>
                    <td><?php echo htmlspecialchars($row_registros['apellido']); ?></td>
                    <td><?php echo htmlspecialchars($row_registros['telefono']); ?></td>
                    <td><?php echo htmlspecialchars($row_registros['razon_social']); ?></td>
                    <td><?php echo htmlspecialchars($row_registros['cuil']); ?></td>
                    <td><?php echo htmlspecialchars($row_registros['provincia']); ?></td>
                    <td><?php echo htmlspecialchars($row_registros['descripcion']); ?></td>
                    <td>
                        <select onchange="actualizarBaja(<?php echo $row_registros['id_proveedor']; ?>, this.value)" style="margin-bottom:0; padding: 4px 8px; font-size:0.85rem;">
                            <option value="1" <?php echo ($row_registros['baja'] == 1) ? 'selected' : ''; ?>>
                                Activo
                            </option>
                            <option value="0" <?php echo ($row_registros['baja'] == 0) ? 'selected' : ''; ?>>
                                No Activo
                            </option>
                        </select>
                    </td>
                    <td>
                        <form action="proveedores_modi.php" method="post" style="margin:0;">
                            <input type="hidden" name="id_proveedor" value="<?php echo $row_registros['id_proveedor']; ?>">
                            <button type="submit" class="table-action" title="Editar proveedor" aria-label="Editar proveedor">
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