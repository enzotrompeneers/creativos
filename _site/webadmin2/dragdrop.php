<?php
	set_include_path('../');
	require_once("../lib/admin.php");
	require_once("inc/helpers.php");
	require_once("config/config.php");
	
	error_reporting(1);
	$id = ($id=='')?0:$id;
	
	$gallery_id 		= $id;
	$table 				= $_GET['table'];
	$table_array 		= explode("_",$table);
	$folder 			= $table_array[1];
	$folder_sing		= rtrim($folder,'s');
	$folder_id 			= 'parent_id';
	
	if($_GET['action'] == "removeImg"){
		
		$query = "SELECT file_name FROM ".$_GET['tbl']." WHERE id = ".$_GET['imageId'];
		
		$sql = record($query);
		$file_name = $sql['file_name'];
		
		$del_query = "DELETE FROM ".$_GET['tbl']." WHERE id = ".$_GET['imageId'];
		echo $del_query;
		mysql_query($del_query);
		
		//echo '../images/'.$folder_sing.'/'.$_GET['id'].'/s_'.$file_name;
		
		if (file_exists('../images/'.$folder.'/'.$_GET['id'].'/s_'.$file_name)) { unlink ('../images/'.$folder.'/'.$_GET['id'].'/s_'.$file_name); }
		if (file_exists('../images/'.$folder.'/'.$_GET['id'].'/m_'.$file_name)) { unlink ('../images/'.$folder.'/'.$_GET['id'].'/m_'.$file_name); }
		if (file_exists('../images/'.$folder.'/'.$_GET['id'].'/l_'.$file_name)) { unlink ('../images/'.$folder.'/'.$_GET['id'].'/l_'.$file_name); }
		if (file_exists('../images/'.$folder.'/'.$_GET['id'].'/g_'.$file_name)) { unlink ('../images/'.$folder.'/'.$_GET['id'].'/g_'.$file_name); }

	
		//header ("location: tipos.php?id={$_GET['id']}");
	}
	// Show file list
		$tableImg 		= $xname.'_images_'.$folder;
		$tableParent 	= $xname.'_'.$folder; 
		$tableClave 	= trim(trim($folder,'es'),'s');
		if ($folder == 'albumes') 		{ $tableClave = 'album'; }
		if ($folder == 'promociones') 	{ $tableClave = 'promocion'; }
		if ($folder == 'alquileres') 	{ $tableClave = 'alquiler'; }
		//echo $tableParent;
		if ($tableParent==$_GET['table']) { 
		$query = "SELECT id, file_name FROM {$tableImg} WHERE parent_id = ".$gallery_id." ORDER BY orden ASC";
		// echo $query;
		$ruta = '../images/'.$folder.'/'.$gallery_id;
			}
		//echo $query;

		$result = dataset($query);
	
	if(!empty($result)){
	foreach($result as $k => $v){
			$id = stripslashes($v['id']);
			$file_name = stripslashes($v['file_name']);
	?>
	<li id="arrayorder_<?php echo $id ?>">
	<?php 
		$g = ($folder=='panoramicas')?'':'g_';
		$s = ($folder=='panoramicas')?'':'m_';
		
		$fullPath = (mb_substr($file_name, 0, 4)=='http') ? $file_name : $ruta . '/' . $s . $file_name;
	?>
		<img src="<?php echo $fullPath; ?>" id="img<?= $id ?>" /> <br />
		<div class="clear">
			<a class="btn btn-xs blue ver_imagen colorbox" href="<?=$ruta?>/<?=$g?><?=$file_name?>?d=<?= date('Hms'); ?>" rel="colorbox">
				<?=show_label('ver_imagen');?>
			</a>
			<?php if ($imageComments==true) : ?>
			<a class="btn btn-xs green comentario colorbox" href="comentarios.php?table=<?=$table?>&tbl=<?=$tableImg?>&imageId=<?=$id?>&id=<?=$gallery_id?>" >
				<?=show_label('comentario');?>
			</a>
			<?php endif ?>
			<a class="btn btn-xs red borrar borrar_imagen" href="dragdrop.php?action=removeImg&table=<?=$table?>&tbl=<?=$tableImg?>&imageId=<?=$id?>&id=<?=$gallery_id?>" >
				<?=show_label('borrar');?>
			</a>
			
			<a href="#" class="rotate" data-rotate="left" data-parent-id="<?= $gallery_id ?>" data-table="<?= $folder ?>" data-file="<?= $file_name ?>" data-id="<?= $id ?>"><i class="fa fa-rotate-left"></a></i>
			<a href="#" class="rotate" data-rotate="right" data-parent-id="<?= $gallery_id ?>" data-table="<?= $folder ?>" data-file="<?= $file_name?>" data-id="<?= $id ?>"><i class="fa fa-rotate-right"></a></i>
			<br class="clear" />
		</div>
	</li>
<?php }
}


// End file

