<?php
require_once __DIR__ . '/auth.php';
?>
<?php
    $fecha_actual = date('Y-m-d');
    require_once("conexion.php");
    
    // FILTROS
    $nombre = isset($_GET['nombre']) ? trim($_GET['nombre']) : '';
    $tipo = isset($_GET['tipo']) ? $_GET['tipo'] : 'Todos';
    $orden = isset($_GET['orden']) ? $_GET['orden'] : '';
    $estado = isset($_GET['estado']) ? $_GET['estado'] : 'todos';

    // CONSULTA INICIAL
    if ($orden == 'mayor') {
        $registros = mysqli_query($conex,"SELECT id_producto, nombre, tipo, precio, baja FROM productos ORDER BY baja DESC, precio DESC");
    } elseif ($orden == 'menor') {
        $registros = mysqli_query($conex,"SELECT id_producto, nombre, tipo, precio, baja FROM productos ORDER BY baja DESC, precio ASC");
    } else {
        $registros = mysqli_query($conex,"SELECT id_producto, nombre, tipo, precio, baja FROM productos ORDER BY baja DESC, id_producto ASC");
    }

    if (!$registros) {
        die("Problema en la consulta: " . mysqli_error($conex));
    }

    // OBTENER TIPOS
    $tipos = mysqli_query($conex,"SELECT DISTINCT tipo FROM productos")
    or die("Problema al obtener tipos: " . mysqli_error($conex));

    mysqli_close($conex);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/panaderia-moderna.css">
    <title>Panel de Productos - Panadería M</title>
</head>
<body>
    <?php include("menu.php"); ?>

    <div class="table-container">
        <div style="text-align: center; margin-bottom: 20px;">
            <h2>Panel de Productos - Panadería M</h2>

<!-- DIV DE FILTROS -->
            <div>
                <form method="GET" action="productos_panel.php">
                    <div class="div-filtros">

                        <!-- BUSCAR POR NOMBRE -->
                        <div>
                            <label for="nombre">Nombre del producto:</label>
                            <input
                                type="text"
                                name="nombre"
                                id="nombre"
                                placeholder="Buscar producto..."
                                value="<?php echo htmlspecialchars($nombre); ?>"
                            >
                        </div>

                        <!-- FILTRAR POR TIPO -->
                        <div>
                            <label for="tipo">Tipo:</label>
                            <select name="tipo" id="tipo">
                                <option value="Todos" <?php if($tipo == 'Todos') echo 'selected'; ?>>
                                    Todos
                                </option>
                                <?php while($fila_tipo = mysqli_fetch_assoc($tipos)) { ?>
                                    <option
                                        value="<?php echo htmlspecialchars($fila_tipo['tipo']); ?>"
                                        <?php if($tipo == $fila_tipo['tipo']) echo 'selected'; ?>
                                    >
                                        <?php echo htmlspecialchars($fila_tipo['tipo']); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                        <!-- ORDENAR POR PRECIO -->
                        <div>
                            <label for="orden">Precio:</label>
                            <select name="orden" id="orden">
                                <option value="">Sin ordenar</option>
                                <option value="mayor" <?php if($orden == 'mayor') echo 'selected'; ?>>
                                    Mayor
                                </option>
                                <option value="menor" <?php if($orden == 'menor') echo 'selected'; ?>>
                                    Menor
                                </option>
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
                            <a href="productos_panel.php" class="button button-outline">
                                LIMPIAR
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <div class="div-mayor">
                <div class="b1">
                    <a class="button" href="productos_alta.php">Nuevo Producto</a>
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
                    <th>Tipo</th>
                    <th>Precio Unitario</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php /* while($row_registros= mysqli_fetch_assoc($registros)) { ?>
                <tr align="center">   
                    <td><?php echo htmlspecialchars($row_registros['id_producto']);?></td> 
                    <td><strong><?php echo htmlspecialchars($row_registros['nombre']);?></strong></td>
                    <td><?php echo htmlspecialchars($row_registros['tipo']);?></td>
                    <td><strong>$<?php echo number_format($row_registros['precio'], 2, ',', '.');?></strong></td> 
                    <td> 
                        <select onchange="actualizarBaja(<?php echo $row_registros['id_producto']; ?>, this.value)" style="margin-bottom:0; padding: 4px 8px; font-size:0.85rem;">
                            <option value="1" <?php echo ($row_registros['baja'] == 1) ? 'selected' : ''; ?>>Activo</option>
                            <option value="0" <?php echo ($row_registros['baja'] == 0) ? 'selected' : ''; ?>>No Activo</option>
                        </select>
                    </td> 
                    <td>
                        <form action="productos_modi.php" method="post" style="margin:0;">
                            <input type="hidden" name="id_producto" value="<?php echo $row_registros['id_producto']; ?>">
                            <button type="submit" class="table-action" title="Editar producto" aria-label="Editar producto">
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
                // COMPROBAR BUSCADOR Y FILTROS
                if (
                    ($nombre == '' || stripos($row_registros['nombre'], $nombre) === 0) &&
                    ($tipo == 'Todos' || $row_registros['tipo'] == $tipo) &&
                    ($estado == 'todos' || $row_registros['baja'] == $estado)
                ) {
                ?>
                <tr align="center">
                    <td><?php echo htmlspecialchars($row_registros['id_producto']);?></td>
                    <td><strong><?php echo htmlspecialchars($row_registros['nombre']);?></strong></td>
                    <td><?php echo htmlspecialchars($row_registros['tipo']);?></td>
                    <td><strong>$<?php echo number_format($row_registros['precio'], 2, ',', '.');?></strong></td>
                    <td>
                        <select onchange="actualizarBaja(<?php echo $row_registros['id_producto']; ?>, this.value)" style="margin-bottom:0; padding: 4px 8px; font-size:0.85rem;">
                            <option value="1" <?php echo ($row_registros['baja'] == 1) ? 'selected' : ''; ?>>Activo</option>
                            <option value="0" <?php echo ($row_registros['baja'] == 0) ? 'selected' : ''; ?>>No Activo</option>
                        </select>
                    </td>
                    <td>
                        <form action="productos_modi.php" method="post" style="margin:0;">
                            <input type="hidden" name="id_producto" value="<?php echo $row_registros['id_producto']; ?>">
                            <button type="submit" class="table-action" title="Editar producto" aria-label="Editar producto">
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