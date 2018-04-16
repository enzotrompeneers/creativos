<?php
// Inicio

// ID para el <body>
$bodyid     = 'inicio';
$bodyclass  = '';
$pagina     = 'inicio';

use Brunelencantado\Projects\ProjectRepository;
use Brunelencantado\Projects\ProjectSliderRepository;

$oProjects = new ProjectRepository($db);
$aProjects = $oProjects->getList();

$oSliderProjects = new ProjectSliderRepository($db);
$aSliderProjects = $oSliderProjects->getList();

//printout($aSliderProjects);

// Cargamos las vistas
require_once dirname(__FILE__) . '/inc/html_head.php';
require_once dirname(__FILE__) . '/inc/web/inicio.php';
require_once dirname(__FILE__) . '/inc/footer.php';

// End file
