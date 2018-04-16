<?php
// Make sure session is TRUE
if ($_SESSION['Admin'] != TRUE) { header('Location: ../login.php');break;}

/*** DEFINES ALL ACTIONS TAKEN BY TIPOS ***/

// Various variables
$action 	= isset($_GET['action']) 	? $_GET['action'] : 'add';
$tableName 	= isset($_GET['titulo']) 	? $_GET['titulo']:$_GET['table'];
$table 		= isset($_GET['table']) 	? $_GET['table'] : $xname.'_viviendas';
$id			= isset($_GET['id']) 		? $_GET['id'] : 0;
$names 		= array('table','action','submit','id','imagen','address');

// Name of folder
$table_array 	= explode("_",$table);
$folder			= $table_array[1];
$folder_id 		= rtrim($folder,'s').'_id';

/**** Clone an entry with photo's and all ****/
if ($_GET['act']=='clonar') {
	// Do not clone id!
	// Get data
	$query = "SELECT * FROM {$table} WHERE id = {$id}";
	$sql = record($query);
	// Get fields and values and construct strings for INSERT query
	foreach ($sql as $k=>$v){
			if(in_array($k,$names)) continue;
			$v = mysql_real_escape_string($v);
			$fields .= $k.',';
			$values .= '\''.$v.'\''.',';		
	}
	// Chop the last commas off
	$fields = rtrim($fields,',');
	$values = rtrim($values,',');
	// INSERT query
	$insertQuery = "INSERT INTO {$table} ({$fields}) values ({$values})";
	// Run the query
	mysql_query ($insertQuery);
	// Get the id of clone for images table
	$newId = select_max("id",$table);
	
	// Auto reference
	if ($autoReference){
		$reference = $referencePrefix.str_pad($newId, 4 , '0',STR_PAD_LEFT);
		$refQuery = "UPDATE {$table} SET referencia = '{$reference}'";
		$refSql = mysql_query($refQuery);
	}
		
	
	// MOVE IMAGES
	if(table_has_images($folder)) {
		// Construct table names and fields
		$imagesTable = $xname.'_images_'.$folder;
		$folder_sing = rtrim($folder,'s');
		$folder_id = 'parent_id';
		// Query to get images
		$imagesQuery = "SELECT * FROM {$imagesTable} WHERE {$folder_id } = $id";
		$imagesSql = dataset($imagesQuery);
		// Insert new image references to new entry
		foreach ($imagesSql as $x=>$y) {
			$imageInsertQuery = "
			INSERT INTO {$imagesTable}
				(file_name,$folder_id,orden)
			VALUES
				('{$y['file_name']}','{$newId}','{$y['orden']}')";
			mysql_query($imageInsertQuery);
		}
		mysql_query($imageInsertQuery);
		// Copy all images
		$oldDir = '../images/'.$folder.'/'.$id.'/';
		$newDir = '../images/'.$folder.'/'.$newId.'/';
		copy_all($oldDir,$newDir);
	}
	// MOVE FILES
	if(table_has_images($folder,'files')) {
		// Construct table names and fields
		$filesTable 		= $xname.'_files_'.$folder;
		$folder_sing 		= rtrim($folder,'s');
		$folder_id 			= 'parent_id';
		// Query to get images
		$filesQuery 		= "SELECT * FROM {$imagesTable} WHERE {$folder_id } = $id";
		$filesSql 			= dataset($filesQuery);
		// Insert new image references to new entry
		foreach ($imafilesSqlgesSql as $x=>$y) {
			$fileInsertQuery = "
			INSERT INTO {$filesTable}
				(file_name,$folder_id,orden)
			VALUES
				('{$y['file_name']}','{$newId}','{$y['orden']}')";
			mysql_query($fileInsertQuery);
		}
		mysql_query($imageInsertQuery);
		// Copy all files
		$oldDir				= '../images/'.$folder.'/files/'.$id.'/';
		$newDir 			= '../images/'.$folder.'/files/'.$newId.'/';
		copy_all($oldDir,$newDir);
	}
	// Redirect to new entry
	header ('location: tipos.php?action=editar&table='.$table.'&id='.$newId.'&titulo='.$tableName);
}
/**** //Clone an entry with photo's and all ****/


