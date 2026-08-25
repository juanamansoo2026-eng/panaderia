<?php
require_once("conexion.php");

if (isset($_REQUEST['Guardar'])){
    $id_empleado = $_REQUEST['id_empleado'];
    $email = $_REQUEST['email'];
    $fecha_modi = date('Y-m-d');

    $resultado_modi = mysqli_query($conex,"UPDATE empleados SET email = '$email', fecha_modi = '$fecha_modi'
    WHERE id_empleado = $id_empleado") 
    or die ("problema en la consulta".mysqli_error($conex));
    if ($resultado_modi){
        echo '<h3>Se guardaron correctamente los datos</h3>';
        header('Refresh: 1; url=empleados_panel.php');
        exit();  
        } else {
        echo 'Se produjo un error al modificar';
    }      
    mysqli_close($conex);
}

?>