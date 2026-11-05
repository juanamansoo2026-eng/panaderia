<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/panaderia-moderna.css">
    <title>Inicio - Panadería M</title>
    <style>
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }
        .dashboard-card {
            background: #FFFFFF;
            border: 1px solid var(--bakery-border);
            border-radius: 14px;
            padding: 22px;
            text-align: center;
            box-shadow: var(--bakery-shadow-sm);
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-between;
        }
        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--bakery-shadow);
            border-color: var(--bakery-primary);
        }
        .dashboard-card h3 {
            margin: 12px 0 8px 0;
            font-size: 1.15rem;
        }
        .dashboard-card p {
            font-size: 0.88rem;
            color: var(--bakery-text-muted);
            margin-bottom: 15px;
        }
        .icon-badge {
            font-size: 2.2rem;
            background: #FFF8EE;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>
<body>

    <?php include("menu.php"); ?>

    <div class="container" style="max-width: 1000px; text-align: center;">
        <div style="margin-bottom: 10px;">
            <span style="font-size: 0.9rem; text-transform: uppercase; letter-spacing: 1px; color: var(--bakery-primary); font-weight: 600;">Panel Control Principal</span>
            <h2>Bienvenido, <?php echo htmlspecialchars($_SESSION['username']); ?></h2>
            <p style="color: var(--bakery-text-muted); font-size: 0.95rem;">Selecciona un módulo para gestionar tu panadería artesanal</p>
        </div>

        <div class="dashboard-grid">
            <div class="dashboard-card">
                <div class="icon-badge"><svg class="ui-icon" aria-hidden="true" viewBox="0 0 24 24"><path d="M4 10c0-2 1.8-3.5 4-3.5S12 8 12 10v9H4z"/><path d="M12 10c0-2 1.8-3.5 4-3.5S20 8 20 10v9h-8z"/></svg></div>
                <h3>Productos</h3>
                <p>Gestión del catálogo de productos y precios</p>
                <a class="button" href="productos_panel.php">Ver Productos</a>
            </div>

            <div class="dashboard-card">
                <div class="icon-badge"><svg class="ui-icon" aria-hidden="true" viewBox="0 0 24 24"><path d="M5 8h14l-1 12H6L5 8z"/><path d="M9 8a3 3 0 0 1 6 0"/></svg></div>
                <h3>Ventas</h3>
                <p>Registrar ventas y consultar historial</p>
                <a class="button" href="ventas_panel.php">Ver Ventas</a>
            </div>

            <div class="dashboard-card">
                <div class="icon-badge"><svg class="ui-icon" aria-hidden="true" viewBox="0 0 24 24"><path d="M16 20v-1a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v1"/><circle cx="10" cy="8" r="3"/><path d="M20 20v-1a4 4 0 0 0-3-4"/></svg></div>
                <h3>Clientes</h3>
                <p>Base de datos de clientes y contactos</p>
                <a class="button" href="clientes_panel.php">Ver Clientes</a>
            </div>

            <div class="dashboard-card">
                <div class="icon-badge"><svg class="ui-icon" aria-hidden="true" viewBox="0 0 24 24"><circle cx="12" cy="14" r="5"/><path d="M7 14H5a3 3 0 1 1 1-5.8A4 4 0 0 1 14 6a3 3 0 1 1 4 4h-1"/></svg></div>
                <h3>Empleados</h3>
                <p>Personal, roles y turnos de trabajo</p>
                <a class="button" href="empleados_panel.php">Ver Empleados</a>
            </div>

            <div class="dashboard-card">
                <div class="icon-badge"><svg class="ui-icon" aria-hidden="true" viewBox="0 0 24 24"><path d="M3 6h11v11H3zM14 10h4l3 3v4h-7z"/><circle cx="7" cy="19" r="1.5"/><circle cx="18" cy="19" r="1.5"/></svg></div>
                <h3>Proveedores</h3>
                <p>Registro de proveedores y pedidos</p>
                <a class="button" href="proveedores_panel.php">Ver Proveedores</a>
            </div>

            <div class="dashboard-card">
                <div class="icon-badge"><svg class="ui-icon" aria-hidden="true" viewBox="0 0 24 24"><path d="m3 7 9-4 9 4v10l-9 4-9-4zM3 7l9 4 9-4m-9 4v10"/></svg></div>
                <h3>Inventario</h3>
                <p>Stock y materia prima almacenada</p>
                <a class="button" href="inventario_panel.php">Ver Inventario</a>
            </div>

            <div class="dashboard-card">
                <div class="icon-badge"><svg class="ui-icon" aria-hidden="true" viewBox="0 0 24 24"><path d="M12 21V4m0 7c-3 0-5-2-5-5 3 0 5 2 5 5zm0 4c3 0 5-2 5-5-3 0-5 2-5 5z"/></svg></div>
                <h3>Materia Prima</h3>
                <p>Insumos y materias primas de panadería</p>
                <a class="button" href="materia_prima_panel.php">Ver Materia Prima</a>
            </div>

            <div class="dashboard-card">
                <div class="icon-badge"><svg class="ui-icon" aria-hidden="true" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M15 8.5c-.7-.6-1.7-1-3-1-1.7 0-3 .8-3 2s1.3 2 3 2 3 .8 3 2-1.3 2-3 2c-1.3 0-2.3-.4-3-1M12 6v12"/></svg></div>
                <h3>Gastos</h3>
                <p>Control de gastos administrativos y categorías</p>
                <a class="button" href="gastos_administrativos_panel.php">Ver Gastos</a>
            </div>
        </div>

        <div style="margin-top: 35px;">
            <a class="button button-outline" href="cerrar.php"><svg class="ui-icon" aria-hidden="true" viewBox="0 0 24 24"><rect x="4" y="10" width="16" height="11" rx="2"/><path d="M8 10V7a4 4 0 1 1 8 0v3"/></svg> Cerrar Sesión</a>
        </div>
    </div>

    <?php include("footer.php"); ?>
</body>
</html>