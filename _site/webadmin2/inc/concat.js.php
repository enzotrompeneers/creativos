<?php
// Concatenates all js files into one
set_include_path('../../');
require_once("../../lib/admin.php");
// error_reporting(E_ALL); 
header('Content-type: application/javascript');




$files = $jsScripts->links;
 
foreach ($files as $file) {
  include($file);
}

// End of file