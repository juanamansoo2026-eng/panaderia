<?php
require_once __DIR__ . '/auth.php';
?>
<?php
    require_once("conexion.php");
    $registros= mysqli_query($conex,"SELECT id_venta, cliente_id, empleado_id, clientes.nombre as c_nombre, clientes.apellido as c_apellido, 
                                    empleados.nombre as e_nombre, empleados.apellido as e_apellido, subtotal, descuento, total, forma_pago, fecha 
    FROM ventas 
    INNER JOIN clientes ON clientes.id_cliente = ventas.cliente_id
    INNER JOIN empleados ON empleados.id_empleado = ventas.empleado_id
    ORDER BY id_venta")
    or die ("problema en la consulta".mysqli_error($conex));

    // FILTROS
    $buscar_cliente = isset($_GET['buscar_cliente']) ? trim($_GET['buscar_cliente']) : '';
    $fecha_desde = isset($_GET['fecha_desde']) ? $_GET['fecha_desde'] : '';
    $fecha_hasta = isset($_GET['fecha_hasta']) ? $_GET['fecha_hasta'] : '';

    // VALIDAR FECHAS
    function validarFecha($fecha) {
        $d = DateTime::createFromFormat('!Y-m-d', $fecha);
        return ($d && $d->format('Y-m-d') === $fecha) ? $fecha : '';
    }

    $fecha_desde = validarFecha($fecha_desde);
    $fecha_hasta = validarFecha($fecha_hasta);

    // CONSULTA INICIAL
    $sql = "SELECT id_venta, cliente_id, empleado_id,
                clientes.nombre AS c_nombre, clientes.apellido AS c_apellido,
                empleados.nombre AS e_nombre, empleados.apellido AS e_apellido,
                subtotal, descuento, total, forma_pago, fecha
            FROM ventas
            INNER JOIN clientes ON clientes.id_cliente = ventas.cliente_id
            INNER JOIN empleados ON empleados.id_empleado = ventas.empleado_id
            WHERE 1=1";

    // FILTROS
    if ($buscar_cliente != '') {
        $sql .= " AND (clientes.nombre LIKE ? OR clientes.apellido LIKE ? OR CONCAT(clientes.nombre, ' ', clientes.apellido) LIKE ?)";
    }

    if ($fecha_desde != '') {
        $sql .= " AND DATE(ventas.fecha) >= ?";
    }

    if ($fecha_hasta != '') {
        $sql .= " AND DATE(ventas.fecha) <= ?";
    }

    // ORDENAR
    $sql .= " ORDER BY id_venta DESC";

    // PREPARAR CONSULTA
    $consulta = mysqli_prepare($conex, $sql);

    $parametros = [];
    $tipos_parametros = '';

    // CARGAR PARÁMETROS
    if ($buscar_cliente != '') {
        $tipos_parametros .= 'sss';
        $parametros = ["$buscar_cliente%", "$buscar_cliente%", "$buscar_cliente%"];
    }

    if ($fecha_desde != '') {
        $tipos_parametros .= 's';
        $parametros[] = $fecha_desde;
    }

    if ($fecha_hasta != '') {
        $tipos_parametros .= 's';
        $parametros[] = $fecha_hasta;
    }

    // VINCULAR PARÁMETROS
    if (!empty($parametros)) {
        mysqli_stmt_bind_param($consulta, $tipos_parametros, ...$parametros);
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
    <title>Historial de Ventas - Panadería M</title>
</head>
<body>
    <?php include("menu.php"); ?>

    <div class="table-container">
        <div style="text-align: center; margin-bottom: 20px;">
            <h2>Historial de Ventas - Panadería M</h2>

            <!-- DIV DE FILTROS -->
            <div>
                <form method="GET" action="ventas_panel.php">
                    <div class="div-filtros">

                        <!-- BUSCAR POR NOMBRE O APELLIDO -->
                        <div>
                            <label for="buscar_cliente">Buscar cliente:</label>
                            <input
                                type="text"
                                name="buscar_cliente"
                                id="buscar_cliente"
                                placeholder="Nombre o apellido..."
                                value="<?php echo htmlspecialchars($buscar_cliente); ?>"
                            >
                        </div>

                        <!-- FILTRAR DESDE FECHA -->
                        <div>
                            <label for="fecha_desde">Desde:</label>
                            <input
                                type="date"
                                name="fecha_desde"
                                id="fecha_desde"
                                value="<?php echo htmlspecialchars($fecha_desde); ?>"
                            >
                        </div>

                        <!-- FILTRAR HASTA FECHA -->
                        <div>
                            <label for="fecha_hasta">Hasta:</label>
                            <input
                                type="date"
                                name="fecha_hasta"
                                id="fecha_hasta"
                                value="<?php echo htmlspecialchars($fecha_hasta); ?>"
                            >
                        </div>

                        <!-- BOTONES BUSCAR Y LIMPIAR -->
                        <div style="padding-top: 30px;">
                            <button type="submit" class="button">
                                BUSCAR
                            </button>
                            <a href="ventas_panel.php" class="button button-outline">
                                LIMPIAR
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <div class="div-mayor">
                <div class="b1">
                    <a class="button" href="ventas_alta.php">Nueva Venta</a>
                    <a class="button" href="turno_caja_panel.php"><svg class="ui-icon" aria-hidden="true" viewBox="0 0 24 24"><path d="M12 21V4m0 7c-3 0-5-2-5-5 3 0 5 2 5 5zm0 4c3 0 5-2 5-5-3 0-5 2-5 5z"/></svg> Abrir Caja</a>
                </div>

                <div class="b1">
                    <a class="button button-outline" href="page1.php">Volver al Menú</a>
                </div>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID Venta</th>
                    <th>Cliente</th>
                    <th>Empleado</th>
                    <th>Subtotal</th>
                    <th>Descuento</th>
                    <th>Total</th>
                    <th>Forma de Pago</th>
                    <th>Fecha</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php while($row_registros= mysqli_fetch_assoc($registros)) { ?>
                <tr align="center">   
                    <td><?php echo htmlspecialchars($row_registros['id_venta']);?></td> 
                    <td><strong><?php echo htmlspecialchars($row_registros['c_nombre'] . " " . $row_registros['c_apellido']);?></strong></td>
                    <td><?php echo htmlspecialchars($row_registros['e_nombre'] . " " . $row_registros['e_apellido']);?></td>
                    <td>$<?php echo number_format($row_registros['subtotal'], 2, ',', '.');?></td>
                    <td>$<?php echo number_format($row_registros['descuento'], 2, ',', '.');?></td>
                    <td><strong style="color: var(--bakery-primary);">$<?php echo number_format($row_registros['total'], 2, ',', '.');?></strong></td>
                    <td><span class="bakery-badge"><?php echo htmlspecialchars($row_registros['forma_pago']);?></span></td>
                    <td><?php echo htmlspecialchars($row_registros['fecha']);?></td>
                    <td>
                        <form action="ventas_pdf.php" method="post" target="_blank">
                            <input type="hidden" name="id_venta" value="<?php echo $row_registros['id_venta'];?>">
                            <input type="image" name="editar" src="img/pdf.png" width="22" title="Imprimir PDF / Detalles">
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