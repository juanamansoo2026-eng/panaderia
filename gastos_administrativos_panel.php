<?php
require_once __DIR__ . '/auth.php';
?>
<?php
    require_once("conexion.php");

    // CONSULTA INICIAL
    $registros = mysqli_query($conex, "SELECT id_gasto, turno_id, turno_caja.turno,
    gastos_administrativos.descripcion, categoria_gasto_id, nombre_categoria,
    monto, empleado, gastos_administrativos.fecha, gastos_administrativos.baja
    FROM gastos_administrativos
    INNER JOIN turno_caja ON turno_caja.id_turno = gastos_administrativos.turno_id
    INNER JOIN categoria_gasto ON categoria_gasto.id_categoria_gasto = gastos_administrativos.categoria_gasto_id
    ORDER BY gastos_administrativos.baja DESC, gastos_administrativos.fecha DESC")
    or die("Problema en la consulta " . mysqli_error($conex));

    // FILTROS
    $categoria = isset($_GET['categoria']) ? trim($_GET['categoria']) : '';
    $fecha_desde = isset($_GET['fecha_desde']) ? trim($_GET['fecha_desde']) : '';
    $fecha_hasta = isset($_GET['fecha_hasta']) ? trim($_GET['fecha_hasta']) : '';
    $estado = isset($_GET['estado']) ? $_GET['estado'] : 'todos';

    // OBTENER CATEGORÍAS
    $categorias = mysqli_query($conex,
        "SELECT id_categoria_gasto, nombre_categoria
        FROM categoria_gasto
        ORDER BY nombre_categoria ASC"
    ) or die("Problema en la consulta: " . mysqli_error($conex));

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

            <!-- DIV DE FILTROS -->
            <div>
                <form method="GET" action="gastos_administrativos_panel.php">

                    <div class="div-filtros">

                        <!-- FILTRAR POR CATEGORIA -->
                        <div>
                            <label for="categoria">Categoría:</label>

                            <select name="categoria" id="categoria">

                                <option value="" <?php if($categoria == '') echo 'selected'; ?>>
                                    Todas las categorías
                                </option>

                                <?php while($row_categoria = mysqli_fetch_assoc($categorias)) { ?>

                                    <option
                                        value="<?php echo htmlspecialchars($row_categoria['id_categoria_gasto']); ?>"
                                        <?php if($categoria == $row_categoria['id_categoria_gasto']) echo 'selected'; ?>
                                    >
                                        <?php echo htmlspecialchars($row_categoria['nombre_categoria']); ?>
                                    </option>

                                <?php } ?>

                            </select>
                        </div>

                        <!-- FECHA DESDE -->
                        <div>
                            <label for="fecha_desde">Fecha desde:</label>

                            <input
                                type="date"
                                name="fecha_desde"
                                id="fecha_desde"
                                value="<?php echo htmlspecialchars($fecha_desde); ?>"
                            >
                        </div>

                        <!-- FECHA HASTA -->
                        <div>
                            <label for="fecha_hasta">Fecha hasta:</label>

                            <input
                                type="date"
                                name="fecha_hasta"
                                id="fecha_hasta"
                                value="<?php echo htmlspecialchars($fecha_hasta); ?>"
                            >
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

                            <a href="gastos_administrativos_panel.php" class="button button-outline">
                                LIMPIAR
                            </a>

                        </div>

                    </div>
                </form>
            </div>

            <div class="div-mayor">
                <div class="b1">
                    <a class="button" href="gastos_administrativos_alta.php">Nuevo Gasto</a>
                    <a class="button" href="categoria_gasto_panel.php"><svg class="ui-icon" aria-hidden="true" viewBox="0 0 24 24"><path d="M20 13 13 20 4 11V4h7z"/><circle cx="8" cy="8" r="1"/></svg> Categorías de Gastos</a>
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
                    <th>Estado</th>
                    <th>Acciones</th>                    
                </tr>
            </thead>
            <tbody>
            <?php /* while($row_registros= mysqli_fetch_assoc($registros)) { ?>
                <tr align="center">   
                    <td><?php echo htmlspecialchars($row_registros['id_gasto']);?></td> 
                    <td><strong><?php echo htmlspecialchars($row_registros['descripcion']);?></strong></td>
                    <td><?php echo htmlspecialchars($row_registros['nombre_categoria']);?></td>
                    <td><strong style="color:var(--bakery-danger);">$<?php echo number_format($row_registros['monto'], 2, ',', '.');?></strong></td>
                    <td><?php echo htmlspecialchars($row_registros['turno']);?></td>
                    <td><?php echo htmlspecialchars($row_registros['empleado']);?></td>
                    <td><?php echo htmlspecialchars($row_registros['fecha']);?></td>
                    <td> 
                        <select onchange="actualizarBaja(<?php echo $row_registros['id_gasto']; ?>, this.value)" style="margin-bottom:0; padding: 4px 8px; font-size:0.85rem;">
                            <option value="1" <?php echo ($row_registros['baja'] == 1) ? 'selected' : ''; ?>>Activo</option>
                            <option value="0" <?php echo ($row_registros['baja'] == 0) ? 'selected' : ''; ?>>No Activo</option>
                        </select>
                    </td> 
                    <td>
                        <form action="gastos_administrativos_modi.php" method="post" style="margin:0;">
                            <input type="hidden" name="id_gasto" value="<?php echo $row_registros['id_gasto']; ?>">
                            <button type="submit" class="table-action" title="Editar gasto" aria-label="Editar gasto">
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
                    ($categoria == '' || $row_registros['categoria_gasto_id'] == $categoria) &&
                    ($fecha_desde == '' || $row_registros['fecha'] >= $fecha_desde) &&
                    ($fecha_hasta == '' || date('Y-m-d', strtotime($row_registros['fecha'])) <= $fecha_hasta) &&
                    ($estado == 'todos' || $row_registros['baja'] == $estado)
                ) {
                ?>

                <tr align="center">

                    <td>
                        <?php echo htmlspecialchars($row_registros['id_gasto']); ?>
                    </td>

                    <td>
                        <strong>
                            <?php echo htmlspecialchars($row_registros['descripcion']); ?>
                        </strong>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($row_registros['nombre_categoria']); ?>
                    </td>

                    <td>
                        <strong style="color:var(--bakery-danger);">
                            $<?php echo number_format($row_registros['monto'], 2, ',', '.'); ?>
                        </strong>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($row_registros['turno']); ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($row_registros['empleado']); ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($row_registros['fecha']); ?>
                    </td>

                    <td>

                        <select onchange="actualizarBaja(<?php echo $row_registros['id_gasto']; ?>, this.value)"
                            style="margin-bottom:0; padding: 4px 8px; font-size:0.85rem;">

                            <option value="1" <?php echo ($row_registros['baja'] == 1) ? 'selected' : ''; ?>>
                                Activo
                            </option>

                            <option value="0" <?php echo ($row_registros['baja'] == 0) ? 'selected' : ''; ?>>
                                No Activo
                            </option>

                        </select>

                    </td>

                    <td>

                        <form action="gastos_administrativos_modi.php" method="post" style="margin:0;">

                            <input type="hidden" name="id_gasto"
                                value="<?php echo $row_registros['id_gasto']; ?>">

                            <button type="submit" class="table-action"
                                title="Editar gasto" aria-label="Editar gasto">

                                <svg width="22px" height="22px" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">

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