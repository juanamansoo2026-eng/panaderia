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
    $tipo = isset($_GET['tipo']) && ($_GET['tipo'] == 'INGRESO' || $_GET['tipo'] == 'EGRESO') ? $_GET['tipo'] : '';
    $buscar = isset($_GET['buscar']) ? trim($_GET['buscar']) : '';
    $fecha_desde = isset($_GET['fecha_desde']) ? validarFecha(trim($_GET['fecha_desde'])) : '';
    $fecha_hasta = isset($_GET['fecha_hasta']) ? validarFecha(trim($_GET['fecha_hasta'])) : '';
    $orden = isset($_GET['orden']) ? $_GET['orden'] : '';

    // CONSULTA INICIAL
    $sql = "SELECT id_registro_financiero, tipo, monto, desripcion, fecha
            FROM registros_financieros
            WHERE 1=1";

    $parametros = [];
    $tipos_parametros = '';

    // FILTRO POR TIPO (INGRESO / EGRESO)
    if ($tipo != '') {
        $sql .= " AND tipo = ?";
        $tipos_parametros .= 's';
        $parametros[] = $tipo;
    }

    // FILTRO POR DESCRIPCIÓN
    if ($buscar != '') {
        $sql .= " AND desripcion LIKE ?";
        $tipos_parametros .= 's';
        $parametros[] = '%' . $buscar . '%';
    }

    // FILTRO POR FECHA DESDE
    if ($fecha_desde != '') {
        $sql .= " AND DATE(fecha) >= ?";
        $tipos_parametros .= 's';
        $parametros[] = $fecha_desde;
    }

    // FILTRO POR FECHA HASTA
    if ($fecha_hasta != '') {
        $sql .= " AND DATE(fecha) <= ?";
        $tipos_parametros .= 's';
        $parametros[] = $fecha_hasta;
    }

    // ORDENAR RESULTADOS
    $ordenes = [
        'antiguos'   => 'fecha ASC',
        'monto_desc' => 'monto DESC',
    ];
    $sql .= " ORDER BY " . (isset($ordenes[$orden]) ? $ordenes[$orden] : 'fecha DESC, id_registro_financiero DESC');

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

    // TOTALES: INGRESOS, EGRESOS Y CAPITAL NETO (de los registros encontrados)
    $ingresos = 0;
    $egresos = 0;
    while ($fila = mysqli_fetch_assoc($registros)) {
        if ($fila['tipo'] == 'INGRESO') {
            $ingresos += $fila['monto'];
        } else {
            $egresos += $fila['monto'];
        }
    }
    mysqli_data_seek($registros, 0);
    $capital_neto = $ingresos - $egresos;

