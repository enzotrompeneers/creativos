<?php
	set_include_path('../');
	require_once("../lib/admin.php");
	// Make sure session is TRUE
	if ($_SESSION['Admin'] != TRUE) { header('Location: ../admin/');break;}

	$gallery_id 		= $_GET['id'];
	$table 				= $_GET['table'];
	$img_id 			= $_GET['imageId'];
	$table_array 		= explode("_",$table);
	$folder 			= $table_array[1];
	$folder_sing 		= rtrim($folder,'s');
	if 					($folder == 'albumes') { $folder_id = 'album_id'; }
	$tableImg 			= $xname.'_images_'.$folder;
?>
<style type="text/css">
#comentarios textarea {
 border:1px solid #ccc;
 padding:2px;
 width:220px;
}
#flags { margin:0; }
</style>
<script type="text/javascript" src="../js/libs/jquery.form.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
<?php
/*** LANGUAGE SELECT ***/

 foreach ($languages as $k => $v) { 
		if ($k!=0) { echo '$(".'.$v.'").css("display","none");'."\n";}
	}
	
 foreach ($languages as $k => $v) { 
	echo "$('.click_{$v}').click(function() { \n
		$('.{$v}').show(); \n
		$('.{$v}_slug').show(); \n
		$('.{$v}_art').show(); \n";
		 foreach ($languages as $x => $y) { 
			if ($v!=$y) { 
				echo "$('.{$y}').hide(); 
				$('.{$y}_slug').hide(); 
				$('.{$y}_art').hide(); "; 
			}
		 }
		 echo'
	});';
} 
?>
var options = { 
	target:  '#message',
	success: function(){
		$('#message').fadeIn().delay(3000).fadeOut();
	}
}; 
$('#comentarios').ajaxForm(options);
});
</script>
<?php
	$query = "SELECT * FROM {$tableImg} WHERE id = {$img_id}";
	$sql = record($query);
?>
<div id="comentarioWrap" style="width:450px;padding:24px;margin:24px;border:1px solid #ccc;">
<div id="flags">
<?php foreach ($languages as $k => $v) { ?>
	<img src="images/flags/<?=$v?>.gif" class="click_<?=$v?>" />&nbsp;
<?php } ?>
</div><!--/flags-->	
<br />
<form action="comentarios_doit.php?act=edit&table=<?=$tableImg?>&id=<?=$img_id?>" method="post" id="comentarios">
<?php
foreach ($languages as $k=>$v) {
	$descr = (!empty($sql['descr_'.$v]))  ? $sql['descr_'.$v] : '' ;
	echo '<textarea name="descr_'.$v.'" id="'.$v.'" class="'.$v.'"  cols="10" rows="7" style="width:100%;background:url(images/flags/'.$v.'.gif) top right no-repeat">'.$descr.'</textarea>';
}
?><input type="submit" value="GUARDAR" class="btn green btn-block " />
		<img src="<?='../images/'.$folder .'/'.$id.'/m_'.$sql['file_name']?>" alt="" style="float:right;border:1px solid #ccc;width:100%;" style="width:100%;" />
		
		<br clear="all" />
		
	</form>
	<br clear="all" />
	<div id="message"></div>
</div>