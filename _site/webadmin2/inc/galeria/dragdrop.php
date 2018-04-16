<?php 	include("../admin.php");?>
<script language="javascript" type="text/javascript">
    $(document).ready(function(){
       $('.borrarNoticia').click(function(){
		return confirm('Do you really want to delete this?');
	   
	   });
    });
</script>

<ul id="album">
<?
	$tblImages = $xname.'_images';
	$query  = "SELECT * FROM $tblImages WHERE fk_album = ".$id." ORDER BY orden ASC";
	//echo $query;
	if ($id) {
		$result = dataset($query);
	}
	$fk_album = $id;
	
	if($result){
		foreach($result as $k => $v){
					
			$id = stripslashes($v['id']);
			$name = stripslashes($v['name']);
					
	?>
            <li id="arrayorder_<?php echo $id ?>"><img src="images/albumes/<?php echo $fk_album?>/s_<?php echo $name; ?>" /> 
                <div class="clear"><a href="../../galeria.php?action=removeImg&amp;fk_album=<?=$fk_album?>&amp;remId=<?=$id?>" class="borrarNoticia">Delete</a></div>
            </li>
	<?php 
		}
    }else{
        echo "No images"; 
    }
?>
</ul>
<? if ($_GET['id']) { ?>
<a href="galeria.php?action=removeAlbum&album=<?=$_GET['id']?>" class="borrarNoticia deleteAlbum">DELETE ALBUM</a>
<? } ?>
