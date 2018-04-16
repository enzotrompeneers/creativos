<?php
// Generic intenral content pages

// ID para el <body>
$pagina = 'login';
$bodyid     = $pagina;
$bodyclass  = '';

// Cargamos las vistas
require_once dirname(__FILE__) . '/inc/html_head.php';
require_once dirname(__FILE__) . '/inc/web/'.$pagina.'.php';
require_once dirname(__FILE__) . '/inc/footer.php';



// End file
