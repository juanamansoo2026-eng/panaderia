<?php
$fecha_actual = date('Y-m-d');
if (isset($_REQUEST['id_inventario'])) {
    $id_inventario = $_REQUEST['id_inventario'];
    require_once("conexion.php"); //conexion a la bd
    $registro= mysqli_query($conex, "SELECT id_inventario, materia_prima_id, nombre_mp, inventario.marca_id, marca_mp.nombre_marca, cantidad_ingreso_mp, cantidad_salida_mp, stock_total, fecha_modi
    FROM inventario
    INNER JOIN materia_prima ON materia_prima.id_materia_prima = inventario.materia_prima_id
    INNER JOIN marca_mp ON marca_mp.id_marca = inventario.marca_id
    WHERE id_inventario = $id_inventario")
    or die ("problema en la consulta".mysqli_error($conex));
    if ($registro) {
        $row_registro= mysqli_fetch_assoc($registro);
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>Modificación de Inventario de Materia Prima - Panadería M</title>

    <script>
        // Función para actualizar el Stock Total automáticamente
        function actualizarStock() {
            // Obtener el stock inicial desde PHP (el valor cargado desde la base de datos)
            var stockInicial = parseInt('<?php echo $row_registro['stock_total']; ?>') || 0; // Usar el valor PHP del stock actual
            
            // Obtener los valores de ingreso y salida
            var ingreso = parseInt(document.getElementsByName('cant_ingreso')[0].value) || 0;
            var salida = parseInt(document.getElementsByName('cant_salida')[0].value) || 0;
            
            // Calcular el stock total como: stock inicial + ingreso - salida
            var stockTotal = stockInicial + ingreso - salida;

            // Asignar el valor calculado al campo de Stock Total
            document.getElementsByName('stock_total')[0].value = stockTotal;
        }

        // Asegurarse de que el evento 'input' se asigne correctamente
        window.onload = function() {
            // Escuchar el cambio en los campos de ingreso y salida
            document.getElementsByName('cant_ingreso')[0].addEventListener('input', actualizarStock);
            document.getElementsByName('cant_salida')[0].addEventListener('input', actualizarStock);
        };
    </script>
</head>
<body>
    <div class="container" style="width: 600px">
        <h2 style="text-align: center">Modificación de Inventario de Materia Prima - Panadería M</h2>
        <form action="modi_inventario.php" method="post" autocomplete="off" required>
            <label>ID Inventario:</label>
            <input style="background:silver;" type="text" name="id_inventario" value="<?php echo $row_registro['id_inventario'];?>" readonly>
            <br>

            <label>Nombre de la Materia Prima:</label>
            <select name="nombre_mp" required>
                <?php
                    include_once('conexion.php'); 
                    $resultado = mysqli_query($conex, "SELECT id_materia_prima, nombre_mp FROM materia_prima"); 
                    while ($row = mysqli_fetch_assoc($resultado)) {?> 
                        <option value="<?php echo $row['id_materia_prima'];?>" <?php if($row['id_materia_prima'] == $row_registro['materia_prima_id']){ echo 'selected';} ?>> <?php echo $row['nombre_mp'];?> </option> 
                    <?php
                    }
                ?>
            </select>
            <br>

            <label>Marca:</label>
            <select name="nombre_marca" required>
                <?php
                    include_once('conexion.php'); 
                    $resultado = mysqli_query($conex, "SELECT id_marca, nombre_marca FROM marca_mp"); 
                    while ($row = mysqli_fetch_assoc($resultado)) {?> 
                        <option value="<?php echo $row['id_marca'];?>" <?php if($row['id_marca'] == $row_registro['marca_id']){ echo 'selected';} ?>> <?php echo $row['nombre_marca'];?> </option> 
                    <?php
                    }
                    mysqli_close($conex);
                ?>
            </select>
            <br>

            <label>Cantidad de Ingreso:</label>
            <input type="number" step="1" name="cant_ingreso" value="0" maxlength="3" required>
            <br>

            <label>Cantidad de Salida:</label>
            <input type="number" step="1" name="cant_salida" value="0" maxlength="3" required>
            <br>

            <label>Stock Total:</label>
            <input type="number" step="1" name="stock_total" value="<?php echo $row_registro['stock_total'];?>" maxlength="3" readonly>
            <br>

            <label>Fecha Modificación:</label>
            <input type="date" name="fecha_modi" value="<?php echo $fecha_actual; ?>" readonly>
            
            <input type="submit" name="enviar" value="Grabar">
            <input type="submit" value="Cancelar" form="volver">
        </form>

        <form action="inventario_panel.php" id="volver" method="post"></form>
    </div>
</body>
</html>