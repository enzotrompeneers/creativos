<?php
	set_include_path('../');
	require_once("../lib/admin.php");
	require_once("inc/helpers.php");
	error_reporting(1);
	$id = ($id=='')?0:$id;
	
	$gallery_id 		= $id;
	$table 				= $_GET['table'];
	$table_array 		= explode("_",$table);
	$folder 			= $table_array[1];
	$folder_sing 		= rtrim($folder,'s');
	$folder_id 			= 'parent_id';
	
	if($_GET['action'] == "removeImg"){
		
		$query = "SELECT file_name FROM ".$_GET['tbl']." WHERE id = ".$_GET['fileId'];
		//echo $query;
		$sql = record($query);
		$file_name = $sql['file_name'];
		
		$del_query = "DELETE FROM ".$_GET['tbl']." WHERE id = ".$_GET['imageId'];
		mysql_query($del_query);
		
	}	
	if($_GET['action'] == "language"){
		$lang 		= $_GET['language'];
		$query 		= "UPDATE {$xname}_files_{$folder} SET language =  '{$lang}' WHERE id = {$_GET['imgId']}";
		// echo $query;
		mysql_query($query);
		

	}
	
	
		$tableImg 			= $xname.'_files_'.$folder;
		$tableParent 		= $xname.'_'.$folder; 
		$tableClave 		= trim($folder,'s');
		if ($folder == 'albumes') 		{ $tableClave = 'album'; }
		if ($folder == 'promociones') 	{ $tableClave = 'promocion'; }
		if ($folder == 'alquileres') 	{ $tableClave = 'alquiler'; }
		//echo $tableParent;
		if ($tableParent==$_GET['table']) { 
		$query 		= "SELECT * FROM {$tableImg} WHERE parent_id = ".$gallery_id." ORDER BY orden ASC";
		// echo $query;
		$ruta 		= '../images/'.$folder.'/'.$gallery_id.'/files';
			}
		//echo $query;

		$result 	= dataset($query);
	
	if(!empty($result)){
	foreach($result as $k => $v){
			$id 			= stripslashes($v['id']);
			$file_name 		= stripslashes($v['file_name']);
			$extension		= substr(strrchr($file_name, "."),1);
			$icon			= get_icon($extension);
			$lang			= (empty($v['language']))?show_label('idioma'):$v['language'];
	?>
		<li id="arrayorder_<?php echo $id ?>" class="list-group-item">
		<div class="row">
			<div class="col-md-8">
				<img src="../images/file_icons/<?=$icon?>.png" alt="<?=$icon?>" />&nbsp;&nbsp;<?= $file_name;?>&nbsp;
			</div>
			<div class="col-md-4">
			
				<button data-toggle="dropdown" type="button" class="btn btn-xs green dropdown-toggle "  aria-expanded="false" >
					<?php if(!empty($v['language'])) : ?><img src="../images/flags/<?=$lang?>.gif" alt="<?=$l?>" /><?php endif ?>
					&nbsp;<?=$lang?> <i class="fa fa-angle-down"></i>
				</button>	
				<ul role="menu" class="dropdown-menu">
					<?php foreach ($languages as $l) : ?>
					<li>
						<a href="file_language.php" class="languageChange" id="lang_<?=$l?>" role="<?=$id?>">
						<img src="../images/flags/<?=$l?>.gif" alt="<?=$l?>" />&nbsp;
						<?=$l?>
						</a>
					</li>
					<?php endforeach ?>
				</ul>
				&nbsp;		
				<a class="btn btn-xs blue ver_imagen  round " href="<?=$ruta?>/<?=$g?><?=$file_name?>" target="_new">
					<i class="fa fa-link"></i>
					<?=show_label('ver_documento');?>
				</a>
				&nbsp;
				<a class="btn btn-xs red borrar borrar_file  round" href="dragdrop_files.php?action=removeImg&table=<?=$table?>&tbl=<?=$tableImg?>&imageId=<?=$id?>&id=<?=$gallery_id?>" role="files">
					<i class="fa fa-times"></i>
					<?=show_label('borrar');?>
				</a>
			

												
			</div>
		</div>
	</li>
	
	
	
<?php }
}


// End file

