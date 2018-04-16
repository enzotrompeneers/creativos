<?php
	set_include_path('../../');
	require_once("../../lib/admin.php");

	error_reporting(1);

	$gallery_id = $id;
	$table = $_GET['table'];
	$table_array = explode("_",$table);
	$folder = $table_array[1];
	$folder_sing = rtrim($folder,'s');
	if ($folder == 'albumes') { $folder_id = 'album_id'; }
	if ($folder == 'promociones') { $folder_id = 'promocion_id'; }
	if ($folder == 'alquileres') { $folder_id = 'alquiler_id'; }
	//echo $folder_id;
	echo('<script type="text/javascript" src="js/helpers.js"></script>');
	if($_GET['action'] == "removeImg"){
		
		$query = "SELECT file_name FROM ".$_GET['tbl']." WHERE id = ".$_GET['imageId'];
		// echo $query;
		$sql = record($query);
		$file_name = $sql['file_name'];
		
		$del_query = "DELETE FROM ".$_GET['tbl']." WHERE id = ".$_GET['imageId'];
		mysql_query($del_query);
		
		//echo '../images/'.$folder_sing.'/'.$_GET['id'].'/s_'.$file_name;
		
		if (file_exists('../images/'.$folder.'/'.$_GET['id'].'/s_'.$file_name)) { unlink ('../images/'.$folder.'/'.$_GET['id'].'/s_'.$file_name); }
		if (file_exists('../images/'.$folder.'/'.$_GET['id'].'/m_'.$file_name)) { unlink ('../images/'.$folder.'/'.$_GET['id'].'/m_'.$file_name); }
		if (file_exists('../images/'.$folder.'/'.$_GET['id'].'/l_'.$file_name)) { unlink ('../images/'.$folder.'/'.$_GET['id'].'/l_'.$file_name); }
		if (file_exists('../images/'.$folder.'/'.$_GET['id'].'/g_'.$file_name)) { unlink ('../images/'.$folder.'/'.$_GET['id'].'/g_'.$file_name); }
		
		?>
		<script type="text/javascript">
			$.get('lib/helpers/dragdrop.php?rand=<?=random_number(10)?>',{'id':<?=$product_id?>,'table':'<?=$xname.'_productos'?>'}, function(data){
				$('#imagenes').html(data);
			});
		</script>
		
		<?
	
		//header ("location: tipos.php?id={$_GET['id']}");
	}
		$tableImg = $xname.'_images_'.$folder;
		$tableParent = $xname.'_'.$folder; 
		$tableClave = trim($folder,'s');
		if ($folder == 'albumes') { $tableClave = 'album'; }
		if ($folder == 'promociones') { $tableClave = 'promocion'; }
		if ($folder == 'alquileres') { $tableClave = 'alquiler'; }
		//echo $tableParent;
		if ($tableParent==$_GET['table']) { 
		$query = "SELECT * FROM {$tableImg} WHERE {$tableClave}_id = ".$gallery_id." ORDER BY orden ASC";
		//echo $query;
		$ruta = 'images/'.$folder.'/'.$gallery_id;
			}
		//echo $query;


	if(!empty($gallery_id)){
		$result = dataset($query);
	}
	
	if(!empty($result)){
	echo '<ul id="album">';
	foreach($result as $k => $v){
			$id = stripslashes($v['id']);
			$file_name = stripslashes($v['file_name']);
	?>
	<li id="arrayorder_<?php echo $id ?>">
	<p><?=$file_name; ?></p><br/>
		<img src="<?php echo $ruta?>/s_<?php echo $file_name; ?>" /> 
		<div class="clear">
			<a href="<?=$ruta?>/g_<?=$file_name?>" class="verImagen colorbox">Ver imagen</a><br />
			
			<a href="lib/helpers/dragdrop.php?action=removeImg&table=<?=$table?>&tbl=<?=$tableImg?>&imageId=<?=$id?>&id=<?=$gallery_id?>" class="borrarNoticia borrar">Borrar</a>

		</div>
	</li>
<?php }
	echo '</ul>';
}
?>

