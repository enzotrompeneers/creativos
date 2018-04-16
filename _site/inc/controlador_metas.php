<?php // Controlador de meta tags para todas las páginas

// Valores por defecto, provenientes de artículos
$meta				= array();
$meta['id']			= $id;
$meta['pagina']		= $pagina;
$meta['titulo']		= title($pagina);
$meta['descr']		= (meta($pagina,'descr')=='')?meta('inicio','descr'):meta($pagina,'descr');
$meta['key']		= (meta($pagina,'key')=='')?meta('inicio','key'):meta($pagina,'key');
$meta['img']		= 'images/og-logo.jpg';
$meta['url']		= curpageURL();

$sizes = getimagesize($meta['img']);
$meta['imgWidth'] = $sizes[0];
$meta['imgHeight'] = $sizes[1];

///*** Condiciones especiales ***///

// Viviendas ficha
if ($pagina=='viviendas' && $id) {
	$meta['titulo']		= $tituloVivienda.' - '.webConfig('nombre');
	$meta['key']		= keywords($meta['titulo']);
	$meta['descr']		= strip_tags(shorten_text($descrVivienda,400));
	$meta['img']		= first_image('viviendas',$id,'l');
	$meta['url']		= curpageURL();
	
	$sizes = getimagesize($meta['img']);
	$meta['imgWidth'] = $sizes[0];
	$meta['imgHeight'] = $sizes[1];	
}

if ($pagina=='logic') {
	$meta['titulo'] = webConfig('nombre');
}


// printout($meta);

// End file