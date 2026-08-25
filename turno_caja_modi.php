<?php
$fecha_actual = date('Y-m-d');
if (isset($_REQUEST['id_turno'])) {
    $id_turno_caja = $_REQUEST['id_turno'];
    require_once("conexion.php"); //conexion a la bd
    $registro= mysqli_query($conex, "SELECT id_turno, empleado_id, empleados.nombre, empleados.apellido, turno, turno_caja.fecha, total_recaudado
    FROM turno_caja
    INNER JOIN empleados ON empleados.id_empleado = turno_caja.empleado_id
    WHERE id_turno = $id_turno_caja")
    or die ("problema en la consulta".mysqli_error($conex)); //mysqli_error me muestra un mensaje con el problema y su linea, "or die" es si sale bien no pasa nada, "O die" o te sale el mensaje error
    if ($registro) {
        $row_registro= mysqli_fetch_assoc($registro); //carga cada columna/campo como id, nombre, etc, lo guardad aca
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
    <title>Modificar de Turno de Caja - Panadería M</title>
</head>
<body>
    <div class="container" style="width: 600px">
    <h2 style="text-align: center">Modificar Turno de Caja - Panadería M</h2>
    <form action="modi_turno_caja.php" method="post" autocomplete="off" required>
        
        <label>ID Turno Caja:</label>
        <input style="background:silver;" type="text" name="id_turno" value="<?php echo $row_registro['id_turno'];?>" readonly>
        <br>

        <label>Empleado:</label>
            <select name="id_empleado" required>
                <?php
                    include_once ('conexion.php'); 
                    $resultado = mysqli_query($conex,"SELECT id_empleado, nombre, apellido FROM empleados Group by nombre"); 
                    while ($row = mysqli_fetch_assoc($resultado)) { ?> 
                        <option value="<?php echo $row['id_empleado'];?>" <?php if($row['id_empleado'] == $row_registro['empleado_id']){ echo 'selected';} ?>> <?php echo $row['nombre'] . ' ' . $row['apellido'];?> </option> 
                    <?php
                    }
                ?>
            </select>
        <br>

        <label>Turno:</label>
            <select name="turno" required>
                <option value="Mañana" <?php if($row_registro['turno'] == 'Mañana') echo 'selected'; ?>>Mañana</option>
                <option value="Tarde" <?php if($row_registro['turno'] == 'Tarde') echo 'selected'; ?>>Tarde</option>
            </select>
        <br>

        <label>Fecha:</label>
        <input type="date" name="fecha" value="<?php echo $row_registro['fecha']; ?>" readonly>
        <br>

        <label>Total Recaudado:</label>
        <input type="number" step="0.01" name="total_recaudado" value="<?php echo $row_registro['total_recaudado']; ?>" required>
        <br>

        <input type="submit" name="enviar" value="Grabar">
        <input type="submit" value="Cancelar" form="volver">
    </form>
    <form action="turno_caja_panel.php" id="volver" method="post"></form>
</body>
</html>