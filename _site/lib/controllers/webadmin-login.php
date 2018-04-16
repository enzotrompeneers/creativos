<?php

// User login

// Output
$title = 'Login';
$view = 'webadmin-login';	
$errorMessage = '';

use \Brunelencantado\Login\WebadminLogin;

// If form is sent
if ($_SERVER['REQUEST_METHOD'] == "POST" && !empty($_POST['username']) && !empty($_POST['password'])) {

    $login = new WebadminLogin($db);

	if ($login->login($_POST['username'], $_POST['password'])) {
		header('location: ' . BASE_SITE . 'webadmin2/articulos.php');
	}

} 



// End file