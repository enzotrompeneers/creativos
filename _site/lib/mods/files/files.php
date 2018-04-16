<?php
// Latest update 21/01/2015

require_once('lib/mods/files/file_functions.php');

function show_files($folder,$id,$lang){
	global 		  $language,$xname;
	$lang		= (empty($lang))?$language:$lang;
	$query		= "SELECT file_name FROM {$xname}_files_{$folder} WHERE parent_id = {$id} AND language = '{$lang}' ORDER BY orden";
	$sql		= dataset($query);
	
	$oFiles		= array();
	$n			= 0;
	foreach ($sql as $k=>$v) {
		$oFiles[$n]['file_name']	= $v['file_name'];
		$extension					= substr(strrchr($oFiles[$n]['file_name'], "."),1);
		$oFiles[$n]['icon']			= get_icon($extension);
		$oFiles[$n]['link']			= 'images/'.$folder.'/'.$id.'/files/'.$oFiles[$n]['file_name'];
		$oFiles[$n]['file_size']	= formatBytes(filesize($oFiles[$n]['link']));
		$n++;
	}

	include('view_files.php'); 

}
// End file