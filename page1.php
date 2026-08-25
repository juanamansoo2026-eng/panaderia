<?php
session_start(); // Iniciar la sesión
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale-1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,300italic,700,700italic">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/milligram/1.4.1/milligram.css">
    <title>Inicio</title>
</head>
<body>
    <div class="container" style="width: 800px; text-align: center;">
        <div>
            <br>
            <h5>Panaderia M</h5> 
            <?php if (isset($_SESSION['username'])): 
                
                    endif?>
                <h2>Bienvenido, <?php echo htmlspecialchars($_SESSION['username']); ?>.</h2>
                <a class="button" href="empleados_panel.php">Ver Panel de Empleados</a>
                <a class="button" href="clientes_panel.php">Ver Panel de Clientes</a>
                <a class="button" href="proveedores_panel.php">Ver Panel de Proveedores</a>
                <a class="button" href="productos_panel.php">Ver Panel de Productos</a>
                <a class="button" href="gastos_administrativos_panel.php">Ver Panel de Gastos Administrativos</a>
                <a class="button button-outline" href="cerrar.php">Cerrar Sesión</a>
        </div>
    </div>
</body>
</html>