// form handling	
if(isset($_POST['submit'])){
	if($_POST['action'] == "add"){
		// Adding an form entry
		
		$new_query = "insert into $table (id) values ('$id')";
		// echo '<p>'.$new_query.'</p>';
		mysql_query($new_query);
		$newId = select_max("id",$table);
		// $last_id = $new_id;
		foreach($_POST as $k => $v){
			if(in_array($k,$names)) continue;
			$v = mysql_real_escape_string($v);
			if (substr($k,0,4)=='slug' && $v=='') {
				$lang = substr($k,-2,4);
				$v = slug($_POST['nombre_'.$lang]); 
			}			
			if ($k=='fecha_creado' || $k=='fecha_modificado') {
				$v = date('Y-m-d H:i:s');
			}
			// Create hash from password
			if ($k=='hash') continue;
			if ($k=='clave' && $folder == 'usuarios' && $v != '') {
				$k = 'hash';
				$v = create_hash($v);
			}			
			$last_id = compare_save($newId, $table, $k, $v);
		}
		
		// Auto reference
		if ($autoReference){
			$reference = $referencePrefix.str_pad($last_id,4 , '0',STR_PAD_LEFT);
			$refQuery = "UPDATE {$table} SET referencia = '{$reference}' WHERE id = {$last_id}";
			// printout($refQuery);
			$refSql = mysql_query($refQuery) or die(mysql_error());
		}
		
		// Move data and files of temp images to new table
		// Database
		if (table_has_images($folder,'images')) {
			$folder_sing = rtrim($folder,'s');
			$folder_id = $folder_sing.'_id';
			if ($folder == 'albumes') { $folder_id = 'album_id'; }
			$updateQuery = "UPDATE {$xname}_images_{$folder} SET parent_id = {$newId} WHERE parent_id = 0";
			// echo $updateQuery;
			mysql_query($updateQuery) or die(mysql_error());
			// Files
			$oldDir = '../images/'.$folder.'/0/';
			$newDir = '../images/'.$folder.'/'.$newId.'/';
			if(!file_exists($newDir)) { (mkdir($newDir,0777));};
			$dir_handle = opendir($oldDir);
			while($file = readdir($dir_handle)) {
			   if ($file != "." && $file != "..") {
					copy($oldDir.$file,$newDir.$file);
					unlink($oldDir.$file);

				}
			}
			closedir($dir_handle);		
		}
		// Move files
		if (table_has_images($folder,'files')) {
			$folder_sing 	= rtrim(rtrim($folder,'es'),'s');
			$folder_id 		= $folder_sing.'_id';
			if 	($folder == 'albumes') { $folder_id = 'album_id'; }
			$updateQuery 	= "UPDATE {$xname}_files_{$folder} SET parent_id = {$newId} WHERE parent_id = 0";
			// echo $updateQuery;
			mysql_query($updateQuery) or die(mysql_error());
			// Files
			$oldDir = '../images/'.$folder.'/0/files/';
			$newDir = '../images/'.$folder.'/'.$newId.'/files/';
			if(!file_exists($newDir)) { (mkdir($newDir,0777));};
			$dir_handle = opendir($oldDir);
			while($file = readdir($dir_handle)) {
			   if ($file != "." && $file != "..") {
					copy($oldDir.$file,$newDir.$file);
					unlink($oldDir.$file);
				}
			}
			closedir($dir_handle);		
		}
		
		// Upload files
		foreach($_FILES as $k=>$v){
			if (get_comment($folder,$k)=='file')
			file_upload ($k,$table,$folder,$newId);
		}

	}
	
	if($_POST['action'] == "edit"){
		// Editing an form entry
		foreach($_POST as $k => $v){
			if(in_array($k,$names)) continue;
			$post = $_POST['id']; 
			// Autoslug
			if (substr($k,0,4)=='slug' && $v=='') {
				$lang = substr($k,-2,4);
				$v = slug($_POST['nombre_'.$lang]); 
			}			
			$v = mysql_real_escape_string($v);
			if (substr($k,0,4)=='slug' && $v=='') {
				$lang = substr($k,-2,4);
				$v = slug($_POST['nombre_'.$lang]); 
			}
			
			// Auto reference
			if ($autoReference && $k=='referencia'){
				$reference = $referencePrefix.str_pad($post,4 , '0',STR_PAD_LEFT);
				$v = $reference;
				
			}
			
			// Auto modified date
			if ($k=='fecha_modificado') {
				$v = date('Y-m-d H:i:s');
			}
			
			// Create hash from password
			if ($k=='hash') continue;
			if ($k=='clave' && $folder == 'usuarios' && $v != '') {
				$k = 'hash';
				$v = create_hash($v);
			}					
			$query = "UPDATE $table SET $k = '$v' WHERE id = $post";
			mysql_query($query) or die(mysql_error());
		}


		
		// Upload files
		foreach($_FILES as $k=>$v){
			if (get_comment($folder,$k)=='file')
			file_upload ($k,$table,$folder,$id);
		}



	
	
	}		
	

}
if(isset($_GET['action'])){
	if($_GET['action'] == "editar"){
		// getting the records
		$setQuery = "SELECT * FROM $table WHERE id = ".$id;
		//echo $setQuery;
		$set = record($setQuery);
		$action = "edit";
	}
	if($_GET['action'] == "borrar"){
		// removing an entry
		mysql_query("DELETE FROM $table WHERE id = ".$id);
		mysql_query("DELETE FROM {$xname}_images_{$folder} WHERE parent_id = ".$id);
		mysql_query("DELETE FROM {$xname}_files_{$folder} WHERE parent_id = ".$id);
		$deleteFolder = '../images/'.$folder.'/'.$id.'/';
		delete_directory ($deleteFolder);
		header ('location: tipos.php?table='.$table.'&titulo='.$tableName.'&idioma='.$lang);
		
	}
	if($_GET['action'] == "ver"){
		// showing an entry
	}		
}	


