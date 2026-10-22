<?php
$prefix = file_exists('css/footer.css') ? '' : (file_exists('../css/footer.css') ? '../' : '../../');
?>
<link rel="stylesheet" href="<?php echo $prefix; ?>css/footer.css">

<footer>
    <p class="footer">© <?php echo date('Y'); ?> Panadería M — Artesanal & Moderna</p>
    <p class="footer">Sistema de Gestión de Panadería</p>
</footer>
