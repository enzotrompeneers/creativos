<?php
// Generic intenral content pages

// ID para el <body>
$bodyid     = $pagina;
$bodyclass  = '';

require_once dirname(__FILE__) . '/inc/form_arrays.php';

// Imágenes
$images = get_images_articulos($pagina);
$mainCols = (!empty($images))?8:12;

// Files
$files = get_files_articulos($pagina) ;

// Cargamos las vistas
require_once dirname(__FILE__) . '/inc/html_head.php';
// Cargamos la vista si existe, o si no cargamos vista de contenido genérico
if (file_exists( dirname(__FILE__) . '/inc/web/'.$pagina.'.php')){
	require_once dirname(__FILE__) . '/inc/attn.php';
	require_once dirname(__FILE__) . '/inc/web/'.$pagina.'.php';
} else {
	require_once dirname(__FILE__) . '/inc/attn.php';
	require_once dirname(__FILE__) . '/inc/web/contenido.php';
}
require_once dirname(__FILE__) . '/inc/footer.php';



// End file
