<?php
// Last updated 07/08/2016


set_include_path('../');
include("../lib/admin.php"); 
error_reporting(4); 
require_once("inc/helpers.php");
require_once("webadmin2/config/config.php");

$parentId 	= req('parent_id');
$folder 	= req('folder');
$file 		= req('file');

$rotation 	= req('rotate');
$degrees	= ($rotation=='right')?270:90;

$imgTypes 	= array('s', 'm', 'l', 'g');

foreach ($imgTypes as $t) {
	$imgSrc 	= '../images/'.$folder.'/'.$parentId.'/'.$t.'_'.$file;
	$source 	= imagecreatefromjpeg($imgSrc);
	$rotated 	= imagerotate($source, $degrees, 0);
	imagejpeg($rotated, $imgSrc);
	echo $imgSrc.'<br/>';
}

// End file