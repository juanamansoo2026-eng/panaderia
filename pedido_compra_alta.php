<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,300italic,700,700italic">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/milligram/1.4.1/milligram.css">
    <title>Alta Pedido de Compra - Panadería M</title>
    <script>
        // Función para actualizar el precio, total de la compra y la marca
        function actualizarPrecio() {
            var cantidad = document.getElementById("cantidad").value;
            var productoSeleccionado = document.getElementById("producto").options[document.getElementById("producto").selectedIndex];

            // Obtener el precio del producto
            var precio_unitario = productoSeleccionado.getAttribute("data-precio");
            document.getElementById("precio").value = precio_unitario;

            // Calcular el total
            var total = cantidad * precio_unitario;
            document.getElementById("total").value = total.toFixed(2); // Formato con 2 decimales

            // Obtener la marca del producto seleccionado
            var marca = productoSeleccionado.innerText.split(" - ")[1]; // Obtener la parte después del guion
            document.getElementById("marca").value = marca; // Asignar la marca al campo de texto
        }
    </script>
</head>
<body>
    <div class="container" style="width: 600px">
        <h2 style="text-align: center">Alta Pedido de Compra - Panadería M</h2>
        <form action="alta_pedido_compra.php" method="post" autocomplete="off" required>

            <label>Proveedor:</label>
            <select name="id_proveedor" required>
                <?php
                    include_once ('conexion.php'); 
                    $resultado = mysqli_query($conex,"SELECT id_proveedor, nombre, apellido FROM proveedor Group by nombre"); 
                ?>
                <option value="">Seleccione..</option>
                <?php
                while ($row = mysqli_fetch_assoc($resultado)) { ?> 
                    <option value="<?php echo $row['id_proveedor'];?>"> <?php echo $row['nombre'] . ' ' . $row['apellido'];?></option> 
                <?php
                }
                ?>
            </select>
            <br>

            <label>Producto:</label>
            <select name="id_materia_prima" id="producto" onchange="actualizarPrecio()" required>
                <?php
                include_once ('conexion.php');
                $resultado = mysqli_query($conex, "SELECT id_materia_prima, nombre_mp, precio, nombre_marca FROM materia_prima
                                                            INNER JOIN marca_mp ON marca_mp.id_marca = materia_prima.marca_id
                                                            ORDER BY nombre_mp" );
                ?>
                <option value="">Seleccione un producto</option>
                <?php
                while ($row = mysqli_fetch_assoc($resultado)) { ?>
                    <option value="<?php echo $row['id_materia_prima']; ?>" data-precio="<?php echo $row['precio']; ?>"><?php echo $row['nombre_mp'] . " - " . $row['nombre_marca']; ?>
                </option>
                <?php
                }
                ?>
            </select>
            <br>

            <label>Marca:</label>
            <input type="text" name="marca_pc" id="marca" readonly>
            <br>

            <label>Cantidad:</label>
            <input type="number" id="cantidad" name="cantidad" min="0" required oninput="actualizarPrecio()">
            <br>

            <label>Precio Unitario:</label>
            <input type="text" id="precio" name="precio" readonly>
            <br>

            <label>Total Compra:</label>
            <input type="text" id="total" name="total" readonly>
            <br>

            <label>Fecha:</label>
            <input type="date" name="fecha" required>
            <br>

            <input type="submit" name="enviar" value="Grabar">
            <input type="submit" value="Cancelar" form="volver">
        </form>
        <form action="pedido_compra_panel.php" id="volver" method="post"></form>
    </div>
</body>
</html>