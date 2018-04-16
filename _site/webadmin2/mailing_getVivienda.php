<?php
// Includes

set_include_path('../');

use Brunelencantado\Viviendas\ViviendasDetail;

include("../lib/admin.php"); 
error_reporting(E_ALL ^ E_DEPRECATED);

$base_site = str_replace('webadmin2/mods/mailing/', '', $base_site);
$mailing = true;
$lang = req('lang');
require_once("inc/helpers.php");
error_reporting(E_ALL ^ E_DEPRECATED);


// Property details
$oVivienda = new ViviendasDetail($db, $id);
$aVivienda = $oVivienda->getDetails();

$img 			= $aVivienda['images'][0]['s'];
$link 			= $aVivienda['link'];
$localizacion 	= $aVivienda['localidad'];
$precio 		= $aVivienda['precio'];

include('mods/mailing/mailing_thumbnail.php');

// End file