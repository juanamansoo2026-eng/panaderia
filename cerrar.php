<?php
    session_start();
    session_destroy(); // Destruir la sesión
    header('Location: index.php'); //Redirige al índice
    exit();
?>