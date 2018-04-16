<?php
/*************************************************

Controls logic for user administration
las updated: 28/10/2012
For behappy2.com

*************************************************/

$error = '';
$controller = 'lib/controllers/'.$clave.'.php';
$fourofour = 'lib/controllers/404.php';
;
// 404 if no controller file

$controller = (file_exists($controller))?$controller:$fourofour;
include($controller);

// end file