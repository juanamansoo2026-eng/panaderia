<?php
include_once("conexion.php");

$fecha_actual = date('Y-m-d'); 

$clientes_query = "SELECT id_cliente, nombre, apellido FROM clientes";
$empleados_query = "SELECT id_empleado, nombre, apellido FROM empleados";
$productos_query = "SELECT id_producto, nombre, precio FROM productos";

$clientes_result = $conex->query($clientes_query);
$empleados_result = $conex->query($empleados_query);
$productos_result = $conex->query($productos_query);

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

        <form action="alta_ventas.php" method="post" id="formVenta">

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-bottom: 20px;">
                <div>
                    <label>Fecha:</label>
                    <input type="text" name="fecha" value="<?php echo $fecha_actual; ?>" readonly style="background:#F0E6D8;">
                </div>

                <div>
                    <label for="empleado_id">Empleado:</label>
                    <label>
                    <select name="empleado_id" id="empleado_id" required>
                        <option value="">Seleccione un empleado</option>
                        <?php while ($row_empleado = $empleados_result->fetch_assoc()) { ?>
                            <option value="<?= $row_empleado['id_empleado'] ?>"><?= htmlspecialchars($row_empleado['nombre'] . " " . $row_empleado['apellido']) ?></option>
                        <?php } ?>
                    </select>
                </div>

                <div>
                    <label for="cliente_id">Cliente:</label>
                    <select name="cliente_id" id="cliente_id" required>
                        <option value="">Seleccione un cliente</option>
                        <?php while ($row_cliente = $clientes_result->fetch_assoc()) { ?>
                            <option value="<?= $row_cliente['id_cliente'] ?>"><?= htmlspecialchars($row_cliente['nombre'] . " " . $row_cliente['apellido']) ?></option>
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
                <button type="button" id="agregarProducto" class="button-outline">➕ Agregar Otro Producto</button>
                <div>
                    <button type="submit" name="enviar"><svg class="ui-icon" aria-hidden="true" viewBox="0 0 24 24"><path d="M5 3h12l4 4v14H3V3zM7 3v6h10V3M7 21v-8h10v8"/></svg>Confirmar Venta</button>
                    <a class="button button-outline" href="ventas_panel.php">❌ Cancelar</a>
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