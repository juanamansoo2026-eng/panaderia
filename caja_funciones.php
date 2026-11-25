<?php
// =====================================================================
// FUNCIONES DE CAJA - Panadería M
// Hay UNA sola caja abierta a la vez. En ella entran las ventas (ingresos)
// y salen las compras a proveedores y los gastos (egresos).
// =====================================================================

// UNA CAJA POR SU NÚMERO (o null si no existe)
function cajaPorId($conex, $id_turno) {
    $consulta = mysqli_prepare($conex,
        "SELECT turno_caja.*, empleados.nombre, empleados.apellido
         FROM turno_caja
         INNER JOIN empleados ON empleados.id_empleado = turno_caja.empleado_id
         WHERE turno_caja.id_turno = ?");
    mysqli_stmt_bind_param($consulta, 'i', $id_turno);
    mysqli_stmt_execute($consulta);
    return mysqli_fetch_assoc(mysqli_stmt_get_result($consulta));
}

// CAJA A LA QUE PERTENECE UN MOVIMIENTO (venta, compra o gasto). Devuelve null si no tiene caja.
// Se usa para no dejar modificar ni borrar movimientos de una caja que ya se cerró.
function cajaDelMovimiento($conex, $tabla, $id) {
    $campos = [
        'ventas' => 'id_venta',
        'pedido_compra' => 'id_pedido_compra',
        'gastos_administrativos' => 'id_gasto',
    ];
    if (!isset($campos[$tabla])) {
        return null;
    }
    $consulta = mysqli_prepare($conex,
        "SELECT turno_caja.id_turno, turno_caja.estado
         FROM $tabla
         INNER JOIN turno_caja ON turno_caja.id_turno = $tabla.turno_id
         WHERE $tabla.{$campos[$tabla]} = ?");
    mysqli_stmt_bind_param($consulta, 'i', $id);
    mysqli_stmt_execute($consulta);
    return mysqli_fetch_assoc(mysqli_stmt_get_result($consulta));
}

// PÁGINA DE AVISO CUANDO NO SE PUEDE HACER ALGO (mismo estilo que el resto del sistema)
function paginaAviso($titulo, $mensaje, $volver, $texto_volver = 'Volver') {
    ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($titulo); ?> - Panadería M</title>
    <link rel="stylesheet" href="css/panaderia-moderna.css">
</head>
<body>
    <?php include("menu.php"); ?>
    <main class="container form-container resultado-container">
        <h2><?php echo htmlspecialchars($titulo); ?></h2>
        <div class="mensaje error" role="alert"><?php echo $mensaje; ?></div>
        <div class="resultado-acciones">
            <a class="button button-outline" href="<?php echo htmlspecialchars($volver); ?>"><?php echo htmlspecialchars($texto_volver); ?></a>
        </div>
    </main>
    <?php include("footer.php"); ?>
</body>
</html>
    <?php
    exit();
}

// CAJA ABIERTA (o null si no hay)
function cajaAbierta($conex) {
    $sql = "SELECT turno_caja.*, empleados.nombre, empleados.apellido
            FROM turno_caja
            INNER JOIN empleados ON empleados.id_empleado = turno_caja.empleado_id
            WHERE turno_caja.estado = 'ABIERTO'
            ORDER BY turno_caja.id_turno DESC
            LIMIT 1";
    return mysqli_fetch_assoc(mysqli_query($conex, $sql));
}

// TOTALES DE UNA CAJA (lo que entra y sale en efectivo, para el arqueo)
function totalesCaja($conex, $caja) {
    $id = (int)$caja['id_turno'];
    $t = [];

    // INGRESOS: VENTAS COBRADAS EN ESTA CAJA
    $fila = mysqli_fetch_assoc(mysqli_query($conex,
        "SELECT COUNT(*) AS cantidad, IFNULL(SUM(total), 0) AS total,
                IFNULL(SUM(CASE WHEN forma_pago = 'efectivo' THEN total ELSE 0 END), 0) AS efectivo
         FROM ventas WHERE turno_id = $id"));
    $t['cantidad_ventas'] = $fila['cantidad'];
    $t['ventas_total'] = $fila['total'];
    $t['ventas_efectivo'] = $fila['efectivo'];

    // EGRESOS: COMPRAS A PROVEEDORES Y GASTOS PAGADOS CON ESTA CAJA
    $fila = mysqli_fetch_assoc(mysqli_query($conex,
        "SELECT COUNT(*) AS cantidad, IFNULL(SUM(total), 0) AS total FROM pedido_compra WHERE turno_id = $id"));
    $t['cantidad_compras'] = $fila['cantidad'];
    $t['compras_total'] = $fila['total'];

    $fila = mysqli_fetch_assoc(mysqli_query($conex,
        "SELECT COUNT(*) AS cantidad, IFNULL(SUM(monto), 0) AS total FROM gastos_administrativos WHERE turno_id = $id"));
    $t['cantidad_gastos'] = $fila['cantidad'];
    $t['gastos_total'] = $fila['total'];

    $t['egresos_total'] = $t['compras_total'] + $t['gastos_total'];

    // EFECTIVO QUE DEBERÍA HABER EN LA CAJA
    $t['esperado'] = $caja['monto_inicial'] + $t['ventas_efectivo'] - $t['egresos_total'];
    return $t;
}

// EMPLEADO QUE INICIÓ SESIÓN
function empleadoSesion($conex) {
    $consulta = mysqli_prepare($conex, "SELECT id_empleado, nombre, apellido FROM empleados WHERE username = ?");
    mysqli_stmt_bind_param($consulta, 's', $_SESSION['username']);
    mysqli_stmt_execute($consulta);
    return mysqli_fetch_assoc(mysqli_stmt_get_result($consulta));
}

// RANGO DE FECHAS SEGÚN EL PERÍODO ELEGIDO (hoy, ayer, 7 días, mes o rango)
function rangoPeriodo($periodo, $desde, $hasta) {
    $hoy = date('Y-m-d');
    switch ($periodo) {
        case 'ayer':
            $d = date('Y-m-d', strtotime('-1 day'));
            return [$d, $d];
        case '7dias':
            return [date('Y-m-d', strtotime('-6 days')), $hoy];
        case 'mes':
            return [date('Y-m-01'), $hoy];
        case 'rango':
            $valida = function ($f) {
                $d = DateTime::createFromFormat('!Y-m-d', $f);
                return ($d && $d->format('Y-m-d') === $f) ? $f : '';
            };
            $desde = $valida($desde);
            $hasta = $valida($hasta);
            return [$desde != '' ? $desde : $hoy, $hasta != '' ? $hasta : $hoy];
        default:
            return [$hoy, $hoy];
    }
}

// FORMATO DE PLATA
function pesos($valor) {
    return '$' . number_format((float)$valor, 2, ',', '.');
}

// FECHA PARA MOSTRAR (las vacías vienen como NULL o 0000-00-00)
function mostrarFecha($fecha, $con_hora = true) {
    if (!$fecha || substr($fecha, 0, 4) == '0000') {
        return '-';
    }
    return date($con_hora ? 'd/m/Y H:i' : 'd/m/Y', strtotime($fecha));
}
