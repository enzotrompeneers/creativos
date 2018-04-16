<?
	$tblAlbum = $xname.'_albumes';
	$tblImages = $xname.'_images';

	if ($_SESSION['Admin'] == TRUE) {
		if(isset($_GET['action'])){
		
			if($_GET['action'] == "removeAlbum"){
				mysql_query("DELETE FROM $tblAlbum WHERE id = ".$_GET['album']);
				mysql_query("DELETE FROM $tblImages WHERE fk_album = ".$_GET['album']);
				delete_directory ("images/albums/$album/");
				header ("location:$this_page");
			
			
			}
			
			
			if($_GET['action'] == "removeImg"){
				// REMOVE IMAGE
				$query = "SELECT name,fk_album FROM $tblImages WHERE id = ".$_GET['remId'];
				$sql = record($query);
				$m_img = "images/albumes/".$sql['fk_album']."/m_".$sql['name'];
				$s_img = "images/albumes/".$sql['fk_album']."/s_".$sql['name'];
				unlink ($m_img);
				unlink ($s_img);
				mysql_query("DELETE FROM $tblImages WHERE id = ".$_GET['remId']);
				header("location: $this_page ");


			}
		}
		if(isset($_POST['action'])){
			if($_POST['action'] == "addAlbum"){
				//CREATE ALBUM
				$count = record("SELECT COUNT(*) as 'count' FROM $tblAlbum WHERE name_es = '".$_POST['name_es']."'");
				if($count['count'] == 0){
				if ($_POST['name_es']) {
					$clave=slug($_POST['name_es']);
				} else { 
					$clave = random_number(10000);
				}
					$update = "INSERT INTO $tblAlbum (name_es, name_en,clave,cliente, descr_es, descr_en) VALUES ('".$_POST['name_es']."','".$_POST['name_en']."','".$clave."','".$_POST['cliente']."','".$_POST['descr_es']."','".$_POST['descr_en']."')";
					//echo "<p>".$update."</p>";
					mysql_query($update);
				}else{
					$err = "<p>Ya hay un albúm con nombre <em>'".$_POST['name_es']."'</em></p>";
				}
			}
			if($_POST['action'] == "addImages"){
				//ADD IMAGES TO AN ALBUM
				$imgdir = "images/albumes/{$_POST['fk_album']}";
				if(!file_exists($imgdir)) (mkdir($imgdir,0777));

				if(isset($_FILES['imagen'])){
					$ordenCount = 0;
					foreach ($_FILES['imagen']['error'] as $key => $error) {
					   if ($error == UPLOAD_ERR_OK) {
						   	echo "$error_codes[$error]";
						   
						   	$tempfile = $_FILES['imagen']['tmp_name'][$key];
						   	$savefile = $_FILES['imagen']['name'][$key];
						   
							pictureresize($tempfile,"$imgdir/m_$savefile",750,550);
							//$copied = copy($tempfile, "$imgdir/m_$savefile");
							pictureresize($tempfile,"$imgdir/s_$savefile",200,85);
							
							$update = "INSERT INTO $tblImages (name, fk_album, orden) VALUES ('{$savefile}','{$_POST['fk_album']}',{$ordenCount})";
							//echo("<p>".$update."</p>");
							mysql_query($update);
							$ordenCount = $ordenCount + 1;						
					   }
					}
					$fk_album = $_POST['fk_album'];
				}
			}		
		}
	}
	
	$albumes = dataset("SELECT * FROM $tblAlbum ORDER BY name_es ASC");
	
    $last_id = select_max('id','brunel_albumes');
	$fk_album = (isset($_POST['fk_album'])) ? $_POST['fk_album'] : $last_id;
?>

	<!-- GALERIA -->
      <?php if ($_SESSION['Admin'] == TRUE) {?>
      <h1>1. Create Album</h1>
      <?= $err ?>
      <form id="crearAlbum" method="post" action="">
        <fieldset>
        <input type="hidden" class="hidden"name="action" value="addAlbum" style="display:none;" />
        <label for="name">Album name:</label>
        <input type="text" name="name_es" class="es" />
        <input type="text" name="name_en" class="en" /><br clear="all" />

        <input type="submit" name="submit" value="Crear albúm" />
        </fieldset>
      </form>
	  <br clear="all" /> 
	  <br clear="all" /> 
      <h1>2. Edit Album</h1>
	        <form id="selectAlbum" action="" method="post">
        <select id="fk_album" name="fk_album">
          <?php 

            if(isset($albumes)){
                foreach($albumes as $k => $v){
				?>	
                	<option value="<?=$v['id']?>" <?php if($v['id'] == $fk_album){ ?>selected="selected"<?php } ?>><?=$v['name_es']?></option>
                <?
                }
            }
        ?>
        </select>
      </form>
        <div id="gal">



	  <br clear="all" />
          <div id="albumHolder" style="clear:both;">
          </div>
          <div id="response"> </div>
        </fieldset>
        <br clear="all" />

        <form id="subirImagenes" method="post" action="" enctype="multipart/form-data">
        <fieldset>
          <input type="hidden" class="hidden" name="action" value="addImages" />
          <input type="hidden" class="hidden" id="test" name="fk_album" value="<?= $fk_album ?>" />
          <label for="imagen" style="margin-top: 6px;">Seleccionar imagenes:</label><br clear="all" />
          <input type="file" name="imagen[]" class="multi" accept="jpg" />
          <input type="submit" name="submit" value="Subir imagenes" />
        </fieldset>
        </form>

      </div>
      <?php } ?>    
    
	<!-- FIN GALERIA -->   