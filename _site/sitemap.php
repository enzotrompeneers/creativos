<?php

include('lib/admin.php');

use  Brunelencantado\Sitemap\Sitemap;

$sitemap = new Sitemap($db, false);
// configure options
$sitemap->domain = $base_site;	// domain
$sitemap->languages = $languages;		// languages availables in sitemap
$sitemap->exclude_articles = array('inicio', 'sendmail', 'buscar', 'ficha', 'getpdf'); // articulos excluded in the mail pages listing

// Send XML to client
echo $sitemap->render();


// End of file