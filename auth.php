<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (empty($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

// Si el usuario tiene una clave temporal (alta o restablecimiento), debe cambiarla antes de seguir
if (!empty($_SESSION['cambiar_clave']) && basename($_SERVER['SCRIPT_NAME']) !== 'cambiar_clave.php') {
    header('Location: cambiar_clave.php');
    exit();
}
