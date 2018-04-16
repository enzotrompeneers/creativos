<?php
// Includes
set_include_path('../../../');
include("../../../lib/admin.php"); 
error_reporting(E_ALL ^ E_DEPRECATED);
$base_site = str_replace('webadmin/mods/mailing/', '', $base_site);
$mailing = true;
$lang = req('lang');
require_once("webadmin2/inc/helpers.php");


$query = "
			SELECT *, v.id AS vid, l.nombre_en AS localidad,v.REFERENCIA
			FROM {$xname}_viviendas v
			JOIN {$xname}_localidades l ON v.localidad_id = l.id			
			WHERE v.id = {$id}";
$sql 	= 	record($query);

$id 			= $sql['vid'];
$img 			= first_image('viviendas',  $id, 'm');
$link 			= get_vivienda_link($id);
$localizacion 	= $sql['localidad'];
$precio 		= precio($sql['precio_de_venta']);

include('mailing_thumbnail.php');

// End file