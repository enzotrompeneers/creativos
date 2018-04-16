<?php 
	include("../admin.php");
	
	$array	= $_POST['arrayorder'];
	$tblImages = $xname.'_images';
		
	if ($_POST['update'] == "update"){
		$count = 1;
		foreach ($array as $idval) {
			$query = "UPDATE $tblImages SET orden = " . $count . " WHERE id = " . $idval;
			mysql_query($query) or die('Error, insert query failed');
			$count ++;	
		}
		echo 'Orden guardado';
	}
?>