<?php
require_once __DIR__ . '/auth.php';
?>
<?php if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
include_once("conexion.php");
require_once("caja_funciones.php");

$fecha_actual = date('Y-m-d H:i:s');

// CAJA DE VENTAS ABIERTA (la venta se guarda en esa caja)
$caja_abierta = cajaAbierta($conex);

$clientes_query = "SELECT id_cliente, nombre, apellido FROM clientes";
$empleados_query = "SELECT id_empleado, nombre, apellido, username FROM empleados";
$productos_query = "SELECT id_producto, nombre, precio FROM productos";

$clientes_result = $conex->query($clientes_query);
$empleados_result = $conex->query($empleados_query);
$productos_result = $conex->query($productos_query);

$username = isset($_SESSION['username']) ? htmlspecialchars((string) $_SESSION['username'], ENT_QUOTES, 'UTF-8') : '';

// Pre-fetch product options HTML so JavaScript clones work smoothly
$product_options = '<option value="">Seleccione un producto</option>';
while ($row_producto = $productos_result->fetch_assoc()) {
    $product_options .= '<option value="' . $row_producto['id_producto'] . '" data-precio="' . $row_producto['precio'] . '">' . htmlspecialchars($row_producto['nombre']) . ' ($' . number_format($row_producto['precio'], 2, ',', '.') . ')</option>';
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/panaderia-moderna.css">
    <title>Nueva Venta - Panadería M</title>
</head>
<body>

    <?php include("menu.php"); ?>

    <div class="container" style="max-width: 900px;">
        <h2 style="text-align: center;">Registrar Nueva Venta - Panadería M</h2>

        <?php if (!$caja_abierta) { ?>
            <!-- SIN CAJA ABIERTA NO SE PUEDEN REGISTRAR VENTAS -->
            <div class="mensaje advertencia" role="alert">
                No hay ninguna caja abierta. Para registrar ventas primero tenés que abrirla.
            </div>
            <div class="resultado-acciones">
                <a class="button" href="turno_caja_alta.php">Abrir Caja</a>
                <a class="button button-outline" href="ventas_panel.php">Volver</a>
            </div>
        </div>
        <?php include("footer.php"); ?>
</body>
</html>
        <?php exit(); } ?>

        <p style="text-align: center;">
            Caja abierta N° <b><?php echo $caja_abierta['id_turno']; ?></b>
            de <?php echo htmlspecialchars($caja_abierta['nombre'] . ' ' . $caja_abierta['apellido']); ?>
        </p>

        <form action="alta_ventas.php" method="post" id="formVenta">

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-bottom: 20px;">
                <div>
                    <label>Fecha:</label>
                    <input type="text" name="fecha" value="<?php echo $fecha_actual; ?>" readonly style="background:#F0E6D8;">
                </div>

                <div>
                    <label>Empleado:</label>
                    <?php
                    $emp = $conex->query("SELECT id_empleado FROM empleados WHERE username = '" . $conex->real_escape_string($_SESSION['username']) . "'")->fetch_assoc();
                    ?>
                    <div style="padding:11px 16px; background:#F0E6D8; border-radius:8px; font-weight:600;"><?= htmlspecialchars($_SESSION['username']) ?></div>
                    <textarea name="empleado_id" hidden><?= $emp['id_empleado'] ?></textarea>
                </div>


                <div>
                    <label for="cliente_id">Cliente:</label>
                    <select name="cliente_id" id="cliente_id" required>
                        <?php while ($row_cliente = $clientes_result->fetch_assoc()) { ?>
                            <option 
                                value="<?= $row_cliente['id_cliente'] ?>"
                                <?= ($row_cliente['nombre'] == 'Consumidor Final') ? 'selected' : '' ?>
                            >
                                <?= htmlspecialchars($row_cliente['nombre'] . " " . $row_cliente['apellido']) ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <div>
                    <label for="forma_pago">Forma de Pago:</label>
                    <select name="forma_pago" id="forma_pago" required>
                        <option value="efectivo">Efectivo</option>
                        <option value="tarjeta">Tarjeta</option>
                        <option value="transferencia">Transferencia</option>
                    </select>
                </div>
            </div>

            <h3 style="color: var(--bakery-dark); margin-top: 25px;">Detalle de Productos</h3>
            <table id="tablaProductos" style="margin-top: 10px;">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th style="width: 120px;">Cantidad</th>
                        <th style="width: 120px;">P. Unitario</th>
                        <th style="width: 130px;">Subtotal</th>
                        <th style="width: 100px;">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="producto-row">
                        <td>
                            <select name="productos[]" class="producto" required style="margin-bottom:0;">
                                <?php echo $product_options; ?>
                            </select>
                        </td>
                        <td><input type="number" name="cantidades[]" class="cantidad" value="1" min="1" required style="margin-bottom:0;"></td>
                        <td><input type="number" name="precio[]" class="precio" value="0" readonly style="margin-bottom:0; background:#F0E6D8;"></td>
                        <td><input type="number" class="precio_subtotal" value="0" readonly style="margin-bottom:0; background:#F0E6D8; font-weight:600;"></td>
                        <td align="center"><button type="button" class="eliminar button-danger" style="padding: 5px 12px; margin:0;">Eliminar</button></td>
                    </tr>

                    <tr>
                        <th colspan="3" style="text-align: right;">Subtotal General:</th>
                        <td colspan="2"><input type="number" name="subtotal" id="subtotal" value="0" readonly style="margin-bottom:0; font-weight:bold; background:#F0E6D8;"></td>
                    </tr>
                    <tr>
                        <th colspan="3" style="text-align: right;">Descuento (%):</th>
                        <td colspan="2"><input type="number" name="descuento" id="descuento" value="0" step="0.1" style="margin-bottom:0;"></td>
                    </tr>
                    <tr>
                        <th colspan="3" style="text-align: right;">Total Final ($):</th>
                        <td colspan="2"><input type="number" name="total" id="total" value="0" readonly style="margin-bottom:0; font-weight:bold; color:var(--bakery-primary); font-size:1.1rem; background:#FFF8EE;"></td>
                    </tr>
                </tbody>
            </table>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 25px;">
                <button type="button" id="agregarProducto" class="button-outline"><svg width="25px" height="25px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <circle cx="12" cy="12" r="10" stroke="#B85C28" stroke-width="1.5"></circle> <path d="M15 12L12 12M12 12L9 12M12 12L12 9M12 12L12 15" stroke="#B85C28" stroke-width="1.5" stroke-linecap="round"></path></g></svg>  Agregar Otro Producto</button>
                <div>
                    <button type="submit" name="enviar"><svg class="ui-icon" aria-hidden="true" viewBox="0 0 24 24"><path d="M5 3h12l4 4v14H3V3zM7 3v6h10V3M7 21v-8h10v8"/></svg>Confirmar Venta</button>
                    <a class="button button-outline" href="ventas_panel.php"><svg width="25px" height="25px" fill="#B85C28" viewBox="0 0 32 32" version="1.1" xmlns="http://www.w3.org/2000/svg" stroke="#B85C28"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <title>cancel1</title> <path d="M16 29c-7.18 0-13-5.82-13-13s5.82-13 13-13 13 5.82 13 13-5.82 13-13 13zM16 6c-5.522 0-10 4.478-10 10s4.478 10 10 10c5.523 0 10-4.478 10-10s-4.477-10-10-10zM20.537 19.535l-1.014 1.014c-0.186 0.186-0.488 0.186-0.675 0l-2.87-2.87-2.87 2.87c-0.187 0.186-0.488 0.186-0.675 0l-1.014-1.014c-0.186-0.186-0.186-0.488 0-0.675l2.871-2.869-2.871-2.87c-0.186-0.187-0.186-0.489 0-0.676l1.014-1.013c0.187-0.187 0.488-0.187 0.675 0l2.87 2.87 2.87-2.87c0.187-0.187 0.489-0.187 0.675 0l1.014 1.013c0.186 0.187 0.186 0.489 0 0.676l-2.871 2.87 2.871 2.869c0.186 0.187 0.186 0.49 0 0.675z"></path> </g></svg>Cancelar</a>
                </div>
            </div>
        </form>
    </div>

    <script>
    document.getElementById('formVenta').addEventListener('change', function (e) {
        if (e.target.classList.contains('producto') || e.target.classList.contains('cantidad') || e.target.id === 'descuento') {
            calcularTotales();
        }
    });

    document.getElementById('formVenta').addEventListener('input', function (e) {
        if (e.target.classList.contains('cantidad') || e.target.id === 'descuento') {
            calcularTotales();
        }
    });

    document.getElementById('agregarProducto').addEventListener('click', function () {
        const tablaProductos = document.getElementById('tablaProductos').getElementsByTagName('tbody')[0];
        const nuevaFila = document.querySelector('.producto-row').cloneNode(true);

        const inputs = nuevaFila.querySelectorAll('input');
        inputs.forEach(input => {
            if (input.classList.contains('cantidad')) {
                input.value = 1;
            } else {
                input.value = 0;
            }
        });

        const selects = nuevaFila.querySelectorAll('select');
        selects.forEach(select => {
            select.selectedIndex = 0;
        });

        const filaSubtotal = document.getElementById('tablaProductos').querySelector('tr:nth-last-child(3)');
        tablaProductos.insertBefore(nuevaFila, filaSubtotal);

        calcularTotales();
    });

    document.getElementById('tablaProductos').addEventListener('click', function (e) {
        if (e.target.classList.contains('eliminar')) {
            const filas = document.querySelectorAll('.producto-row');
            if (filas.length > 1) {
                e.target.closest('tr').remove();
                calcularTotales();
            } else {
                alert('Debe incluir al menos un producto en la venta.');
            }
        }
    });

    function calcularTotales() {
        let subtotal = 0;
        const filas = document.querySelectorAll('.producto-row');
        filas.forEach(function (fila) {
            const cantidad = parseFloat(fila.querySelector('.cantidad').value) || 0;
            const productoSelect = fila.querySelector('.producto');
            const precio = parseFloat(productoSelect.options[productoSelect.selectedIndex]?.getAttribute('data-precio')) || 0;
            const subtotalProducto = cantidad * precio;

            fila.querySelector('.precio').value = precio.toFixed(2);
            fila.querySelector('.precio_subtotal').value = subtotalProducto.toFixed(2);

            subtotal += subtotalProducto;
        });

        const descuentoPorcentaje = parseFloat(document.getElementById('descuento').value) || 0;
        document.getElementById('subtotal').value = subtotal.toFixed(2);

        const descuento = (descuentoPorcentaje / 100) * subtotal;
        let total = subtotal - descuento;
        if (total < 0) total = 0;

        document.getElementById('total').value = total.toFixed(2);
    }
    </script>

    <?php include("footer.php"); ?>
</body>
</html>