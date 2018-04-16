<?php
// Last upadate: 29/12/2010
// For Eco-Homes

$gal_inc = TRUE;

/***** SHOW GALLERY FUNCTION ****/

function show_gallery($clave){
	global $xname,$language;
	$table_name = 'albumes';
	$table_name_single = 'album';
	
	$album_sql = "SELECT * FROM {$xname}_{$table_name} WHERE clave = '{$clave}'";
	//echo $album_sql;
	$album = record($album_sql);
	
	$id = $album['id'];
	$titulo = $album['titulo_'.$language];
	
	$images_sql = "SELECT * FROM {$xname}_images_{$table_name} WHERE {$table_name_single}_id = {$id} ORDER BY orden ASC";
	//echo $images_sql;
	
	$images = dataset($images_sql);
	
	if ($images) { 
	//print_r($images); 
	$imagefile = $images[0];
	$image = $imagefile['file_name'];
	//echo $image;
	$rutaImg = (!empty($image)) ? 'images/'.$table_name.'/'.$id.'/g_'.$image : 'images/no_image_s.jpg';
	//echo $rutaImg;
	?>
	<div id="comment"><?php
	//$titulo = ($clave=='villa_29')?'Modelo Luisma':'Modelo Luisma';
	//echo $titulo;
	?></div>
	<a href="<?=$rutaImg?>" class="thickbox" style="border:0;" id="big_link"><img src="<?=$rutaImg?>" alt="<?=$titulo?>" id="main_image" style="width:405px;"  /></a>
	</span>
	<ul id="galeria">
	<?php
		$images_sql = "SELECT * FROM {$xname}_images_{$table_name} WHERE {$table_name_single}_id = {$id} ORDER BY orden ASC";
		//echo $images_sql;
		$images = dataset($images_sql);
		foreach ($images as $k=>$v){
			$image = $v['file_name'];
			echo '<li><a href="images/'.$table_name.'/'.$id.'/g_'.$image.'" title="'.$titulo.'" rel="'.$v['id'].'" style="height:40px;overflow:hidden;"><img src="images/'.$table_name.'/'.$id.'/s_'.$image.'" alt="'.$titulo.'"    /></a></li>';
		}
	?>
	</ul> 

<?php } } 

function show_galleria ($folder='articulos',$parent_id) {
	global $language,$xname;
	$imagesQuery = "SELECT * FROM {$xname}_images_{$folder} WHERE parent_id = {$parent_id} ORDER BY orden ASC";
	// echo $imagesQuery;
	$images = dataset($imagesQuery);
	// printout($images);
	include('view_galleria.php');
}

// Helper function for below....
function has_images($clave) {
	global $language,$xname;
	$testQuery = "
					SELECT i.id
					FROM {$xname}_images_articulos i
					JOIN {$xname}_articulos a ON i.parent_id = a.id
					WHERE clave = '{$clave}'
					ORDER BY i.orden,i.id
					";
	$testSql = record($testQuery);
	if ($testSql){ return TRUE; } else { return FALSE; }
}

//*** returns gallery in array ***/
function show_gallery_img ($clave,$size='l') {
	global $language,$xname;
	
	$clave = (has_images($clave))?$clave:'welkom';
	
	$albumSelect = "
					SELECT *,a.id AS aid
					FROM {$xname}_images_articulos i
					JOIN {$xname}_articulos a ON i.parent_id = a.id
					WHERE clave = '{$clave}'
					ORDER BY i.orden,i.id
					";
				// echo $albumSelect;
	$album = dataset($albumSelect);
	$oAlbum = array();
	foreach ($album as $k=>$v) {
		$oAlbum[] = 'images/articulos/'.$v['aid'].'/'.$size.'_'.$v['file_name'];
	}
	// printout($oAlbum);
	return $oAlbum;
}

/***** SHOW PANORÁMICAS FUNCTION ****/
function show_panoramicas($id=1){
	global $xname,$language;
	// Cargador de imágenes panorámicas
	$panQuery = "SELECT * FROM {$xname}_images_panoramicas WHERE parent_id = 0 ORDER BY orden";
	$panoramicas = dataset($panQuery);
	$nPan = 1;
	// if ($panoramicas) {
		include('view_panoramicas.php');
	// }
}


/***** SHOW MINIMAL GALLERY FUNCTION ****/

function show_gallery_min($clave){
	global $xname,$language;
	$table_name = 'albumes';
	$table_name_single = 'album';
	
	$album_sql = "SELECT * FROM {$xname}_{$table_name} WHERE clave = '{$clave}'";
	//echo $album_sql;
	$album = record($album_sql);
	
	$id = $album['id'];
	$titulo = $album['titulo_'.$language];
	
	$images_sql = "SELECT * FROM {$xname}_images_{$table_name} WHERE {$table_name_single}_id = {$id} ORDER BY orden ASC";
	//echo $images_sql;
	
	$images = dataset($images_sql);
	
	if ($images) { 
	echo '<h3>'.$titulo.'</h3>';
	echo '<ul class="gallery">';
		foreach ($images as $k=>$v){ 
			$image = $v['file_name'];
			$descr = $v['descr_'.$language];
			echo '<li><a href="images/'.$table_name.'/'.$id.'/l_'.$image.'" title="'.$descr.'" rel="'.$clave.'" class="colorbox"><img src="images/'.$table_name.'/'.$id.'/s_'.$image.'" alt="'.$descr.'"  /></a></li>';
		}
	echo '</ul>'."\n";
	}
 }
 
 // End of file
