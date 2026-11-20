<?php
    $fecha_actual = date('Y-m-d');
    require_once("conexion.php");
    $registros= mysqli_query($conex,"SELECT id_cliente, nombre, apellido, direccion, telefono, descripcion, baja FROM clientes ")
    or die ("problema en la consulta".mysqli_error($conex)); 

    // FILTROS
    $nombre = isset($_GET['nombre']) ? trim($_GET['nombre']) : '';
    $apellido = isset($_GET['apellido']) ? trim($_GET['apellido']) : '';
    $orden = isset($_GET['orden']) ? $_GET['orden'] : '';

    // CONSULTA INICIAL
    $sql = "SELECT id_cliente, nombre, apellido, direccion, telefono, descripcion, baja
            FROM clientes
            WHERE 1=1";

    // FILTRO POR NOMBRE
    if ($nombre != '') {
        $sql .= " AND nombre LIKE ?";
    }

    // FILTRO POR APELLIDO
    if ($apellido != '') {
        $sql .= " AND apellido LIKE ?";
    }

    // ORDENAR POR NOMBRE O APELLIDO
    if ($orden == 'nombre_asc') {
        $sql .= " ORDER BY nombre ASC";

    } elseif ($orden == 'nombre_desc') {
        $sql .= " ORDER BY nombre DESC";

    } elseif ($orden == 'apellido_asc') {
        $sql .= " ORDER BY apellido ASC";

    } elseif ($orden == 'apellido_desc') {
        $sql .= " ORDER BY apellido DESC";
    }

    // PREPARAR CONSULTA SEGURA
    $consulta = mysqli_prepare($conex, $sql);

    $parametros = [];
    $tipos_parametros = '';

    // PARÁMETRO NOMBRE
    if ($nombre != '') {
        $tipos_parametros .= 's';
        $parametros[] = "$nombre%";
    }

    // PARÁMETRO APELLIDO
    if ($apellido != '') {
        $tipos_parametros .= 's';
        $parametros[] = "$apellido%";
    }

    // VINCULAR PARÁMETROS
    if (!empty($parametros)) {
        mysqli_stmt_bind_param(
            $consulta,
            $tipos_parametros,
            ...$parametros
        );
    }

    // EJECUTAR CONSULTA
    mysqli_stmt_execute($consulta);
    $registros = mysqli_stmt_get_result($consulta);
    
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

                        <!-- BUSCAR POR NOMBRE -->
                        <div>
                            <label for="nombre">Nombre:</label>

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
                            <label for="apellido">Apellido:</label>

                            <input
                                type="text"
                                name="apellido"
                                id="apellido"
                                placeholder="Buscar apellido..."
                                value="<?php echo htmlspecialchars($apellido); ?>"
                            >
                        </div>

                        <!-- ORDENAR POR NOMBRE O APELLIDO -->
                        <div>
                            <label for="orden">Ordenar por:</label>

                            <select name="orden" id="orden">

                                <option value=""
                                    <?php if($orden == '') echo 'selected'; ?>>
                                    Sin ordenar
                                </option>

                                <option value="nombre_asc"
                                    <?php if($orden == 'nombre_asc') echo 'selected'; ?>>
                                    Nombre (A-Z)
                                </option>

                                <option value="nombre_desc"
                                    <?php if($orden == 'nombre_desc') echo 'selected'; ?>>
                                    Nombre (Z-A)
                                </option>

                                <option value="apellido_asc"
                                    <?php if($orden == 'apellido_asc') echo 'selected'; ?>>
                                    Apellido (A-Z)
                                </option>

                                <option value="apellido_desc"
                                    <?php if($orden == 'apellido_desc') echo 'selected'; ?>>
                                    Apellido (Z-A)
                                </option>

                            </select>
                        </div>

                        <!-- BOTONES FILTRAR Y LIMPIAR -->
                        <div>

                            <button type="submit" class="button">
                                FILTRAR
                            </button>

                            <a href="clientes_panel.php" class="button button-outline">
                                LIMPIAR
                            </a>

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
                    <th>Acciones</th>
                    <th>Baja</th>
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
                            <button type="submit" class="table-action" title="Editar cliente" aria-label="Editar cliente">
                                <svg width="22px" height="22px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                </svg>
                            </button>
                        </form>
                    </td>
                    <td align="center"><?php echo ($row_registros['baja']);?></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>

    <?php include("footer.php"); ?>
</body>
</html>