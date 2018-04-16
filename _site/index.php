<?php 
// Routes all calls from .htaccess
include('lib/admin.php');

$metas = [];

$pagina = ($pagina) ? $pagina : $slug;

// Get page
if (file_exists($pagina.'.php')) {
	include($pagina.'.php');
} else {
	include('contenido.php');
}

// End file