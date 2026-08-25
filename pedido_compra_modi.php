<?php
if (isset($_REQUEST['id_pedido_compra'])) {
    $id_pedido_compra = $_REQUEST['id_pedido_compra'];
    require_once("conexion.php"); //conexion a la bd
    $registro = mysqli_query($conex, "SELECT id_pedido_compra, pedido_compra.proveedor_id, proveedor.nombre, proveedor.apellido, materia_prima_id, materia_prima.nombre_mp, marca_pc, cantidad, pedido_compra.precio, total, fecha
    FROM pedido_compra
    INNER JOIN proveedor ON proveedor.id_proveedor = pedido_compra.proveedor_id
    INNER JOIN materia_prima ON materia_prima.id_materia_prima = pedido_compra.materia_prima_id
    WHERE id_pedido_compra = $id_pedido_compra")
    or die ("problema en la consulta".mysqli_error($conex)); //mysqli_error me muestra un mensaje con el problema y su linea, "or die" es si sale bien no pasa nada, "O die" o te sale el mensaje error
    if ($registro) {
        $row_registro = mysqli_fetch_assoc($registro); //carga cada columna/campo como id, nombre, etc, lo guardad aca
    } else {
        echo 'error en la conexion';
    }
} 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,300italic,700,700italic">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/milligram/1.4.1/milligram.css">
    <title>Modificar Pedido de Compra - Panadería M</title>
    <script>
        // Función para actualizar el total de compra y otros campos (precio, marca)
        function actualizarTotal() {
            // Obtener la cantidad ingresada (inicializada en 0 si no se ingresa nada)
            var cantidad = parseFloat(document.getElementById("cantidad").value) || 0;
            
            // Obtener el precio unitario del producto seleccionado
            var productoSeleccionado = document.getElementById("producto").options[document.getElementById("producto").selectedIndex];
            var precio_unitario = parseFloat(productoSeleccionado.getAttribute("data-precio")) || 0;
            
            // Calcular el total de compra: cantidad * precio_unitario
            var total = cantidad * precio_unitario;
            
            // Actualizar el campo Total Compra
            document.getElementById("total").value = total.toFixed(2);  // Formato con 2 decimales
            
            // Actualizar el precio unitario
            document.getElementById("precio").value = precio_unitario.toFixed(2);

            // Actualizar la marca automáticamente
            var marca = productoSeleccionado.innerText.split(" - ")[1] || ""; // Obtener la parte después del guion
            document.getElementById("marca").value = marca; // Asignar la marca al campo de texto
        }

        // Función que se ejecuta cuando se cambia el producto
        function reiniciarCampos() {
            // Reiniciar la cantidad y el total a 0
            document.getElementById("cantidad").value = 0;
            document.getElementById("total").value = 0;

            // Llamar a la función para actualizar el total
            actualizarTotal();
        }

        // Función que se ejecuta cuando se cambia la cantidad o el producto
        window.onload = function() {
            // Inicializar la cantidad y el total con 0
            document.getElementById("cantidad").value = 0;
            document.getElementById("total").value = 0;
            
            // Escuchar los cambios en el campo cantidad y producto
            document.getElementById("cantidad").addEventListener('input', actualizarTotal);  // Cuando cambia la cantidad
            document.getElementById("producto").addEventListener('change', reiniciarCampos); // Cuando cambia el producto
            
            // Llamar a la función inicial para calcular el total cuando la página se carga
            actualizarTotal();
        };
    </script>
</head>
<body>
    <div class="container" style="width: 600px">
        <h2 style="text-align: center">Modificar Pedido de Compra - Panadería M</h2>
        <form action="modi_pedido_compra.php" method="post" autocomplete="off" required>
            
            <label>ID Pedido Compra:</label>
            <input style="background:silver;" type="text" name="id_pedido_compra" value="<?php echo $row_registro['id_pedido_compra'];?>" readonly>
            <br>

            <label>Proveedor:</label>
            <select name="id_proveedor" required>
                <?php
                    include_once ('conexion.php'); 
                    $resultado = mysqli_query($conex,"SELECT id_proveedor, nombre, apellido FROM proveedor Group by nombre"); 
                    while ($row = mysqli_fetch_assoc($resultado)) { ?> 
                        <option value="<?php echo $row['id_proveedor'];?>" <?php if($row['id_proveedor'] == $row_registro['proveedor_id']){ echo 'selected';} ?>> <?php echo $row['nombre'] . ' ' . $row['apellido'];?> </option> 
                    <?php
                    }
                ?>
            </select>
            <br>

            <label>Producto:</label>
            <select name="id_materia_prima" id="producto" onchange="reiniciarCampos()" required>
                <?php
                include_once ('conexion.php');
                $resultado = mysqli_query($conex, "SELECT id_materia_prima, nombre_mp, precio, nombre_marca FROM materia_prima
                                                            INNER JOIN marca_mp ON marca_mp.id_marca = materia_prima.marca_id
                                                            ORDER BY nombre_mp" );
                while ($row = mysqli_fetch_assoc($resultado)) { ?>
                    <option value="<?php echo $row['id_materia_prima'];?>" data-precio="<?php echo $row['precio']; ?>" <?php if($row['id_materia_prima'] == $row_registro['materia_prima_id']){ echo 'selected';} ?>>
                        <?php echo $row['nombre_mp'] . " - " . $row['nombre_marca']; ?>
                    </option>
                <?php
                }
                ?>
            </select>
            <br>

            <label>Marca:</label>
            <input type="text" name="marca_pc" id="marca" value="<?php echo $row_registro['marca_pc']; ?>" readonly>
            <br>

            <label>Cantidad:</label>
            <input type="number" id="cantidad" name="cantidad" min="0" value="0" required>
            <br>

            <label>Precio Unitario:</label>
            <input type="text" id="precio" name="precio" value="<?php echo $row_registro['precio']; ?>" readonly>
            <br>

            <label>Total Compra:</label>
            <input type="number" step="0.01" name="total" id="total" value="0" readonly>
            <br>

            <label>Fecha:</label>
            <input type="date" name="fecha" value="<?php echo $row_registro['fecha']; ?>">
            <br>

            <input type="submit" name="enviar" value="Grabar">
            <input type="submit" value="Cancelar" form="volver">
        </form>
        <form action="pedido_compra_panel.php" id="volver" method="post"></form>
    </div>
</body>
</html>