mysqli_close($conex);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/panaderia-moderna.css">
    <title>Registros Financieros - Panadería M</title>
    <style>
        .ingreso { color: #2E7D32; font-weight: 700; }
        .egreso { color: var(--bakery-danger); font-weight: 700; }
        .tipo-ingreso { background: #E8F5E9; color: #2E7D32; }
        .tipo-egreso { background: #FFEBEE; color: var(--bakery-danger); }
    </style>
</head>
<body>
    <?php include("menu.php"); ?>

    <div class="table-container">
        <div style="text-align: center; margin-bottom: 20px;">
            <h2>Registros Financieros - Panadería M</h2>
            <p>Cada vez que se cierra la caja se guardan dos registros: un INGRESO con lo vendido y un EGRESO con las compras y gastos.</p>

            <!-- DIV DE FILTROS -->
            <div>
                <form method="GET" action="registros_financieros_panel.php">
                    <div class="div-filtros">

                        <!-- TIPO -->
                        <div>
                            <label for="tipo">Tipo:</label>
                            <select name="tipo" id="tipo">
                                <option value="" <?php if($tipo == '') echo 'selected'; ?>>Todos</option>
                                <option value="INGRESO" <?php if($tipo == 'INGRESO') echo 'selected'; ?>>Ingresos</option>
                                <option value="EGRESO" <?php if($tipo == 'EGRESO') echo 'selected'; ?>>Egresos</option>
                            </select>
                        </div>

                        <!-- BUSCAR -->
                        <div>
                            <label for="buscar">Descripción:</label>
                            <input type="text" name="buscar" id="buscar" value="<?php echo htmlspecialchars($buscar); ?>" placeholder="Ej: caja de ventas #12">
                        </div>

                        <!-- FECHA DESDE -->
                        <div>
                            <label for="fecha_desde">Desde:</label>
                            <input type="date" name="fecha_desde" id="fecha_desde" value="<?php echo htmlspecialchars($fecha_desde); ?>">
                        </div>

                        <!-- FECHA HASTA -->
                        <div>
                            <label for="fecha_hasta">Hasta:</label>
                            <input type="date" name="fecha_hasta" id="fecha_hasta" value="<?php echo htmlspecialchars($fecha_hasta); ?>">
                        </div>

                        <!-- ORDENAR POR -->
                        <div>
                            <label for="orden">Ordenar por:</label>
                            <select name="orden" id="orden">
                                <option value="" <?php if($orden == '') echo 'selected'; ?>>Más recientes</option>
                                <option value="antiguos" <?php if($orden == 'antiguos') echo 'selected'; ?>>Más antiguos</option>
                                <option value="monto_desc" <?php if($orden == 'monto_desc') echo 'selected'; ?>>Mayor monto</option>
                            </select>
                        </div>

                        <!-- BOTONES FILTRAR Y LIMPIAR -->
                        <div class="botones-filtros">
                            <button type="submit" class="button">
                                FILTRAR
                            </button>

                            <a href="registros_financieros_panel.php" class="button button-outline">
                                LIMPIAR
                            </a>
                        </div>

                    </div>
                </form>

                <!-- CANTIDAD DE RESULTADOS Y TOTALES -->
                <p class="resultado-filtros">
                    Se encontraron <strong><?php echo $total_registros; ?></strong> registro(s).
                    Ingresos: <strong class="ingreso">+<?php echo pesos($ingresos); ?></strong> ·
                    Egresos: <strong class="egreso">−<?php echo pesos($egresos); ?></strong> ·
                    Capital neto: <strong class="<?php echo ($capital_neto < 0) ? 'egreso' : 'ingreso'; ?>"><?php echo ($capital_neto < 0 ? '−' : '') . pesos(abs($capital_neto)); ?></strong>
                </p>
            </div>

            <div class="div-mayor">
                <div class="b1">
                    <a class="button" href="turno_caja_panel.php">Ir a Caja</a>
                </div>

                <div class="b1">
                    <a class="button button-outline" href="turno_caja_panel.php">Volver</a>
                </div>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>N°</th>
                    <th>Fecha</th>
                    <th>Tipo</th>
                    <th>Descripción</th>
                    <th>Monto</th>
                </tr>
            </thead>
            <tbody>
            <?php while($row_registros = mysqli_fetch_assoc($registros)) {
                $es_ingreso = ($row_registros['tipo'] == 'INGRESO');
            ?>
                <tr>
                    <td><?php echo htmlspecialchars($row_registros['id_registro_financiero']); ?></td>
                    <td><?php echo mostrarFecha($row_registros['fecha']); ?></td>
                    <td><span class="bakery-badge <?php echo $es_ingreso ? 'tipo-ingreso' : 'tipo-egreso'; ?>"><?php echo htmlspecialchars($row_registros['tipo']); ?></span></td>
                    <td><strong><?php echo htmlspecialchars($row_registros['desripcion']); ?></strong></td>
                    <td class="<?php echo $es_ingreso ? 'ingreso' : 'egreso'; ?>"><?php echo ($es_ingreso ? '+' : '−') . pesos($row_registros['monto']); ?></td>
                </tr>
            <?php } ?>

            <!-- MENSAJE CUANDO NO HAY RESULTADOS -->
            <?php if ($total_registros == 0) { ?>
                <tr>
                    <td colspan="5" class="sin-resultados">
                        No se encontraron registros con los filtros aplicados.
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>

    <?php include("footer.php"); ?>
</body>
</html>
