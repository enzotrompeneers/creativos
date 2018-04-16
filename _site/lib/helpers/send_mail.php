<?php
// Send mail function
require('mail/phpMailer/class.phpmailer.php');
require('mail/phpMailer/class.smtp.php');

// Get header and footer HTML
$mailHeader = file_get_contents('mail/header.html',TRUE);
$mailFooter = file_get_contents('mail/footer.html',TRUE);


		
// Retrieves subject & message from database depending on clave, to be sent to the send_mail option
function mail_content($clave){
	global $xname,$language;
	$query = "SELECT * FROM {$xname}_emails WHERE clave = '{$clave}'";
	$sql = record($query);
	$asunto = $sql['asunto_'.$language];
	$mensaje = $sql['texto_'.$language];
	// Returns array with asunto and mensaje
	$mail = array();
	$mail['asunto'] = $asunto;
	$mail['mensaje'] = $mensaje;
	return $mail;
}	
// Retrieves subject & message from database depending on clave, to be sent to the send_mail option
function mail_id($id){
	global $xname,$language;
	$query = "SELECT * FROM {$xname}_emails WHERE id = '{$id}'";
	$sql = record($query);
	$asunto = $sql['asunto_'.$language];
	$mensaje = $sql['texto_'.$language];
	// Returns array with asunto and mensaje
	$mail = array();
	$mail['asunto'] = $asunto;
	$mail['mensaje'] = $mensaje;
	return $mail;
}	


function send_mail($mailOptions){

	global $language;
	$oEmail = new PHPMailer(true);
	$ignores = $mailOptions['ignores'];
	
	$mailOptions['template'] 	= (empty($mailOptions['template']))?'template_contacto.php':$mailOptions['template'];
	
	// Cargamos la plantilla del email y la ejecutamos --> limpiar todo el buffer de salida
	// Como los buffers son apilables, con ob_start() y ob_end_clean(); hago que el codigo intermedio
	// sea válido solo para ese buffer
	ob_start(); 
		//$email		= $mailOptions['data'];
		$header 	= (file_exists($mailOptions['embeddeds']['mailheader']))?'<img src="cid:mailheader" />':'<h1>'.webConfig('nombre').'</h1>'; 
		
		$message	= $mailOptions['mensaje'];
		require $mailOptions['template'];
		$emailBody = ob_get_contents();
	ob_end_clean();
	
    // $oEmail->SMTPDebug 		= 2;  // direccion del servidor
    // $oEmail->SMTPSecure 	= 'tls';  // direccion del servidor
    $oEmail->Host 			= HOST; // direccion del servidor
    $oEmail->SMTPAuth 		= true; // usaremos autenticacion
    $oEmail->Port	 		= 25; // usaremos autenticacion
    $oEmail->Username 		= USER; // usuario
    $oEmail->Password 		= PASSWORD; // contraseña
	$oEmail->CharSet		= "UTF-8";
    $oEmail->From 			= $mailOptions['from']; // Mail de origen
    $oEmail->FromName 		= $mailOptions['fromName']; // Nombre del que envia
    $oEmail->WordWrap 		= 50; // Largo de las lineas
    $oEmail->Subject 		= $mailOptions['asunto'];
    $oEmail->Body			= $emailBody;
	$oEmail->IsSMTP(); // vamos a conectarnos a un ser100vidor SMTP
    $oEmail->IsHTML(TRUE); // Podemos incluir tags html
    $oEmail->AddAddress($mailOptions['to']); // Mail destino, podemos agregar muchas direcciones
	if (!empty($mailOptions['bcc']) && valid_email($mailOptions['bcc'])) { $oEmail->AddBCC($mailOptions['bcc']	);	}
	if (!empty($mailOptions['embeddeds'])) {
		foreach ($mailOptions['embeddeds'] as $cid => $embeddeds) {
		// $data = filedata($embeddeds);	
		$oEmail->AddStringEmbeddedImage(file_get_contents($embeddeds), $cid);
		}	
	}
	
	 // printout($oEmail);
	// echo $emailBody;
	
	//** Send it!
    // $bResult = $oEmail->send();
	
	if (! $bResult = $oEmail->send()) {
		var_dump( $oEmail->ErrorInfo );
	}
	
	return $bResult;
	
	
}




// End file