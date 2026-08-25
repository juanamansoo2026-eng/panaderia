<?php
// Conexión a la base de datos
require_once("conexion.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recibimos los datos enviados por AJAX
    $id_empleado = $_POST['id_empleado'];
    $baja = $_POST['baja'];  // 1 o 0 (Activo o No Activo)

    // Fecha actual en formato Y-m-d
    $fecha_actual = date('Y-m-d'); 

    // Si "baja" es 0 (No Activo), actualizamos la fecha de baja con la fecha actual.
    if ($baja == 0) {
        $query = "UPDATE empleados SET baja = ?, fecha_baja = ? WHERE id_empleado = ?";
        $stmt = $conex->prepare($query);
        $stmt->bind_param("isi", $baja, $fecha_actual, $id_empleado);  // Asignamos la fecha actual
    } else {
        // Si "baja" es 1 (Activo), ponemos la fecha de baja como NULL
        $query = "UPDATE empleados SET baja = ?, fecha_baja = NULL WHERE id_empleado = ?";
        $stmt = $conex->prepare($query);
        $stmt->bind_param("ii", $baja, $id_empleado);
    }

    // Ejecutar la consulta
    if ($stmt->execute()) {
        echo 'success';  // Respondemos con éxito si la actualización fue exitosa
    } else {
        echo 'error';  // Respondemos con error si hubo algún problema
    }

    // Cerrar la conexión
    $stmt->close();
    mysqli_close($conex);
} else {
    // Si no es un POST, devolvemos error
    echo 'Invalid Request';
}
?>