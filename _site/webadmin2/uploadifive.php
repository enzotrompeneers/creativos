<?php
/*
Uploadify
Copyright (c) 2012 Reactive Apps, Ronnie Garcia
Released under the MIT License <http://www.opensource.org/licenses/mit-license.php> 
*/


error_reporting(E_ALL); 

/***** CONFIG *****/

// Set images size
$giant_image_h = '1920';
$giant_image_v = '1280';
$large_image_h = '890';
$large_image_v = '640';
$medium_image_h='570';
$medium_image_v='420';
$small_image_h='158';
$small_image_v='107';

// File array: what file extensions are permitted
$fileExtensions = array('pdf','txt','doc','docx','zip','jpg','jpeg','gif','png'); // FILES
$imageExtensions = array('jpg','jpeg','gif','png'); // IMAGES

// Folders that include original
$keepOriginals = ['panoramicas', 'proyectos'];

/***** /CONFIG *****/

set_include_path('../');
include("../lib/admin.php");
require_once("webadmin2/inc/helpers.php");

$session_name 	= session_name();
$session_id		= session_id();

// echo $session_id;

// if ($session_id!=$_POST[$session_name]	) {
    // // exit;
// } 
// Get extra data data 
if (empty($_POST['id']) || !is_numeric($_POST['id'])){
	$id = 0;
} else {
	$id = $_POST['id'];
}

$table = req('table');
// Name of folder
$table_array = explode("_",$table);
$folder = (!empty($table_array[1]))?$table_array[1]:false;
$folder_id = 'parent_id';

// print_r($_FILES);
if (!empty($_FILES)) {

	$tempFile 			= $_FILES['Filedata']['tmp_name'];
	
	if (!empty($_POST['type']) && $_POST['type']=='file') {
		// FILES
		$savefile 		= $_FILES['Filedata']['name'];
		$targetPath 	= '../images/'.$folder.'/'.$id.'/files/'; // Relative to the root
		if ( !file_exists($targetPath) ) { @mkdir($targetPath,0777,true);}  // Create folder if not exists		
		$targetFile 	= $targetPath . $savefile;
		$fileParts 		= pathinfo($_FILES['Filedata']['name']);	
		
		
		if (in_array(strtolower($fileParts['extension']),$fileExtensions)) {
			move_uploaded_file($tempFile,$targetFile); // Simple file move
			
			// Insert into database
			// Get order first!
			$maxOrden 		= record("SELECT MAX(orden) AS maxOrden FROM {$xname}_files_{$folder} WHERE {$folder_id} = $id");
			$fileOrden 		= ($maxOrden['maxOrden'] + 1);
			$update 		= "INSERT INTO {$xname}_files_{$folder} (file_name, {$folder_id}, orden) VALUES ('{$savefile}','{$id}','{$fileOrden}')";
			// echo $update;
			mysql_query($update);
			echo '1';
		} else {
			echo "Invalid file type";
		}

	} else {
		// IMAGES
		$targetPath = '../images/'.$folder.'/'.$id.'/'; // Relative to the root
		
		$filename = $_FILES['Filedata']['name'];
		$extension = pathinfo($filename, PATHINFO_EXTENSION);
		$savefile = slug(random_string(20)).'.'.$extension;
		$targetFile = rtrim($targetPath,'/') . '/' . $savefile;
		
		if (!file_exists($targetPath)) mkdir($targetPath, 0777);

		// Validate the file type
		$fileParts = pathinfo($_FILES['Filedata']['name']);	

		if (in_array(strtolower($fileParts['extension']),$imageExtensions)) {
			// Process files and add to database
			
				// Get order
				$maxOrden = record("SELECT MAX(orden) AS maxOrden FROM {$xname}_images_{$folder} WHERE {$folder_id} = $id");
				$imgOrden = ($maxOrden['maxOrden'] + 1);
				
				// Keep original panorámicas etc...
				if (in_array($folder, $keepOriginals)) {
					pictureresize($tempFile,"$targetPath/m_$savefile",$medium_image_h,$medium_image_v); // Mobile version
					move_uploaded_file($tempFile,$targetFile); // Old simple file move
					
				} else {
					// Resize and copy files to destination folder
					pictureresize($tempFile,"$targetPath/s_$savefile",$small_image_h,$small_image_v);
					pictureresize($tempFile,"$targetPath/m_$savefile",$medium_image_h,$medium_image_v);
					pictureresize($tempFile,"$targetPath/l_$savefile",$large_image_h,$large_image_v);
					pictureresize($tempFile,"$targetPath/g_$savefile",$giant_image_h,$giant_image_v);
				}

				
				// Insert into database
				$update = "INSERT INTO {$xname}_images_{$folder} (file_name, {$folder_id}, orden) VALUES ('{$savefile}','{$id}','{$imgOrden}')";
				// echo $update;
				mysql_query($update);
			
			
			// move_uploaded_file($tempFile,$targetFile); // Old simple file move
			echo '1';
		} else {
			echo 'Invalid file type.';
		}
	}
}


// End of file