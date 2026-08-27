<?php
require_once("../../conexion.php");

if (isset($_REQUEST['Guardar'])){
    $id_empleado = filter_input(INPUT_POST, 'id_empleado', FILTER_VALIDATE_INT);
    $id_rol = filter_input(INPUT_POST, 'id_rol', FILTER_VALIDATE_INT);
    $nombre = trim($_POST['nombre'] ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    $dni = trim($_POST['dni'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $fecha_modi = date('Y-m-d');

    if (!$id_empleado || !$id_rol || $nombre === '' || $apellido === '' || $dni === '' || $telefono === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die('Los datos del empleado no son válidos.');
    }

    $consulta = mysqli_prepare($conex, "UPDATE empleados
        SET nombre = ?, apellido = ?, dni = ?, telefono = ?, id_rol = ?, email = ?, fecha_modi = ?
        WHERE id_empleado = ?");
    mysqli_stmt_bind_param($consulta, 'ssssissi', $nombre, $apellido, $dni, $telefono, $id_rol, $email, $fecha_modi, $id_empleado);
    $resultado_modi = mysqli_stmt_execute($consulta);

    if ($resultado_modi){
        header('Location: empleados_panel.php');
        exit();  
        } else {
        echo 'Se produjo un error al modificar: ' . mysqli_error($conex);
    }      
    mysqli_close($conex);
}

?>