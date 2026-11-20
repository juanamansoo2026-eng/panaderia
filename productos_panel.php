<?php
require_once __DIR__ . '/auth.php';
?>
<?php
    $fecha_actual = date('Y-m-d');
    require_once("conexion.php");
    $registros= mysqli_query($conex,"SELECT id_producto, nombre, tipo, precio, baja FROM productos")
    or die ("problema en la consulta".mysqli_error($conex)); 
    


    // Filtros
    $nombre = isset($_GET['nombre']) ? trim($_GET['nombre']) : '';
    $tipo = isset($_GET['tipo']) ? $_GET['tipo'] : 'Todos';
    $orden = isset($_GET['orden']) ? $_GET['orden'] : '';

    // Consulta inicial
    $sql = "SELECT id_producto, nombre, tipo, precio, baja FROM productos WHERE 1=1";

    // Filtro por nombre
    if ($nombre != '') {
        $sql .= " AND nombre LIKE ?";
    }

    // Filtro por tipo
    if ($tipo != 'Todos' && $tipo != '') {
        $sql .= " AND tipo = ?";
    }

    // Orden por precio
    if ($orden == 'mayor') {
        $sql .= " ORDER BY precio DESC";
    } elseif ($orden == 'menor') {
        $sql .= " ORDER BY precio ASC";
    }

    // Preparar consulta segura
    $consulta = mysqli_prepare($conex, $sql);

    $parametros = [];
    $tipos_parametros = '';

    if ($nombre != '') {
        $tipos_parametros .= 's';
        $parametros[] = "%$nombre%";
    }

    if ($tipo != 'Todos' && $tipo != '') {
        $tipos_parametros .= 's';
        $parametros[] = $tipo;
    }

    if (!empty($parametros)) {
        mysqli_stmt_bind_param(
            $consulta,
            $tipos_parametros,
            ...$parametros
        );
    }

    mysqli_stmt_execute($consulta);
    $registros = mysqli_stmt_get_result($consulta);

    // Obtener los tipos disponibles
    $tipos = mysqli_query(
        $conex,
        "SELECT DISTINCT tipo FROM productos ORDER BY tipo ASC"
    ) or die("Problema al obtener tipos: " . mysqli_error($conex));

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

        <form method="GET" href="productos_panel.php">

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
                        <option value="Todos"
                            <?php if($tipo == 'Todos') echo 'selected'; ?>>
                            Todos
                        </option>

                        <?php while($fila_tipo = mysqli_fetch_assoc($tipos)) { ?>
                            <option
                                value="<?php echo htmlspecialchars($fila_tipo['tipo']); ?>"
                                <?php if($tipo == $fila_tipo['tipo']) echo 'selected'; ?>>
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

                        <option value="mayor"
                            <?php if($orden == 'mayor') echo 'selected'; ?>>
                            Mayor
                        </option>

                        <option value="menor"
                            <?php if($orden == 'menor') echo 'selected'; ?>>
                            Menor
                        </option>
                    </select>
                </div>

                <!-- BOTONES FILTRAR Y LIMPIAR -->
                <div>
                    <button type="submit" class="button">
                        FILTRAR
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
                    <th>Baja</th>
                    <th colspan="2">Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php while($row_registros= mysqli_fetch_assoc($registros)) { ?>
                <tr>   
                    <td><?php echo htmlspecialchars($row_registros['id_producto']);?></td> 
                    <td><strong><?php echo htmlspecialchars($row_registros['nombre']);?></strong></td>
                    <td><?php echo htmlspecialchars($row_registros['tipo']);?></td>
                    <td><strong>$<?php echo number_format($row_registros['precio'], 2, ',', '.');?></strong></td> 
                    <td style="text-align: center"><?php echo ($row_registros['baja']);?></td>
                    <td align="center">
                        <form action="productos_modi.php" method="post">
                            <input type="hidden" name="id_producto" value="<?php echo $row_registros['id_producto'];?>">
                            <input type="image" name="editar" src="img/lapiz2.png" width="22" title="Editar">
                        </form>
                    </td>
                    <!-- <td align="center">
                        <form action="productos_baja.php" method="post">
                            <input type="hidden" name="id_producto" value="<?php echo $row_registros['id_producto'];?>">
                            <input type="hidden" name="nombre_prod" value="<?php echo htmlspecialchars($row_registros['nombre']);?>">
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