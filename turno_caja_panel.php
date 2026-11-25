<?php
require_once __DIR__ . '/auth.php';
?>
<?php
require_once("conexion.php");
require_once("caja_funciones.php");

    // VALIDAR FECHAS (formato AAAA-MM-DD)
    function validarFecha($fecha) {
        $d = DateTime::createFromFormat('!Y-m-d', $fecha);
        return ($d && $d->format('Y-m-d') === $fecha) ? $fecha : '';
    }

    // FILTROS
    $empleado = isset($_GET['empleado']) ? trim($_GET['empleado']) : '';
    $estado = isset($_GET['estado']) ? trim($_GET['estado']) : '';
    $fecha_desde = isset($_GET['fecha_desde']) ? validarFecha(trim($_GET['fecha_desde'])) : '';
    $fecha_hasta = isset($_GET['fecha_hasta']) ? validarFecha(trim($_GET['fecha_hasta'])) : '';
    $orden = isset($_GET['orden']) ? $_GET['orden'] : '';

    // CAPITAL TOTAL DEL NEGOCIO (registros financieros: ingresos − egresos)
    $capital = mysqli_fetch_assoc(mysqli_query($conex,
        "SELECT IFNULL(SUM(CASE WHEN tipo = 'INGRESO' THEN monto ELSE 0 END), 0) AS ingresos,
                IFNULL(SUM(CASE WHEN tipo = 'EGRESO' THEN monto ELSE 0 END), 0) AS egresos
         FROM registros_financieros"));
    $capital_total = $capital['ingresos'] - $capital['egresos'];

    // CAJA ABIERTA EN ESTE MOMENTO Y SU EFECTIVO
    $caja_abierta = cajaAbierta($conex);
    $t_caja = $caja_abierta ? totalesCaja($conex, $caja_abierta) : null;

    // OTRAS CAJAS QUE QUEDARON ABIERTAS (de cuando había dos cajas): hay que cerrarlas
    $otras_abiertas = mysqli_fetch_all(mysqli_query($conex,
        "SELECT id_turno FROM turno_caja WHERE estado = 'ABIERTO'" . ($caja_abierta ? " AND id_turno <> " . (int)$caja_abierta['id_turno'] : "")), MYSQLI_ASSOC);

    // CONSULTA INICIAL (las ventas en efectivo, los gastos y las compras de cada turno se usan para calcular la diferencia)
    $sql = "SELECT id_turno, empleado_id, empleados.nombre, empleados.apellido, turno,
                   turno_caja.fecha_apertura, turno_caja.fecha_cierre, monto_inicial, total_recaudado, monto_final, estado,
                   (SELECT IFNULL(SUM(ventas.total), 0) FROM ventas
                     WHERE ventas.turno_id = turno_caja.id_turno AND ventas.forma_pago = 'efectivo') AS ventas_efectivo,
                   (SELECT IFNULL(SUM(ventas.total), 0) FROM ventas
                     WHERE ventas.turno_id = turno_caja.id_turno) AS ventas_total,
                   (SELECT IFNULL(SUM(gastos_administrativos.monto), 0) FROM gastos_administrativos
                     WHERE gastos_administrativos.turno_id = turno_caja.id_turno) AS total_gastos,
                   (SELECT IFNULL(SUM(pedido_compra.total), 0) FROM pedido_compra
                     WHERE pedido_compra.turno_id = turno_caja.id_turno) AS total_compras
            FROM turno_caja
            INNER JOIN empleados ON empleados.id_empleado = turno_caja.empleado_id
            WHERE 1=1";

    $parametros = [];
    $tipos_parametros = '';

    // FILTRO POR EMPLEADO
    if ($empleado != '' && ctype_digit($empleado)) {
        $sql .= " AND turno_caja.empleado_id = ?";
        $tipos_parametros .= 'i';
        $parametros[] = (int)$empleado;
    }

    // FILTRO POR ESTADO (ABIERTO / CERRADO)
    if ($estado == 'ABIERTO' || $estado == 'CERRADO') {
        $sql .= " AND estado = ?";
        $tipos_parametros .= 's';
        $parametros[] = $estado;
    }

    // FILTRO POR FECHA DE APERTURA DESDE
    if ($fecha_desde != '') {
        $sql .= " AND DATE(turno_caja.fecha_apertura) >= ?";
        $tipos_parametros .= 's';
        $parametros[] = $fecha_desde;
    }

    // FILTRO POR FECHA DE APERTURA HASTA
    if ($fecha_hasta != '') {
        $sql .= " AND DATE(turno_caja.fecha_apertura) <= ?";
        $tipos_parametros .= 's';
        $parametros[] = $fecha_hasta;
    }

    // ORDENAR RESULTADOS
    $ordenes = [
        'antiguos'   => 'turno_caja.fecha_apertura ASC',
        'total_desc' => 'total_recaudado DESC',
        'numero'     => 'turno_caja.id_turno DESC',
    ];
    $sql .= " ORDER BY " . (isset($ordenes[$orden]) ? $ordenes[$orden] : 'turno_caja.fecha_apertura DESC');

    // PREPARAR CONSULTA SEGURA
    $consulta = mysqli_prepare($conex, $sql)
        or die("Problema al preparar la consulta: " . mysqli_error($conex));

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

    // CANTIDAD DE RESULTADOS ENCONTRADOS
    $total_registros = mysqli_num_rows($registros);

    // SUMAR LO VENDIDO Y LAS COMPRAS Y GASTOS DE LAS CAJAS ENCONTRADAS
    // (se calcula con los movimientos de cada caja, así una caja abierta suma lo que lleva hasta ahora)
    $total_vendido = 0;
    $total_desembolsado = 0;
    while ($fila = mysqli_fetch_assoc($registros)) {
        $total_vendido += $fila['ventas_total'];
        $total_desembolsado += $fila['total_gastos'] + $fila['total_compras'];
    }
    mysqli_data_seek($registros, 0);

    // CONSULTAR EMPLEADOS PARA EL SELECT
    $empleados = mysqli_query($conex, "SELECT id_empleado, nombre, apellido FROM empleados ORDER BY nombre ASC")
        or die("Problema al consultar empleados: " . mysqli_error($conex));

mysqli_close($conex);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/panaderia-moderna.css">
    <title>Caja - Panadería M</title>
    <style>
        .sobrante, .monto-ingreso { color: #2E7D32; font-weight: 700; }
        .faltante, .monto-egreso { color: var(--bakery-danger); font-weight: 700; }
        .estado-abierto { background: #E8F5E9; color: #2E7D32; }
        .estado-cerrado { background: #EFEBE9; color: #6D4C41; }
        .capital { font-size: 1.15rem; margin-bottom: 15px; }
        .capital small { display: block; font-size: 0.85rem; color: #7A6257; }
        .div-filtros { flex-wrap: wrap; gap: 15px 25px; align-items: flex-end; }
        .div-filtros select, .div-filtros input { min-width: 150px; }
        .estado-caja { display: flex; justify-content: space-between; align-items: center; gap: 15px; flex-wrap: wrap; text-align: left; padding: 10px 20px; }
        .acciones-caja { display: flex; gap: 10px; flex-wrap: wrap; }
        .estado-caja .cuenta { margin-top: 6px; color: #3E2723; }
        .estado-caja .cuenta span { margin-left: 6px; }
        td small { color: #7A6257; }
        .monto { white-space: nowrap; }
        .b1 { display: flex; gap: 10px; flex-wrap: wrap; }
    </style>
</head>
<body>
    <?php include("menu.php"); ?>

    <div class="table-container">
        <div style="text-align: center; margin-bottom: 20px;">
            <h2>Caja - Panadería M</h2>

            <!-- CAPITAL TOTAL -->
            <p class="capital">
                Capital total: <b class="<?php echo ($capital_total < 0) ? 'monto-egreso' : 'monto-ingreso'; ?>"><?php echo ($capital_total < 0 ? '−' : '') . pesos(abs($capital_total)); ?></b>
                <small>(entró <?php echo pesos($capital['ingresos']); ?> − salió <?php echo pesos($capital['egresos']); ?>, según los cierres de caja)</small>
            </p>

            <!-- ESTADO DE LA CAJA -->
            <div class="mensaje <?php echo $caja_abierta ? 'exito' : 'advertencia'; ?> estado-caja">
                <div>
                    <?php if ($caja_abierta) { ?>
                        <b>Caja abierta</b>
                        (N° <?php echo $caja_abierta['id_turno']; ?>, <?php echo htmlspecialchars($caja_abierta['nombre'] . ' ' . $caja_abierta['apellido']); ?>, desde el <?php echo mostrarFecha($caja_abierta['fecha_apertura']); ?>)
                        <div class="cuenta">
                            Inicial <?php echo pesos($caja_abierta['monto_inicial']); ?>
                            <span class="monto-ingreso">+ ventas en efectivo <?php echo pesos($t_caja['ventas_efectivo']); ?></span>
                            <span class="monto-egreso">− compras <?php echo pesos($t_caja['compras_total']); ?></span>
                            <span class="monto-egreso">− gastos <?php echo pesos($t_caja['gastos_total']); ?></span>
                            = <b>Efectivo en caja <?php echo pesos($t_caja['esperado']); ?></b>
                        </div>
                    <?php } else { ?>
                        <b>La caja está cerrada.</b> Abrila para registrar ventas, compras y gastos.
                    <?php } ?>
                </div>
                <div class="acciones-caja">
                    <?php if ($caja_abierta) { ?>
                        <a class="button" href="ventas_alta.php">+ Venta</a>
                        <a class="button" href="pedido_compra_alta.php">+ Compra</a>
                        <a class="button" href="gastos_administrativos_alta.php">+ Gasto</a>
                        <form action="turno_caja_cierre.php" method="post" style="margin:0;">
                            <input type="hidden" name="id_turno" value="<?php echo $caja_abierta['id_turno']; ?>">
                            <button type="submit" class="button button-outline">Cerrar caja</button>
                        </form>
                    <?php } else { ?>
                        <a class="button" href="turno_caja_alta.php">Abrir Caja</a>
                    <?php } ?>
                </div>
            </div>

            <?php if (!empty($otras_abiertas)) { ?>
                <!-- QUEDARON OTRAS CAJAS ABIERTAS -->
                <div class="mensaje advertencia">
                    También quedó abierta la caja N° <?php echo implode(', N° ', array_column($otras_abiertas, 'id_turno')); ?>.
                    Cerrala desde la tabla (botón "Cerrar") para que quede una sola caja abierta.
                </div>
            <?php } ?>

            <!-- DIV DE FILTROS -->
            <div>
                <form method="GET" action="turno_caja_panel.php">
                    <div class="div-filtros">

                        <!-- EMPLEADO -->
                        <div>
                            <label for="empleado">Empleado:</label>
                            <select name="empleado" id="empleado">
                                <option value="">Todos los empleados</option>
                                <?php while($row_empleado = mysqli_fetch_assoc($empleados)) { ?>
                                    <option
                                        value="<?php echo htmlspecialchars($row_empleado['id_empleado']); ?>"
                                        <?php if($empleado == $row_empleado['id_empleado']) echo 'selected'; ?>
                                    >
                                        <?php echo htmlspecialchars($row_empleado['nombre'] . ' ' . $row_empleado['apellido']); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                        <!-- ESTADO -->
                        <div>
                            <label for="estado">Estado:</label>
                            <select name="estado" id="estado">
                                <option value="" <?php if($estado == '') echo 'selected'; ?>>Todas</option>
                                <option value="ABIERTO" <?php if($estado == 'ABIERTO') echo 'selected'; ?>>Abiertas</option>
                                <option value="CERRADO" <?php if($estado == 'CERRADO') echo 'selected'; ?>>Cerradas</option>
                            </select>
                        </div>

                        <!-- APERTURA DESDE -->
                        <div>
                            <label for="fecha_desde">Apertura desde:</label>
                            <input type="date" name="fecha_desde" id="fecha_desde" value="<?php echo htmlspecialchars($fecha_desde); ?>">
                        </div>

                        <!-- APERTURA HASTA -->
                        <div>
                            <label for="fecha_hasta">Apertura hasta:</label>
                            <input type="date" name="fecha_hasta" id="fecha_hasta" value="<?php echo htmlspecialchars($fecha_hasta); ?>">
                        </div>

                        <!-- ORDENAR POR -->
                        <div>
                            <label for="orden">Ordenar por:</label>
                            <select name="orden" id="orden">
                                <option value="" <?php if($orden == '') echo 'selected'; ?>>Más recientes</option>
                                <option value="antiguos" <?php if($orden == 'antiguos') echo 'selected'; ?>>Más antiguas</option>
                                <option value="total_desc" <?php if($orden == 'total_desc') echo 'selected'; ?>>Mayor total</option>
                                <option value="numero" <?php if($orden == 'numero') echo 'selected'; ?>>Número de caja</option>
                            </select>
                        </div>

                        <!-- BOTONES FILTRAR Y LIMPIAR -->
                        <div class="botones-filtros">
                            <button type="submit" class="button">
                                FILTRAR
                            </button>

                            <a href="turno_caja_panel.php" class="button button-outline">
                                LIMPIAR
                            </a>
                        </div>

                    </div>
                </form>

                <!-- CANTIDAD DE RESULTADOS -->
                <p class="resultado-filtros">
                    Se encontraron <strong><?php echo $total_registros; ?></strong> caja(s).
                    Vendido: <strong class="monto-ingreso">+<?php echo pesos($total_vendido); ?></strong> ·
                    Compras y gastos: <strong class="monto-egreso">−<?php echo pesos($total_desembolsado); ?></strong>
                </p>
            </div>

            <div class="div-mayor">
                <div class="b1">
                    <a class="button button-outline" href="registros_financieros_panel.php">Registros Financieros</a>
                </div>

                <div class="b1">
                    <a class="button button-outline" href="page1.php">Volver</a>
                </div>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Caja</th>
                    <th>Fecha</th>
                    <th>Inicial</th>
                    <th>Movimientos</th>
                    <th>Esperado</th>
                    <th>Contado</th>
                    <th>Diferencia</th>
                </tr>
            </thead>
            <tbody>
            <?php while($row_registros= mysqli_fetch_assoc($registros)) {
                $abierta = ($row_registros['estado'] == 'ABIERTO');
                // las cajas viejas que se cerraron sin arqueo no tienen fecha de cierre
                $sin_arqueo = $abierta || empty($row_registros['fecha_cierre']) || substr($row_registros['fecha_cierre'], 0, 4) == '0000';
                // EFECTIVO ESPERADO = inicial + ventas en efectivo − compras − gastos
                $ingresos = $row_registros['ventas_total'];
                $egresos = $row_registros['total_gastos'] + $row_registros['total_compras'];
                $esperado = $row_registros['monto_inicial'] + $row_registros['ventas_efectivo'] - $egresos;
                $diferencia = round($row_registros['monto_final'] - $esperado, 2);
            ?>
                <tr>
                    <td>
                        <strong>N° <?php echo htmlspecialchars($row_registros['id_turno']);?></strong><br>
                        <small><?php echo htmlspecialchars($row_registros['nombre'] . ' ' . $row_registros['apellido']);?></small>
                        <form action="turno_caja_cierre.php" method="post" style="margin:6px 0 0;">
                            <input type="hidden" name="id_turno" value="<?php echo $row_registros['id_turno'];?>">
                            <button type="submit" class="<?php echo $abierta ? 'button' : 'button button-outline'; ?>" style="padding: 4px 12px; font-size: 0.75rem;">
                                <?php echo $abierta ? 'Cerrar' : 'Ver'; ?>
                            </button>
                        </form>
                    </td>
                    <td>
                        <span class="monto"><?php echo mostrarFecha($row_registros['fecha_apertura']);?></span><br>
                        <small>
                            <?php if ($abierta) { ?>
                                <span class="bakery-badge estado-abierto">abierta</span>
                            <?php } elseif ($sin_arqueo) { ?>
                                cerrada sin arqueo
                            <?php } else { ?>
                                cerró <?php echo (substr($row_registros['fecha_cierre'], 0, 10) == substr($row_registros['fecha_apertura'], 0, 10)) ? 'a las ' . date('H:i', strtotime($row_registros['fecha_cierre'])) : 'el ' . mostrarFecha($row_registros['fecha_cierre']); ?>
                            <?php } ?>
                        </small>
                    </td>
                    <td class="monto"><?php echo pesos($row_registros['monto_inicial']); ?></td>
                    <td class="monto">
                        <span class="monto-ingreso">+<?php echo pesos($ingresos); ?></span> <small>vendido</small><br>
                        <span class="monto-egreso">−<?php echo pesos($egresos); ?></span> <small>compras y gastos</small>
                    </td>
                    <td class="monto">
                        <?php if (round($esperado, 2) < 0) { ?>
                            <span class="faltante" title="Hay egresos por más plata de la que tenía la caja. Revisalos en Ver detalle.">⚠ −<?php echo pesos(abs($esperado)); ?></span>
                        <?php } else { ?>
                            $<?php echo number_format($esperado, 2, ',', '.'); ?>
                        <?php } ?>
                    </td>
                    <td class="monto"><?php echo $sin_arqueo ? '-' : pesos($row_registros['monto_final']);?></td>
                    <td class="monto">
                        <?php if ($sin_arqueo) { echo '-'; } elseif (round($esperado, 2) < 0) { ?>
                            <span class="faltante" title="El efectivo esperado es negativo: revisá los egresos en Ver detalle.">⚠ Revisar</span>
                        <?php } elseif ($diferencia < 0) { ?>
                            <span class="faltante">−$<?php echo number_format(abs($diferencia), 2, ',', '.'); ?></span>
                        <?php } else { ?>
                            <span class="sobrante">$<?php echo number_format($diferencia, 2, ',', '.'); ?></span>
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>

            <!-- MENSAJE CUANDO NO HAY RESULTADOS -->
            <?php if ($total_registros == 0) { ?>
                <tr>
                    <td colspan="7" class="sin-resultados">
                        No se encontraron resultados con los filtros aplicados.
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>

    <?php include("footer.php"); ?>
</body>
</html>
