<?	
// Latest update 02/04/2014
// For Yes Inmo - total revamp

// $mailto = Email to be sent to
// $asunto = Email Subject
// error_reporting(E_ALL);
require_once('lib/helpers/send_mail.php');
$cryptinstall = 'lib/mods/contacto/crypt/cryptographp.fct.php';
require_once($cryptinstall);

function show_contacto($mailto,$asunto,$captcha=FALSE){
	global $language,$xname,$pagina;
	
	if (!$mailto) { $mailto = webConfig('email'); }
	if (!$asunto) { $asunto = 'Contact form '.webConfig('nombre'); }
	$gracias = (art_sin('gracias')!='')?art_sin('gracias'):'Gracias por su interés, le responderemos en breve';

	if($_POST && chk_crypt(req('code'))){
		include('controller_contacto.php'); // HTML form
		include('view_gracias.php'); // HTML form
	} else {
		$value = $_POST;
		include('view_contacto_form.php'); // HTML form
	}  
}



// End file