// Initiating variables & arrays
$r = array();
if(isset($_GET['id']) || isset($_GET['new'])) {
	$displayform = '';
	if($id){
		$r = record( "SELECT * FROM $table WHERE id={$id}");
	}
}
$texts 		= array();
$bools 		= array();
$ignores 	= array('orden');
$hidden 	= array('id');

// Gets the arrays with column names for the listing
initListing($table);

// Create the form

$addform 	= form_better($table, $r, $texts, $bools, $ignores, $starts);

// Get reference
$reference= '';
if ($folder=='viviendas' && $id){
	$refQuery = "SELECT referencia FROM {$xname}_viviendas WHERE id = {$id}";
	$refSql = record($refQuery);
	if ($refSql) {
		$reference = ' - Ref: '.$refSql['referencia'];
	}
}

// Images
$hasImages = table_has_images($folder);

// Queries
$imageQuery = "";
if ($hasImages) {
	$imagesFolder = $xname . '_images_' . $folder;
	$imageQuery = "
				, (SELECT i.file_name 
				FROM {$imagesFolder} i
				WHERE i.parent_id = t.id
				ORDER BY i.orden ASC
				LIMIT 1) AS image";
}	
		
$recordsQuery .= "
				SELECT * {$imageQuery} 
				FROM {$table} t
				ORDER BY t.id DESC
				";
$records 	= dataset($recordsQuery);
$list_data 	= record("SELECT table_fields FROM {$xname}_list_data WHERE table_name='$table'");
$arrPieces 	= getPieces($list_data);
if (empty($arrPieces[0])){
	$arrPieces = array();
	foreach ($languages as $l) {
		$arrPieces[] = 'nombre_'.$l;
	}
}

// Other variables
$ordenExistsQuery	= mysql_query("SHOW COLUMNS FROM `{$table}` LIKE 'orden'");;
$ordenExists = (mysql_num_rows($ordenExistsQuery))?TRUE:FALSE;



	$imageQuery = "SELECT parent_id, file_name FROM {$xname}_images_{$folder}";
	$imageSql = dataset($imageQuery);
	$images = array();
	foreach ($imageSql as $k=>$v){
		$images[$k] = $v['file_name'] . ' - ' . $v['parent_id'];
	}

	



$editButton = show_label('editar');
$deleteButton = show_label('borrar');

// Map variable
if ($mapa==TRUE) {
	$mapScript = '<script type="text/javascript">
	$(document).ready(function() {
		// Create map div
		$("#tab_lat").prepend(\'<div class="form-group"><div class="col-md-4"><input type="text" id="address" name="address"  class="form-control input-xlarge" /></div><div class="col-md-8"><input type="button" id="mapButton" value="Search"  class="btn green" /></div></div><div id="map_canvas"></div>\');
	 });
</script>';
}

// End file