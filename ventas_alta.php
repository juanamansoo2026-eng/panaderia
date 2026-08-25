<?php
// Incluir archivo de conexión
include_once("conexion.php");

$fecha_actual = date('Y-m-d'); 

// Consultas para obtener los datos de clientes, empleados y productos
$clientes_query = "SELECT id_cliente, nombre, apellido FROM clientes";
$empleados_query = "SELECT id_empleado, nombre, apellido FROM empleados";
$productos_query = "SELECT id_producto, nombre, precio FROM productos";

$clientes_result = $conex->query($clientes_query);
$empleados_result = $conex->query($empleados_query);
$productos_result = $conex->query($productos_query);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,300italic,700,700italic">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/milligram/1.4.1/milligram.css">
    <title>Venta - Panadería M</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid black;
        }

        th, td {
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #c561ff;
            color:rgb(47, 45, 48);
        }

        input[type="number"], select {
            width: 95%;
            padding: 5px;
        }

        .form-container {
            margin: 20px;
        }
    </style>
</head>
<body>

    <div class="form-container">
        <h1>Venta - Panadería M</h1>

        <form action="alta_ventas.php" method="post" id="formVenta">

            <label>Fecha:</label>
            <input type="datetime" name="fecha" value="<?php echo $fecha_actual; ?>" readonly>
            <br>

            <label for="empleado_id">Empleado:</label>
            <select name="empleado_id" id="empleado_id" required>
                <option value="">Seleccione un empleado</option>
                <?php while ($row_empleado = $empleados_result->fetch_assoc()) { ?>
                    <option value="<?= $row_empleado['id_empleado'] ?>"><?= $row_empleado['nombre'] . " " . $row_empleado['apellido'] ?></option>
                <?php } ?>
            </select>
            <br>

            <label for="cliente_id">Cliente:</label>
            <select name="cliente_id" id="cliente_id" required>
                <option value="">Seleccione un cliente</option>
                <?php while ($row_cliente = $clientes_result->fetch_assoc()) { ?>
                    <option value="<?= $row_cliente['id_cliente'] ?>"><?= $row_cliente['nombre'] . " " . $row_cliente['apellido'] ?></option>
                <?php } ?>
            </select>
            <br>

            <label for="forma_pago">Forma de pago:</label>
            <select name="forma_pago" id="forma_pago" required>
                <option value="efectivo">Efectivo</option>
                <option value="tarjeta">Tarjeta</option>
                <option value="transferencia">Transferencia</option>
            </select>
            <br>

            <h3 style="color: black; text-align: center;">Productos</h3>
            <table id="tablaProductos">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio</th>
                        <th>Precio Subtotal</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="producto-row">
                        <td>
                            <select name="productos[]" class="producto" required>
                                <option value="">Seleccione un producto</option>
                                <?php while ($row_producto = $productos_result->fetch_assoc()) { ?>
                                    <option value="<?= $row_producto['id_producto'] ?>" data-precio="<?= $row_producto['precio'] ?>"><?= $row_producto['nombre'] ?></option>
                                <?php } ?>
                            </select>
                        </td>
                        <td><input type="number" name="cantidades[]" class="cantidad" value="0" min="0" required></td>
                        <td><input type="number" name="precio[]" class="precio" value="0" readonly></td>
                        <td><input type="number" class="precio_subtotal" value="0" readonly></td>
                        <td><button type="button" class="eliminar">Eliminar</button></td>
                    </tr>

                    <tr>
                        <th colspan="3">Subtotal</th>
                        <td colspan="2"><input type="number" name="subtotal" id="subtotal" value="0" readonly></td>
                    </tr>
                    <tr>
                        <th colspan="3">Descuento</th>
                        <td colspan="2"><input type="number" name="descuento" id="descuento" value="0" step="0.1"></td>
                    </tr>
                    <tr>
                        <th colspan="3">Total</th>
                        <td colspan="2"><input type="number" name="total" id="total" value="0" readonly></td>
                    </tr>
                </tbody>
            </table>
            <br>

            <button type="button" id="agregarProducto">Agregar Producto</button>
            <input type="submit" name="enviar" value="Grabar">
            <input type="submit" value="Cancelar" form="volver">
        </form>
        <form action="turno_caja_panel.php" id="volver" method="post"></form>
    </div>

    <script>
    // Función para calcular el precio y el subtotal de cada producto
    document.getElementById('formVenta').addEventListener('change', function (e) {
        if (e.target.classList.contains('producto') || e.target.classList.contains('cantidad') || e.target.id === 'descuento') {
            calcularTotales();
        }
    });

    // Agregar un producto a la tabla
    document.getElementById('agregarProducto').addEventListener('click', function () {
        const tablaProductos = document.getElementById('tablaProductos').getElementsByTagName('tbody')[0];
        const nuevaFila = document.querySelector('.producto-row').cloneNode(true);

        // Asegurarse de que los campos de la nueva fila estén vacíos (valor 0 para cantidad, precio, etc.)
        const inputs = nuevaFila.querySelectorAll('input');
        inputs.forEach(input => {
            input.value = 0;
        });

        const selects = nuevaFila.querySelectorAll('select');
        selects.forEach(select => {
            select.selectedIndex = 0; // Limpiar la selección de producto
        });

        // Insertar la nueva fila **antes de la fila de subtotal**
        const filaSubtotal = document.getElementById('tablaProductos').querySelector('tr:nth-last-child(3)');
        tablaProductos.insertBefore(nuevaFila, filaSubtotal); // Insertamos antes de la fila de subtotal

        calcularTotales();
    });

    // Eliminar producto de la tabla
    document.getElementById('tablaProductos').addEventListener('click', function (e) {
        if (e.target.classList.contains('eliminar')) {
            e.target.closest('tr').remove();
            calcularTotales();
        }
    });

    // Función para calcular totales
    function calcularTotales() {
        let subtotal = 0;
        const filas = document.querySelectorAll('.producto-row');
        filas.forEach(function (fila) {
            const cantidad = fila.querySelector('.cantidad').value;
            const productoSelect = fila.querySelector('.producto');
            const precio = parseFloat(productoSelect.options[productoSelect.selectedIndex].getAttribute('data-precio')) || 0;
            const subtotalProducto = cantidad * precio;

            fila.querySelector('.precio').value = precio.toFixed(2);
            fila.querySelector('.precio_subtotal').value = subtotalProducto.toFixed(2);

            subtotal += subtotalProducto;
        });

        // Obtener el descuento como porcentaje
        const descuentoPorcentaje = parseFloat(document.getElementById('descuento').value) || 0;

        // Mostrar el subtotal (sin descuento)
        document.getElementById('subtotal').value = subtotal.toFixed(2);

        // Aplicar el descuento al total como porcentaje del subtotal
        const descuento = (descuentoPorcentaje / 100) * subtotal; // Descuento en base al porcentaje
        let total = subtotal - descuento;

        // Asegurarse de que el total no sea negativo
        if (total < 0) total = 0;

        document.getElementById('total').value = total.toFixed(2);
    }
</script>

</body>
</html>