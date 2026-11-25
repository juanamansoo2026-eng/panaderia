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

// Recibir filtros
$buscar_cliente = isset($_GET['buscar_cliente'])
    ? trim($_GET['buscar_cliente']) : '';

$fecha_desde = isset($_GET['fecha_desde'])
    ? $_GET['fecha_desde'] : '';

$fecha_hasta = isset($_GET['fecha_hasta'])
    ? $_GET['fecha_hasta'] : '';

// Validar fechas
function validarFecha($fecha) {
    $d = DateTime::createFromFormat('!Y-m-d', $fecha);
    return ($d && $d->format('Y-m-d') === $fecha) ? $fecha : '';
}

$fecha_desde = validarFecha($fecha_desde);
$fecha_hasta = validarFecha($fecha_hasta);

// Construir consulta
$sql = "SELECT 
            id_venta,
            cliente_id,
            empleado_id,
            clientes.nombre AS c_nombre,
            clientes.apellido AS c_apellido,
            empleados.nombre AS e_nombre,
            empleados.apellido AS e_apellido,
            subtotal,
            descuento,
            total,
            forma_pago,
            fecha
        FROM ventas
        INNER JOIN clientes
            ON clientes.id_cliente = ventas.cliente_id
        INNER JOIN empleados
            ON empleados.id_empleado = ventas.empleado_id
        WHERE 1=1";

// Filtro por nombre o apellido del cliente
if ($buscar_cliente !== '') {
    $buscar = mysqli_real_escape_string($conex, $buscar_cliente);

    $sql .= " AND CONCAT(clientes.nombre, ' ', clientes.apellido)
              LIKE '%$buscar%'";
}

// Filtro desde una fecha
if ($fecha_desde !== '') {
    $desde = mysqli_real_escape_string($conex, $fecha_desde);
    $sql .= " AND DATE(ventas.fecha) >= '$desde'";
}

// Filtro hasta una fecha
if ($fecha_hasta !== '') {
    $hasta = mysqli_real_escape_string($conex, $fecha_hasta);
    $sql .= " AND DATE(ventas.fecha) <= '$hasta'";
}

// Ordenar ventas
$sql .= " ORDER BY id_venta DESC";

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
    <title>Historial de Ventas - Panadería M</title>
</head>
<body>
    <?php include("menu.php"); ?>

    <div class="table-container">
        <div style="text-align: center; margin-bottom: 20px;">
            <h2>Historial de Ventas - Panadería M</h2>

<form method="GET" action="" class="div-filtros">

    <div>
        <label for="buscar_cliente">Buscar cliente:</label>
        <input
            type="text"
            name="buscar_cliente"
            id="buscar_cliente"
            placeholder="Nombre o apellido..."
            value="<?= htmlspecialchars($buscar_cliente) ?>"
        >
    </div>

    <div>
        <label for="fecha_desde">Desde:</label>
        <input
            type="date"
            name="fecha_desde"
            id="fecha_desde"
            value="<?= htmlspecialchars($fecha_desde) ?>"
        >
    </div>

    <div>
        <label for="fecha_hasta">Hasta:</label>
        <input
            type="date"
            name="fecha_hasta"
            id="fecha_hasta"
            value="<?= htmlspecialchars($fecha_hasta) ?>"
        >
    </div>

    <div>
        <button type="submit">Buscar / Filtrar</button>
        <a href="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" class="button button-outline">Limpiar</a>
    </div>

</form>

            <div class="div-mayor">
                <div class="b1">
                    <a class="button" href="ventas_alta.php">Nueva Venta</a>
                    <a class="button" href="turno_caja_panel.php"><svg class="ui-icon" aria-hidden="true" viewBox="0 0 24 24"><path d="M12 21V4m0 7c-3 0-5-2-5-5 3 0 5 2 5 5zm0 4c3 0 5-2 5-5-3 0-5 2-5 5z"/></svg> Caja</a>
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
                    <th colspan="2">Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php while($row_registros= mysqli_fetch_assoc($registros)) { ?>
                <tr>   
                    <td><?php echo htmlspecialchars($row_registros['id_venta']);?></td> 
                    <td><strong><?php echo htmlspecialchars($row_registros['c_nombre'] . " " . $row_registros['c_apellido']);?></strong></td>
                    <td><?php echo htmlspecialchars($row_registros['e_nombre'] . " " . $row_registros['e_apellido']);?></td>
                    <td>$<?php echo number_format($row_registros['subtotal'], 2, ',', '.');?></td>
                    <td>$<?php echo number_format($row_registros['descuento'], 2, ',', '.');?></td>
                    <td><strong style="color: var(--bakery-primary);">$<?php echo number_format($row_registros['total'], 2, ',', '.');?></strong></td>
                    <td><span class="bakery-badge"><?php echo htmlspecialchars($row_registros['forma_pago']);?></span></td>
                    <td><?php echo htmlspecialchars($row_registros['fecha']);?></td>
                    <td align="center">
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