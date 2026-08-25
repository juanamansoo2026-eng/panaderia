<?php
    $servidor= "localhost"; // el servidor del xampp es "localhost"
    $usuario= "root"; // En xampp, el usuario por defecto es 'root'.
    $pass= ""; // la contraseña para conectarse a la base de datos, en este caso hacemos la bd sin contraseñas
    $basedatos="original"; // El nombre de la bd a la que nos queremos conectar

    $conex= mysqli_connect($servidor,$usuario,$pass,$basedatos); // Variable "conexión" con la base de datos usando los parámetros definidos (servidor, usuario, contraseña, y nombre de la base de datos). La función mysqli_connect() devuelve un mensaje de conexión si tiene éxito, o false en caso de error.

    if ($conex->connect_error) {
        die("Conexión fallida: " . $conex->connect_error);
    }
    mysqli_query($conex,"SET NAMES 'UTF8'");

?>