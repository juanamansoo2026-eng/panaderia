<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,300italic,700,700italic">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/milligram/1.4.1/milligram.css">
    <title>Alta Gastos Administrativos - Panadería M</title>
    <script>
        function actualizarEmpleado() {
            var turnoSeleccionado = document.getElementById("turno").options[document.getElementById("turno").selectedIndex];
            // Obtener el nombre del empleado desde el atributo data-empleado
            var empleado = turnoSeleccionado.getAttribute("data-empleado");
            document.getElementById("empleado").value = empleado; // Asignar el nombre del empleado al campo de texto
        }
    </script>
</head>
<body>
    <div class="container" style="width: 600px">
    <h2 style="text-align: center">Alta Gastos Administrativos - Panadería M</h2>
    <form action="alta_gastos_administrativos.php" method="post" autocomplete="off" required>
        
        <label>Descripcion:</label>
        <input type="text" name="descripcion" maxlength="100" required>
        <br>

        <label>Categoría:</label>
        <select name="categoria_gasto_id" required>
            <?php
                include_once ('conexion.php'); 
                $resultado = mysqli_query($conex,"SELECT id_categoria_gasto, nombre_categoria FROM categoria_gasto GROUP BY nombre_categoria"); 
            ?>
            <option value="">Seleccione..</option>
            <?php
                while ($row = mysqli_fetch_assoc($resultado)) { ?> 
                    <option value="<?php echo $row['id_categoria_gasto'];?>"> <?php echo $row['nombre_categoria'];?></option> 
            <?php } ?>
        </select>
        <br>

        <label>Monto:</label>
        <input type="number" step="0.01" name="monto" required>
        <br>

        <label>Turno:</label>
        <select name="turno_id" id="turno" onchange="actualizarEmpleado()" required>
            <?php
            include_once ('conexion.php');
            $resultado = mysqli_query($conex, "SELECT turno_caja.id_turno, turno_caja.turno, empleados.nombre, empleados.apellido
                                                FROM turno_caja
                                                INNER JOIN empleados ON turno_caja.empleado_id = empleados.id_empleado
                                                ORDER BY turno");
            ?>
            <option value="">Seleccione un Turno</option>
            <?php
            while ($row = mysqli_fetch_assoc($resultado)) { ?>
                <option value="<?php echo $row['id_turno']; ?>" data-empleado="<?php echo $row['nombre'] . ' ' . $row['apellido']; ?>">
                    <?php echo $row['turno'] . " - " . $row['nombre'] . " " . $row['apellido']; ?>
                </option>
            <?php
            }
            ?>
        </select>
        <br>

        <label>Empleado:</label>
        <input type="text" name="empleado" id="empleado" readonly>
        <br>

        <label>Fecha Pago:</label>
        <input type="date" name="fecha" required>
        <br>

        <input type="submit" name="enviar" value="Grabar">
        <input type="submit" value="Cancelar" form="volver">
    </form>
    <form action="gastos_administrativos_panel.php" id="volver" method="post"></form>
</body>
</html>