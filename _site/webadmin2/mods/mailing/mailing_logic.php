<?php
// Make sure session is TRUE
// if ($_SESSION['Admin'] != TRUE) { header('Location: ../login.php');exit;}
// $base_site = str_replace('webadmin/','',$base_site);
 error_reporting(E_ALL);
// Includes

use Brunelencantado\Viviendas\ViviendasDetail;

require_once('webadmin2/mods/mailing/mailing_config.php');
require_once('lib/helpers/send_mail.php');
$_SESSION['body'] = '';

// Get all properties by ref number
$vivQuery = "SELECT id AS id, referencia, titulo_{$languages[0]} AS titulo FROM {$xname}_viviendas WHERE visible = 1 ORDER BY referencia";
$aViviendas = dataset($vivQuery);
$viviendas = [];
foreach($aViviendas as $k =>$v){
	
	$viviendas[$k]['id'] = $v['id'];
	$viviendas[$k]['nombre'] = $v['referencia'] . ' - ' . $v['titulo'];
	
}



// Get mailing groups


// Logic if form is sent
if ($_POST){
	// printout($_POST);
	// Get POST details
	$lang = req('lang');
	$text = $_POST['mensaje'];
	$viviendasList = $_POST['vivienda'];
	// printout($viviendasList);
	$emails = (!empty($_POST['email']))?$_POST['email']:array();
	// printout($_POST['email']);
	$asunto = $_POST['asunto'];
	$emailGroup = (!empty($_POST['emailGroup']))?$_POST['emailGroup']:array();
	
	// Add group emails to email array
	if (!empty($emailGroup)) {
		foreach ($emailGroup AS $group) {
			$emailGroupQuery = "SELECT email FROM {$xname}_emails_clientes WHERE grupo_mailing_id = {$group}";
			$emailGroupSql = dataset($emailGroupQuery);
			foreach ($emailGroupSql as $email) {
				$emails[] = $email['email'];
			}
		}
		
	}

	
	// printout($emails); 

	// Create message
	$asunto = (req('asunto')!='')? req('asunto') : translate('asunto_mailing', $lang);
	$fontStyle 	= "font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;";
	$mensaje = $text;
	$mensaje .= '<table id="viviendas" width="720" '.$fontStyle.' >';
	$mensaje .= '<tr>
	<td style="width:120px;padding:4px;background:#fff;text-align:center;'.$fontStyle.'">&nbsp;</td>
	<td style="width:120px;padding:4px;background:#fff;text-align:center;'.$fontStyle.'"><strong>'.translate('ref', $lang).'</strong></td>
	<td style="width:120px;padding:4px;background:#fff;text-align:center;'.$fontStyle.'"><strong>'.translate('detalles', $lang).'</strong></td>
	<td style="width:120px;padding:4px;background:#fff;text-align:center;'.$fontStyle.'"><strong>'.translate('precio_de_venta', $lang).'</strong></td>	
	<td style="width:120px;padding:4px;background:#fff;text-align:center;'.$fontStyle.'"><strong>'.translate('enlace', $lang).'</strong></td></tr>';
	$noBorrar = TRUE;
	// echo 'lang: '.$lang;
	$n = 0;
	$viviendasRefs = '';
	$thumbnails= [];
	foreach ($viviendasList as $k=>$v) {
		
		// Property details
		$oVivienda = new ViviendasDetail($db, $v);
		$aVivienda = $oVivienda->getDetails();

		$id 			= $aVivienda['id'];
		$viviendasRefs  .= $aVivienda['referencia'] . ' ';
		$titulo			= $aVivienda['titulo_' . $lang];
		$img 			= (mb_substr($aVivienda['images'][0]['s'], 0, 4) == 'http') ? $aVivienda['images'][0]['s'] : '../' . $aVivienda['images'][0]['s'];
		
		$thumbnails['thumb' . $id] = $img;
		
		$link 			= $oVivienda->getLink($lang);
		
		$precio 		= $aVivienda['precio'];
		$background		= ($n%2 == 0) ? '#fff' : '#eaeaea';
		$mensaje 		.= '
			<tr>
				<td width="120" style="padding:4px;background:'.$background.';text-align:center;width:120px;'.$fontStyle.'">
					<a href="'.$link.'" target="_new">
						<img src="cid:thumb'.$id.'" alt="' . $titulo . '" width="120" />
					</a>
				</td>
				<td width="110" style="padding:4px;background:'.$background.';text-align:center;'.$fontStyle.'">
					<p style="width:110px;'.$fontStyle.'">'.$aVivienda['referencia'].'</p>
				</td>
				<td width="200" style="padding:4px;background:'.$background.';text-align:left;'.$fontStyle.'">
					<p style="'.$fontStyle.'">
						<strong>' . $titulo . '</strong> <br/>
						' . translate('sup_vivienda') . ': ' . $aVivienda['sup_vivienda'] . 'm&sup2; <br/>
						
					</p>
				</td>
				<td width="110" style="padding:4px;background:'.$background.';text-align:center;'.$fontStyle.'">
					<p style="width:110px;">'.$precio.' &euro;</p>
				</td>		
				<td width="100" style="padding:4px;background:'.$background.';text-align:center;'.$fontStyle.'">
					<p style="width:100px;"><a href="'.$link.'" target="_new">' . translate('click', $lang).'</a></p>
				</td>
			</tr>
	'; 
	$n++;
	}
	$mensaje 			.= '</table>';
	
	
	// echo $mensaje;

	
	// DB insert -----------------------------------------------------------
	$insert = array();
	$insert['fecha'] 		= date('Y-m-d H:i:s');
	$insert['recipientes'] 	= implode(' ', $emails);
	$insert['viviendas'] 	= $viviendasRefs;
	$insert['asunto'] 		= $asunto;
	$insert['cuerpo'] 		= $mensaje;
	$insertDb = insertDb($insert, $xname.'_emails_enviados');
	mysql_query($insertDb) or die(mysql_error());
	
	// phpmailer -----------------------------------------------------------
	$mailoptions = array();
	$mailOptions['ignores']		= array();
	$mailOptions['data']		= array();
	// $mailOptions['bcc']		= $mailto;
	$mailOptions['asunto']		= $asunto;
	$mailOptions['from']		= webconfig('email');
	$mailOptions['fromName']	= webConfig('nombre');
	$mailOptions['subject']		= $asunto;
	$mailOptions['template']	= 'webadmin2/mods/mailing/mailing_template.php';
	$mailOptions['mensaje']		= $mensaje;
	
	$mailOptions['embeddeds'] 	= array('mailheader' => '../images/logo.png');
	
	$mailOptions['embeddeds'] = array_merge($mailOptions['embeddeds'], $thumbnails);

	
	// printout($mailOptions);
	// error_reporting(E_ALL);

	// Mail to recipients
	foreach ($emails as $e) {
		$emailArray = explode(',',$e);
		$mailOptions['to']			= $e;
		$mailOptions['from'] 		= webConfig('email');
		$mailOptions['fromName'] 	= webConfig('nombre');
		// printout($mailOptions);
		$mail = send_mail($mailOptions);
		
	}
	
	$message = '<p class="success">'.trad('mensaje_enviado').'</p>';
	$view = 'send';
	
} else {
	// Form arrays
	$aIdiomas = array();
	$n = 1;
	foreach ($languages as $l) {
		$aIdiomas[$n]['id']		= $l;
		$aIdiomas[$n]['nombre']		= $l;
		$n++;
	}
	
	$view = 'form';
}


// End file