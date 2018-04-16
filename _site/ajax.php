<?php
include('lib/admin.php');
include('lib/logic.php');
$viewFile = 'lib/views/'.$view.'.php';
if (file_exists($viewFile)) { include($viewFile); }



// End file 