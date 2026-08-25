<?php
$fecha_actual = date('Y-m-d'); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,300italic,700,700italic">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/milligram/1.4.1/milligram.css">
    <title>Alta Turno de Caja - Panadería M</title>
</head>
<body>
    <div class="container" style="width: 600px">
    <h2 style="text-align: center">Alta Turno de Caja - Panadería M</h2>
    <form action="alta_turno_caja.php" method="post" autocomplete="off" required>

        <label>Empleado:</label>
            <select name="id_empleado" required>
                <?php
                    include_once ('conexion.php'); 
                    $resultado = mysqli_query($conex,"SELECT id_empleado, nombre, apellido FROM empleados Group by nombre"); 
                    ?>
                    <option value="">Seleccione..</option>
                    <?php
                    while ($row = mysqli_fetch_assoc($resultado)) { ?> 
                        <option value="<?php echo $row['id_empleado'];?>"> <?php echo $row['nombre'] . ' ' . $row['apellido'];?></option> 
                    <?php
                    }
                ?>
            </select>
        <br>

        <label>Turno:</label>
            <select name="turno" required>
                <option>Mañana</option>
                <option>Tarde</option>
            </select>
        <br>

        <label>Fecha:</label>
        <input type="date" name="fecha" value="<?php echo $fecha_actual; ?>" readonly>
        <br>

        <label>Total Recaudado:</label>
        <input type="number" step="0.01" name="total_recaudado" required>
        <br>

        <input type="submit" name="enviar" value="Grabar">
        <input type="submit" value="Cancelar" form="volver">
    </form>
    <form action="turno_caja_panel.php" id="volver" method="post"></form>
</body>
</html>