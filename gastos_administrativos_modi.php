<?php
if (isset($_REQUEST['id_gasto'])) {
    $id_gasto = $_REQUEST['id_gasto'];
    require_once("conexion.php"); //conexion a la bd
    $registro = mysqli_query($conex, "SELECT id_gasto, turno_id, turno_caja.turno, gastos_administrativos.descripcion, categoria_gasto_id, nombre_categoria, monto, empleado, gastos_administrativos.fecha
    FROM gastos_administrativos
    INNER JOIN turno_caja ON turno_caja.id_turno = gastos_administrativos.turno_id
    INNER JOIN categoria_gasto ON categoria_gasto.id_categoria_gasto = gastos_administrativos.categoria_gasto_id
    WHERE id_gasto = $id_gasto")
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
    <title>Modificación Gastos Administrativos - Panadería M</title>
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
    <div class="container" style="width: 601px">
    <h2 style="text-align: center">Modificación Gastos Administrativos - Panadería M</h2>
    <form action="modi_gastos_administrativos.php" method="post" autocomplete="off" required>

        <label>ID Gasto:</label>
        <input style="background:silver;" type="text" name="id_gasto" value="<?php echo $row_registro['id_gasto'];?>" readonly>
        <br>
        <label>Descripcion:</label>
        <input type="text" name="descripcion" maxlength="100" value="<?php echo $row_registro['descripcion']; ?>" required>
        <br>

        <label>Categoría:</label>
            <select name="categoria_gasto_id" required>
                <?php
                    include_once ('conexion.php'); 
                    $resultado = mysqli_query($conex,"SELECT id_categoria_gasto, nombre_categoria FROM categoria_gasto Group by nombre_categoria"); 
                    while ($row = mysqli_fetch_assoc($resultado)) { ?> 
                        <option value="<?php echo $row['id_categoria_gasto'];?>" <?php if($row['id_categoria_gasto'] == $row_registro['categoria_gasto_id']){ echo 'selected';} ?>> <?php echo $row['nombre_categoria'];?> </option> 
                    <?php
                    }
                ?>
            </select>
            <br>

        <label>Monto:</label>
        <input type="number" step="0.01" name="monto" value="<?php echo $row_registro['monto']; ?>" required>
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
                <option value="<?php echo $row['id_turno']; ?>" data-empleado="<?php echo $row['nombre'] . ' ' . $row['apellido']; ?>"
                 <?php if($row['id_turno'] == $row_registro['turno_id']){ echo 'selected';} ?>>
                    <?php echo $row['turno'] . " - " . $row['nombre'] . " " . $row['apellido']; ?>
                </option>
            <?php
            }
            ?>
        </select>
        <br>
        
        <label>Empleado:</label>
        <input type="text" name="empleado" id="empleado" value="<?php echo $row_registro['empleado']; ?>" readonly>
        <br>

        <label>Fecha Pago:</label>
        <input type="date" name="fecha" value="<?php echo $row_registro['fecha']; ?>" required>
        <br>

        <input type="submit" name="enviar" value="Grabar">
        <input type="submit" value="Cancelar" form="volver">
    </form>
    <form action="gastos_administrativos_panel.php" id="volver" method="post"></form>
</body>
</html>