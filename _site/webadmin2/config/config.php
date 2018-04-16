<?php 
/***** CONFIG *****/

$table 		= isset($_GET['table']) ? $_GET['table'] : $xname.'_viviendas';

// Are the database fields tranlated or humanized?
$translations = false;

//******* Define Tabs ******//
if ($table==$xname.'_panoramicas'){
	$starts = array(
			'start' 			=> show_label('principal'),
			'upload_images' 	=> show_label('imagenes')
	);
}


if ($table==$xname.'_proyectos'){
	$starts = array(
			'start' 			=> show_label('principal'),
			'descr_es' 			=> show_label('descripciones'),
			'fecha_creado' 		=> show_label('meta'),	
			'upload_images' 	=> show_label('imagenes')
	);
}

if ($table==$xname.'_proyectos_slider'){
	$starts = array(
			'start' 			=> show_label('principal'),
			'fecha_creado' 		=> show_label('meta'),	
			'upload_images' 	=> show_label('imagenes')
	);
}


if ($table==$xname.'_noticias'){
	$starts = array(
			'start' 				=> show_label('principal'),
			'upload_images' 	=> show_label('imagenes')
	);
}
if ($table==$xname.'_categorias'){
	$starts = array(
			'start' 				=> show_label('principal'),
			'slug_es' 				=> show_label('url'),
			'descr_es' 				=> show_label('descripciones')
	);
}

// Options array: defines what fields are to be shown in <option>. Default is nombre_es
$optionsArray = array(
	'statuses' 	=> 'nombre',
	'localidades' 	=> 'nombre',
	'default'		=> 'nombre_en'
);
// Tick/X array: defines what columns in the list are represented by symbols
$symbolArray = array('reservado','destacar','visible','vendido','destacado');

// Exceptions to correct automatic fuck-ups
$exceptionsArray = array(
	'localidads' => 'localidades'
);

$imageComments = false; // True for image comments

/***** /CONFIG *****/

// End file