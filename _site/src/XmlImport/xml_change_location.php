<?php
// Includes
set_include_path('../../../');
include("lib/admin.php"); 
require_once("webadmin2/inc/helpers.php");

$entryId	= req('entry_id');
$currentId	= req('current_id');
$newId		= req('new_id');

// First we get the assigned id, and change all properties to new assignation
$updateViviendasQuery	= "UPDATE {$xname}_viviendas SET localidad_id = {$currentId} WHERE localidad_id = {$newId}";
mysql_query($updateViviendasQuery) or die(mysql_error());

// Then we mark the assignation in the entries table
$updateEntriesQuery		= "UPDATE {$xname}_new_entries SET converted_id	= {$currentId} WHERE id = {$entryId}";
mysql_query($updateEntriesQuery) or die(mysql_error());

// Finally we delete the localidades entry if not assigned to self
if ($currentId != $newId) {
	$deletelocalidadQuery	= "DELETE from {$xname}_localidades WHERE id = {$newId}";
	mysql_query($deletelocalidadQuery) or die(mysql_error());	
}


echo $entryId;

// End of file