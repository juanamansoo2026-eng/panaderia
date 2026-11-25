<?php
require_once __DIR__ . '/auth.php';
?>
<?php
require_once("conexion.php");

// Modificación de Usuario: solo se edita el correo y se actualiza la fecha de última modificación
if (isset($_REQUEST['Guardar'])){
    $id_empleado = filter_input(INPUT_POST, 'id_empleado', FILTER_VALIDATE_INT);
    $email = trim($_POST['email'] ?? '');
    $fecha_modi = date('Y-m-d');

    if (!$id_empleado || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die('El correo no es válido.');
    }

    $consulta = mysqli_prepare($conex, "UPDATE empleados
        SET email = ?, fecha_modi = ?
        WHERE id_empleado = ?");
    mysqli_stmt_bind_param($consulta, 'ssi', $email, $fecha_modi, $id_empleado);
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
