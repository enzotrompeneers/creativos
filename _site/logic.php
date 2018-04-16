<?php
// Logic
include('lib/admin.php');
// ID para el <body>
$bodyid     = 'logic';
$bodyclass  = '';

$error = '';

$controller = req('controller');
$controller = 'lib/controllers/'.$controller.'.php';
$fourofour = 'lib/controllers/404.php';	// 404 if no controller file
include($controller);

$controller = (file_exists($controller))?$controller:$fourofour;

// Cargamos las vistas
$viewPath = 'lib/views/'.$view.'.php';
if ($pagina=='ajax' && file_exists($viewPath)) {
	include($viewPath);
} else {
	require_once dirname(__FILE__) . '/inc/html_head.php';
	require_once dirname(__FILE__) . '/inc/web/logic.php';
	require_once dirname(__FILE__) . '/inc/footer.php';
}





// End file