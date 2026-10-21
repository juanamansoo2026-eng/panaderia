<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/menu.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>

<body>

<nav class="nav-class"> 
    
    <!-- LOGO IZQUIERDA -->
    <a class="link" href="login.php">
        <img class="logo" src="img/logo_panaderia.png" alt="">
    </a>

    <!-- MENÚ NORMAL (ESCRITORIO) -->
    <section class="menu menu-desktop">
        <ul class="ul-links">
            <li class="li-links"> <a class="link" href="page1.php">Inicio</a></li>
            <li class="li-links"> <a class="link" href="templates/empleados/empleados_panel.php">Empleados</a></li>
            <li class="li-links"> <a class="link" href="templates/clientes/clientes_panel.php">Clientes</a></li>
            <li class="li-links"> <a class="link" href="templates/proveedores/proveedores_panel.php">Proveedores</a></li>
            <li class="li-links"> <a class="link" href="templates/productos/productos_panel.php">Productos</a></li>
            <li class="li-links"> <a class="link" href="templates/gastos_administrativos/gastos_administrativos_panel.php">Gastos Administrativos</a></li>
            <?php if (isset($_SESSION['username'])): 
                
                    endif?>
            <h2>Usuario: <?php echo htmlspecialchars($_SESSION['username']); ?>.</h2>
            <br>
            <!-- <h2>Rol: <?ph echo htmlspecialchars($_SESSION['nombre_rol']); ?>.</h2> -->

            <li class="li-links"> <a class="link" href="cerrar.php">Cerrar Sesión</a></li>
        </ul>
    </section>

</nav>


<div class="btnwapp">
    <div class="contenedor-logo">
        <a href="https://www.facebook.com/zuviria8037">
            <img class="img-logos" src="images/logofacebook.png" title="Facebook">
        </a>
        <a href="https://www.instagram.com/instituto.zuviria/">
            <img class="img-logos" src="images/logoinstagram.png" title="Instagram">
        </a>
        <a href="https://api.whatsapp.com/send?phone=5493876308810" target="_blank">
            <img class="img-logos" src="images/logowhatsapp.png" title="Whatsapp">
        </a>
    </div>
</div>

</body>
</html>
