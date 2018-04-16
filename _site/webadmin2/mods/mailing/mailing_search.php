<?php // Search results for mailing tool
// Includes
set_include_path('../../../');
include("../../../lib/admin.php"); 
$mailing = true;
include("../../../webadmin2/inc/helpers.php"); 
include("../../../webadmin2/mods/mailing/mailing_helpers.php"); 
$base_site = str_replace('mods/mailing/','',$base_site);
if ($_GET) {
	
	$tipoventa			=	req('tipoventa');
	$tipovivienda		=	req('tipovivienda');
	$dormiorios			= (int) req('dormiorios');
	$banos				= (int) req('banos');
	$localizacion		= (int) req('localizacion');
	$precio_desde		= (int) req('precio_desde');
	$precio_hasta		= (int) req('precio_hasta');
	$piscina			= (int) req('piscina');
	$aparcamiento		= (string) req('aparcamiento');
	$vistas				= (string) req('vistas');
	$jardines			= (string) req('jardines');
	$orientaciones		= (string) req('orientaciones');
	$airco				= (string) req('airco');
	$terraza			= (string) req('terraza');
	$text				= (string) req('text');
	
	// Main statement
	$buscarSQL	= get_vivienda_sql();	
	
	// Conditionals
	if (! empty($precio_desde))	$sWhere .= '	  and v.precio_de_venta >= ' .	$precio_desde . PHP_EOL; 
	if (! empty($precio_hasta))	$sWhere .= '	  and v.precio_de_venta <= ' .	$precio_hasta . PHP_EOL; 
	if (! empty($dormitorios))	$sWhere .= '	  and v.dormitorios >= ' .		$dormitorios . PHP_EOL;
	if (! empty($banos))		$sWhere .= '	  and v.banos >= ' .			$banos . PHP_EOL;
	if (! empty($localizacion))	$sWhere .= '      and v.localidad_id = ' .	$localizacion . PHP_EOL;
	if (! empty($piscina))		$sWhere .= '      and v.piscina_id = ' .	$piscina . PHP_EOL;
	if (! empty($tipoventa))	$sWhere .= '      and v.clase_id = ' .	$tipoventa . PHP_EOL;
	if (! empty($tipovivienda))	$sWhere .= '      and v.tipo_id = ' .	$tipovivienda . PHP_EOL;
	if (! empty($aparcamiento))	$sWhere .= '      and v.parking_id = ' .	$aparcamiento . PHP_EOL;
	if (! empty($vistas))		$sWhere .= '      and v.vista_id = ' .	$vistas . PHP_EOL;
	if (! empty($jardines))		$sWhere .= '      and v.jardin_id = ' .	$jardines . PHP_EOL;
	if (! empty($orientaciones))$sWhere .= '      and v.orientacion_id = ' .	$orientaciones . PHP_EOL;
	if (! empty($airco))		$sWhere .= '      and v.aire_acondicionado = 1' . PHP_EOL;
	if (! empty($terraza))		$sWhere .= '      and v.terraza = 1' . PHP_EOL;
	if (! empty($text))			$sWhere .= '      and (v.cd_code LIKE \'%'.$text.'%\'  OR referencia LIKE \'%'.$text.'%\' ' . PHP_EOL;
	
	$buscarSQL .= $sWhere;
	
	
	
	
	$result = dataset($buscarSQL);
	
	// printout($buscarSQL);
	
	if (empty($result)) echo trad('sin_resultados');
	
	echo '<ul>';
	foreach ($result as $k=>$v) {
		
		$id 			= $v['vid'];
		$link 			= get_vivienda_link($id);
		$img 			= first_image('viviendas',  $id, 'm');
		$localizacion	= $v['ciudad'];
		$precio 		= precio($v['precio_de_venta']);		
		
		echo '<li id="s'.$id.'">
		<a href="#" class="anadir input-xsmall btn green right" id="b'.$id.'" >'.trad('anadir').'</a>
				<div class="left">
					<a href="'.$link.'" target="_new"><img src="'.$img.'" alt="" /></a>
				</div>
				<div class="left">
					<a href="'.$link.'" target="_new">'.$v['referencia'].' - '.$v['nombre'].'</a><br />
					<p>'.$localizacion.'</p>
					<p>'.trad('precio').': '.$precio.'&euro;</p>
				</div>
				
			</li>';
		
	}
	echo '<ul>';
}

// End file