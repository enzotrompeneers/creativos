<?php
// Guestbook controller © Daniel beard 2014

function show_guestbook_form () {
	global $xname,$language,$error,$value,$base_site;
	$language 	= 'en';
	$viewForm 	= FALSE;
	$thankyou	= FALSE;
	// error_reporting(E_ALL); 
	if ($_POST) {
		$form 				= array();
		$error				= array();
		$form['fecha'] 		= date('Y-m-d H:i:s');
		$form['ip'] 		= $_SERVER['REMOTE_ADDR'];
		$form['nombre'] 	= req('nombre');
		$form['email_guest']= req('email_guest');
		$form['mensaje'] 	= req('mensaje');
		$form['hash'] 		= hash('sha256',$form['email_guest'].$form['ip'].$form['fecha']);
		$form['visible'] 	= 0;
		
		$pageContent 	= file_get_contents('http://freegeoip.net/json/'.$form['ip'].'');
		$geoInfo 		= json_decode($pageContent);
		foreach ($geoInfo as $k=>$v){
			$form['geo_info'] .= $k.': '.$v."\n";
		}
		
		
		// Required general array conditions
		$required = array('nombre','mensaje');
		foreach ($required as $r){
			if ($form[$r]==''){
				$error[$r] = trad('por_favor_introduzca').' '.trad($r);
			} else {
				$value[$r] = $form[$r];
			}
		}		
		// Special Conditions
		if (!valid_email($form['email_guest'])) { $error['email_guest'] = trad('email_valido'); } else { $value['email_guest'] = $form['email_guest']; }
		if (!chk_crypt(req('code'))) { $error['code'] = '<p class="error info">'.trad('captcha_invalido').'</p>';  $code = $error['code'].'</p>';} else { $value['code'] = $form['code'];}

		// Everything ok!
		if (empty($error)) {
			// Insert into database
			$insert 	= $form;
			$insertDb	= insertDb($insert,$xname.'_guestbook');
			mysql_query($insertDb) or die(mysql_error());
			// echo $insertDb;
			
			// Send mail to client
			$mailContent 	= 	mail_content('guestbook');
			$mensaje 		= 	$mailContent['mensaje'];
			$mensaje		.=	trad('nombre').': '.$insert['nombre'].'<br />';
			$mensaje		.=	trad('email').': '.$insert['email_guest'].'<br />';
			$mensaje		.=	trad('mensaje').': '.$insert['mensaje'].'<br />';
			$mensaje		.= 	'<a href="'.$base_site.'en/app/guestbook-publish/publish/'.$insert['hash'].'" style="color:#ae0000;">'.trad('publish').'</a><br />';
			$mensaje		.= 	'<a href="'.$base_site.'en/app/guestbook-publish/delete/'.$insert['hash'].'">'.trad('delete').'</a><br />';
			
			
			$mailOptions = array();
			$mailOptions['to'] 			= webConfig('email');
			$mailOptions['from'] 		= webConfig('email');
			$mailOptions['fromName'] 	= webConfig('nombre');
			$mailOptions['nombre'] 		= webConfig('nombre');
			$mailOptions['asunto'] 		= $mailContent['asunto'];
			$mailOptions['mensaje'] 	= $mensaje;
			$mailOptions['template']	= 'template_contacto.php';
			$mail 						= send_mail($mailOptions);
			
			// View thank you
			$thankyou = TRUE;
		} else {

			$viewForm = TRUE;
		}
		
		
		
	} else {
		$viewForm = TRUE;
	}
	
	include('view_guestbook_form.php');

}

function show_guestbook_messages() {
	global $xname,$language;
	
	// Pagination setup
	$start = (isset($_GET['clave'])) ? $_GET['clave'] : 1;
	$prev = $start - 1; $next = $start + 1;
	$limit = $start * $pagesize - $pagesize;	
	
	$guestQuery 	= "SELECT * FROM {$xname}_guestbook WHERE visible = 1 ORDER BY fecha";
	$guestSql		= dataset($guestQuery);
	
	$aGuest			= array();
	$n				= 0;
	foreach ($guestSql as $k=>$v) {
		$date = new DateTime($v['fecha']);
		$aGuest[$n]['fecha']	= $date->format('d/m/Y');
		$aGuest[$n]['nombre']	= htmlspecialchars($v['nombre']);
		$aGuest[$n]['mensaje']	= htmlspecialchars($v['mensaje']);
		$n++;
	}
	include('view_guestbook_messages.php');
}

// End file