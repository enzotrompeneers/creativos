<?php // Controller to process form, insert into database and send email

	$asunto = ($asunto)?$asunto:webConfig('asunto');
	$email = array();
	$email['title'] = $asunto;
	$post = $_POST;
	$ignores = array('submit','code','role', 'ref', 'link');
	
	// Insert into database
	$insert = array();
	$insert['fecha'] = date('Y-m-d H:m:s');
	$insert['ip'] = $_SERVER['REMOTE_ADDR'];
	$insert['nombre'] = req('nombre');
	$insert['email'] = req('email');
	$insert['telefono'] = req('telefono');
	$insert['mensaje'] = req('mensaje');
	$insert['clave'] = req('formulario');
	
	// Get all info
	$fullEmail = '<table>';
	foreach ($_POST as $k=>$v){
		if(in_array($k,$ignores)) continue;
		if ($k=='formulario') $v = humanize($v);
		$fullEmail .= '<tr><td>'.trad($k).':&nbsp;</td><td>'.$v.'</td></tr>';
	}
	$fullEmail .= '</table>';
	$insert['email_completo'] = $fullEmail;
	
	$insertDb = insertDb($insert,$xname.'_contactos');
	mysql_query($insertDb);

	// Get content form database
	$contacto	= mail_content('contacto');

	$asunto		= ($contacto['asunto'])?$contacto['asunto']:$asunto;
	$mensaje	= $contacto['mensaje'];
	
	// phpmailer -----------------------------------------------------------
	$mailoptions = array();
	$mailOptions['to']			= webConfig('email');
	// $mailOptions['bcc']		= $mailto;
	$mailOptions['from']		= (valid_email($insert['email']))?$insert['email']:webConfig('email');
	$mailOptions['fromName']	= ($insert['nombre']!='')?$insert['nombre']:webConfig('nombre');
	$mailOptions['asunto']		= $asunto;
	$mailOptions['template']	= 'lib/mods/contacto/templates/template_contacto.php';
	$mailOptions['mensaje']		= $mensaje;
	$mailOptions['data']		= $fullEmail;
	$mailOptions['ignores'] 	= $ignores;
	$mailOptions['embeddeds'] 	= array('mailheader' => 'images/logo-mail.png');  
	
	// printout($mailOptions);
	// error_reporting(E_ALL);
	
	// Mail to client
	send_mail($mailOptions);
	
	if (valid_email($insert['email'])) {
		// Mail to user
		$mailOptions['to']			= $insert['email'];
		$mailOptions['from'] 		= webConfig('email');
		$mailOptions['fromName'] 	= webConfig('nombre');
		send_mail($mailOptions);
	}
	
// End file