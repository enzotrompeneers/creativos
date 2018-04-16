<?php
/****** Product helpers ******/

// Function: Gets categorias by familia
function get_categorias ($familia_id){
	global $xname,$language;
	$catQuery = "SELECT *,nombre_{$language} AS nombre FROM {$xname}_categorias WHERE familia_id = {$familia_id}";
	$categorias = dataset($catQuery);
	return $categorias;
}

// Function: gets subcategorías by supplied categoría
function get_subcategorias ($categoria_id){
	global $xname,$language;
	$query = "SELECT *,nombre_{$language} AS nombre FROM {$xname}_subcategorias WHERE categoria_id = {$categoria_id}";
	$sql = dataset($query);
	return $sql;
}

// Gets familia, categoria, subcategoria slug from id	
function product_slug($id,$tipo){
	global $xname,$language;
	$query = "SELECT slug_{$language} AS slug FROM {$xname}_{$tipo} WHERE id = {$id}";
	$sql = record($query);
	$slug = $sql['slug'];
	return $slug;
}

// Gets name from familia, categoria or subcategoria s from id	
function product_name($id,$tipo){
	global $xname,$language;
	$query = "SELECT nombre_{$language} AS slug FROM {$xname}_{$tipo} WHERE id = {$id}";
	$sql = record($query);
	$slug = $sql['slug'];
	return $slug;
}
// Gets full product link
function get_product_link ($id){
	global $xname,$language;
	$query = "
			SELECT p.id AS pid,
			p.nombre_{$language} AS nombre,
			f.slug_{$language} AS f_slug,
			c.slug_{$language} AS c_slug,
			s.slug_{$language} AS s_slug
			FROM {$xname}_productos p
				JOIN {$xname}_familias f
					ON f.id = p.familia_id
				JOIN {$xname}_categorias c
					ON c.id = p.categoria_id
				JOIN {$xname}_subcategorias s
					ON s.id = p.subcategoria_id
			WHERE p.id = {$id}
	";
	$sql = record($query);
	$link = $language.'/'.slugged('productos').'/'.$sql['f_slug'].'/'.$sql['c_slug'].'/'.$sql['s_slug'].'/'.slug($sql['nombre']).'-'.$sql['pid'].'.html';
	return $link;
}

// Creates phrase from data
function frase ($dormitorios,$tipo,$poblacion) {
	global $language;
	switch ($language) {
		case 'en':
			$frase1 = ($tipo!='Business premises')?$dormitorios.' bedroom ':'';
			$frase = $frase1.$tipo.' in '.$poblacion;
			if ($dormitorios=='any'){ $frase = $tipo.' in '.$poblacion; }
			break;
		case 'se':
			$frase1 = ($tipo!='Business premises')?$dormitorios.' '.trad("bedroom").' ':'';
			$frase = $frase1.$tipo.' i '.$poblacion;
			if ($dormitorios=='any'){ $frase = $tipo.' in '.$poblacion; }
			break;			
		case 'es':
			$plural = ($dormitorios==1)?'dormitorio':'dormitorios';
			$frase1 = ($tipo!=7)?' de '.$dormitorios.' '.$plural:'';
			$frase = $tipo.$frase1.' en '.$poblacion;
			break;
		default:
			$frase1 = ($tipo!='Business premises')? $dormitorios.' '.trad("bedroom").' ':'';
			$frase = $frase1.$tipo.' in '.$poblacion;
			if ($dormitorios=='any'){ $frase = $tipo.' in '.$poblacion; }
			break;		
	}
	return $frase;
}

// Creates phrase from data
function frase_zona ($tipo,$zona) {
	global $language;
	switch ($language) {
		case 'en':
			$frase = $tipo.' in '.$zona;
			break;
		case 'se':
			$frase = $tipo.' in '.$zona;
			break;			
		case 'es':
			$frase = $tipo.' en '.$zona;
			break;
		default:
			$frase = $tipo.' in '.$zona;
			break;		
	}
	return $frase;
}

// Product helpers