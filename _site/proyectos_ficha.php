<?php 
$clave = $_GET['clave'];

use Brunelencantado\Projects\ProjectRepository;
use Brunelencantado\Projects\Project;

$oProjects = new ProjectRepository($db);
$aProjects = $oProjects->getList($clave);

$oProject = new Project($db, $clave);
$filter = 3;
$aProject = $oProject->getDetails($clave, $filter);

// Cargamos las vistas
require_once dirname(__FILE__) . '/inc/html_head.php';
require_once dirname(__FILE__) . '/inc/web/proyectos_ficha.php';
require_once dirname(__FILE__) . '/inc/footer.php';
