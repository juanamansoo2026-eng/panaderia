<?php
$scriptDirectory = trim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/')), '/');
$pathSegments = $scriptDirectory === '' ? [] : explode('/', $scriptDirectory);
if (($pathSegments[0] ?? '') === basename(__DIR__)) {
    array_shift($pathSegments);
}
$prefix = str_repeat('../', count($pathSegments));
$username = isset($_SESSION['username']) ? htmlspecialchars((string) $_SESSION['username'], ENT_QUOTES, 'UTF-8') : '';
?>
<link rel="stylesheet" href="<?php echo $prefix; ?>css/menu.css">
<nav class="nav-class" aria-label="Navegaci&oacute;n principal">
    <a class="link brand-link" href="<?php echo $prefix; ?>page1.php">
        <img class="logo" src="<?php echo $prefix; ?>img/logo_panaderia.png" alt="Panader&iacute;a M">
        <span>Panader&iacute;a "M"</span>
    </a>
    <section class="menu menu-desktop">
        <ul class="ul-links">
            <li class="li-links"><a class="link" href="<?php echo $prefix; ?>page1.php">Inicio</a></li>
            <li class="li-links"><a class="link" href="<?php echo $prefix; ?>empleados_panel.php">Empleados</a></li>
            <li class="li-links"><a class="link" href="<?php echo $prefix; ?>clientes_panel.php">Clientes</a></li>
            <li class="li-links"><a class="link" href="<?php echo $prefix; ?>proveedores_panel.php">Proveedores</a></li>
            <li class="li-links"><a class="link" href="<?php echo $prefix; ?>productos_panel.php">Productos</a></li>
            <li class="li-links"><a class="link" href="<?php echo $prefix; ?>gastos_administrativos_panel.php">Gastos Administrativos</a></li>
            <li class="li-links"><a class="link" href="<?php echo $prefix; ?>ventas_panel.php">Ventas</a></li>
            <li class="li-links"><a class="link" href="<?php echo $prefix; ?>materia_prima_panel.php">Materia Prima</a></li>
            <li class="li-links"><a class="link" href="<?php echo $prefix; ?>inventario_panel.php">Inventario</a></li>
        </ul>
    </section>
    <div class="nav-user">
        <?php if ($username !== ''): ?><span><?php echo $username; ?></span><?php endif; ?>
        <a class="link" href="<?php echo $prefix; ?>cerrar.php">Cerrar Sesi&oacute;n</a>
    </div>
</nav>
