<?php
//These helpers are used to select and show content


/*** Get links ***/
//Devuelve un link, en html, el nombre (clave)
function getLinks($clave,$id){
	global $language,$xname;
	$inicio = ($clave=='inicio')?'':slugged($clave).'/';
	$link = '<li><a href="'.$language.'/'.$inicio.'" title="'.title($clave).'" id="'.$id.'_'.$clave.'">'.linkit($clave).'</a></li>';
	return $link;
}

/*** Same as above, but using clave***/
//Igual que el anterior, pero con la ruta absoluta
function getLinksClave($pagina,$clave){
	global $language,$xname;
	$link = '<li id="'.$clave.'"><a href="'.$language.'/'.slugged($pagina).'/'.slugged($clave).'/" title="'.title($clave).' - '.title($pagina).'">'.linkit($clave).'</a></li>';
	return $link;
}

// Devuelve una lista con banderas
function getIdiomas ($clave='idiomas', $reverse=false) {
	global $xname,$language,$languages;
	$langs = ($reverse==true)?array_reverse($languages):$languages;
	$output = '<ul name="'.$clave.'" class="'.$clave.'" id="'.$clave.'">'; 
	foreach ($langs as $l){
		$output .= '<li class="'.$l.'"><a href="'.cambiar_idioma($l).'" title="'.strtoupper($l).'">'.strtoupper($l).'</a></li>';
	}
	$output .= '</ul>';
	return $output;
}

// Gets all translations
function getTrad(){
	global $language, $xname;
	
	$query = "SELECT {$language}, clave FROM {$xname}_traducciones";
	$sql = dataset($query);
	
	$output = array();
	foreach ($sql as $k=>$v){
		$output[$v['clave']] = $v[$language]; 
	}
	
	return $output;
}

// Traduce una clave, si no está traducida devuelve !clave, si no existe la clave la crea
function trad($clave, $lang = null){
	global $language, $languages, $xname, $traducciones;
	$lang = ($lang)?$lang:$language;
	// printout($clave .' ' . $traducciones[$clave]);
	$row = (!empty($traducciones[$clave])) ? $traducciones[$clave] : null;
	
	if (!array_key_exists($clave, $traducciones)) {

		// Specific language trads for fileds that end in _language
		$thisLanguage = '';
		$aTrad = explode('_', $clave);
		$lastElement = array_pop($aTrad);
		if (in_array($lastElement, $languages)) {
			$clave = implode('_', $aTrad);
			$thisLanguage = ' ' . strtoupper($lastElement);
			return trad($clave) . $thisLanguage;
		}
	
		// Add blank translation
		if (TEST == true) {
			$insert	= "INSERT INTO {$xname}_traducciones (clave, used) values('{$clave}', 1)";
			// mysql_query($insert) or die(mysql_error());
			$traducciones[$clave] = '';
			return('!' . $clave);
		}
		
		$traducciones[$clave] = '';
		return('!' . $clave);
		
	} else {
		// Set translation as used in the website
		if (TEST == true) {
			$update	= "UPDATE {$xname}_traducciones SET used = 1 WHERE clave = '{$clave}'";
			// mysql_query($update);
			
		}
		
		if ($row) {
			return ($row);
		} else {
			return('!' . $clave);
		}
		
		
	}
}

// Traduce una clave, si no está traducida devuelve !clave, si no existe la clave devuelve !!clave
function translate($clave, $lang = null){
	
	global $language, $xname;
	
	$lang = ($lang) ? $lang : $language;
	
	$query = "select $lang from ".$xname."_traducciones where clave = '$clave'";
	// echo $query;
	$sql = mysql_query($query);
	$row=mysql_fetch_row($sql);
	// print_r($row);
	if(!$row) {
		return ("!!".$clave);
	} 
	
	if($row[0]=="") {
		return("!".$clave);
	} else {
		return ($row[0]);
	}
}


// Igual que el anterior pero para enlaces
function linkit($clave, $lang=null){
	global $language,$xname;
	$lang = ($lang)?$lang:$language;
	$query = "select link_{$lang} from ".$xname."_articulos where clave = '$clave'";
	//	echo $query;
	$sql = mysql_query($query);
	$row=mysql_fetch_row($sql);
	if(!$row) {
		return ("!!".$clave);
	}
	if($row[0]=="") {
		return("!".$clave);
	} else {
		return ($row[0]);
	}
}

// Igual que las anteriores con el título
function title($clave, $lang=null){
	global $language,$xname;
	$lang = ($lang)?$lang:$language;
	$query = "select titulo_{$lang} from ".$xname."_articulos where clave = '$clave'";
	//	echo $query;
	$sql = mysql_query($query);
	$row=mysql_fetch_row($sql);
	if(!$row) {
		return ("!!".$clave);
	}
	if($row[0]=="") {
		return("!".$clave);
	} else {
		return ($row[0]);
	}
}

// Igual que los anteriores para slug
function slugged($clave, $lang=null){
	global $language,$xname;
	$lang = ($lang)?$lang:$language;
	$query = "select slug_{$lang} from {$xname}_articulos where clave = '$clave'";
	//echo $query;
	$sql = mysql_query($query);
	$row=mysql_fetch_row($sql);
	if(!$row) {
		return ($clave);
	}
	if($row[0]=='') {
		return($clave);
	} else {
		return ($row[0]);
	}
}

// buscar un articulo a partir de una clave, desde helper
function art($clave, $lang=null){
	global $language,$xname;
	$lang = ($lang)?$lang:$language;
	$query = "select art_".$lang." from ".$xname."_articulos where clave = '$clave'";
	$sql = mysql_query($query);
	//echo $query;
	$row=mysql_fetch_row($sql);
	if(!$sql) return "!".$clave;
	$output=$row[0];	
	$helper = (!empty($_SESSION['Admin']) && $_SESSION['Admin']==true)?'<strong>('.$clave.')</strong> ':'';
	// $output = ($output=='')?lipsum():$output;
	return ($output);
}

//Igual que el anterior sin necesidad de estar logeado en el helper
function art_sin($clave, $lang=null){
	global $language,$xname;
	$lang = ($lang)?$lang:$language;
	$sql = mysql_query("select art_".$lang." from ".$xname."_traducciones where clave = '$clave'");
	$row=mysql_fetch_row($sql);
	if(!$sql) return "!".$clave;
	$output=$row[0];	
	return ($output);
}
// Get metas for this artículo
function get_meta($clave,$tipo='descr', $lang=null){
	global $language,$xname;
	$lang = ($lang)?$lang:$language;
	$sql = mysql_query("select meta_{$tipo}_".$lang." from ".$xname."_articulos where clave = '$clave'");
	$row = mysql_fetch_row($sql);
	if(!$sql) return "!".$clave;
	$output = strip_tags($row[0]);	
	return ($output);
}
// Language change function
function cambiar_idioma($language,$claveId='',$nombre='') {
	global $languages,$pagina,$xname,$id,$aFicha,$clave,$tituloVivienda;
	$query = "SELECT * FROM {$xname}_articulos WHERE clave = '{$pagina}'";
	//echo $query;
	
	$sql = record($query);
	$slug = $sql['slug_'.$language];
	$link = ($claveId=='')?$language.'/'.$slug.'/':$language.'/'.$slug.'/'.$claveId.'/';
	$link = ($nombre=='')?$link:$link.slug($nombre).'/';
	$link = ($pagina=='viviendas' && is_numeric($id))?$language.'/'.$slug.'/'.$clave.'/'.slug($tituloVivienda).'-'.$id.'.html':$link;
	if ($pagina=='' || $pagina=='inicio') { $link = $language.'/'; }
	return $link;
}

// Gets first image of table for the id
function first_image($table,$id,$size='m'){
	global $xname,$base_site;
	$base_site = str_replace('webadmin2/','',$base_site);
	// Database query
	$query = "SELECT file_name FROM {$xname}_images_{$table} WHERE parent_id = {$id} ORDER BY orden LIMIT 1";
	// echo $query;
	$sql = record($query);
	
	if ($sql['file_name']!='') {
		$image = $base_site.'images/'.$table.'/'.$id.'/'.$size.'_'.$sql['file_name'];
	} else {
		$image = $base_site.'images/noImage.png';
	}
	if (mb_substr($sql['file_name'],0,4)=='http') $image	=	$sql['file_name'];
	return $image;
}	
// Returns image array
function get_images_articulos($clave) {
	global $language,$xname;
	$query = "
				SELECT a.id AS aid, i.file_name
				FROM {$xname}_images_articulos i
				JOIN {$xname}_articulos a ON i.parent_id = a.id
				WHERE clave = '{$clave}'
				ORDER BY i.orden
				";
	$sql = dataset($query);
	return $sql;
}
// returns doc array
function get_files_articulos($clave){
	global $language,$xname;
	$query = "
				SELECT f.*,SUBSTRING_INDEX(file_name,'.',-1) AS extension
				FROM {$xname}_files_articulos f
				JOIN {$xname}_articulos a ON f.parent_id = a.id
				WHERE a.clave = '{$clave}'
				ORDER BY f.orden
				";
	$sql = dataset($query);
	return $sql;	
	
}

// Returns image array
function get_images($folder,$parent_id) {
	global $language,$xname;
	$query = "
				SELECT a.id AS aid, i.file_name
				FROM {$xname}_images_{$folder} i
				JOIN {$xname}_{$folder} a ON i.parent_id = a.id
				WHERE parent_id = '{$parent_id}'
				ORDER BY i.orden
				";
	$sql = dataset($query);
	return $sql;
}
//texto predeterminado creado automáticamente
function lipsum() { 
	$lipsum = "<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
	<p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>";
	return $lipsum;
}

//Corta una cadena de texto a un número determinado de caracteres
//añadiendo '...' al final de la misma
function shorten_text($text, $chars, $strip = true) {
	// Change to the number of characters you want to display
	$elipsis = (strlen($text) > $chars) ? '...' : '';
	$text = ($strip) ? strip_tags($text) : $text;
	$text = $text." ";
	$text = substr($text,0,$chars);
	$text = substr($text,0,strrpos($text,' '));
	$text = $text.$elipsis;
	return $text;
}

// Current Page for social buttons
function curPageURL() {
	 $pageURL = 'http';
	 // if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
	 $pageURL .= "://";
	 if ($_SERVER["SERVER_PORT"] != "80") {
	  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	 } else {
	  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	 }
	 return $pageURL;
}

// Outputs Metas (descr|key)
function meta($clave,$tipo='descr'){
	global $language,$xname,$pagina;
	$query 		= "SELECT meta_".$tipo."_".$language." FROM ".$xname."_articulos WHERE clave = '$clave'";
	$sql 		= mysql_query($query);
	// echo $query;
	$row 		= mysql_fetch_row($sql);
	if (empty($row[0])){
		if ($tipo=='descr') {
			$output 	= strip_tags(shorten_text(art($pagina),400));
		} else {
			$output 	= keywords(title($pagina).','.keywords(webconfig('nombre')));
		}
	} else {
		$output		= strip_tags($row[0]);	
	}
	
	return strip_tags($output);
}
// Key words
function keywords($frase){
	$frase_array = explode(' ',$frase);
	$frase_key = '';
	foreach ($frase_array as $f) {
		$frase_key .= $f.',';
	}
	$frase_key = rtrim($frase_key, ',');
	return $frase_key;
}
// Gets boolean value and returns Sí/No
function sino($bool){
	$output = '';
	if ($bool==1) {
		$output = trad('si');
	} else {
		$output = trad('no');
	}
	return $output;
}

// Get video code from YouTube link
function getVideoCode($url){
	$querystring = parse_url($url, PHP_URL_QUERY);
	
	if (!$querystring) {
		$aVideo = explode('/', $url);
		$videoCode = end($aVideo);
		return $videoCode;
	};
	
	parse_str($querystring, $aQuerystring);
	$videoCode = $aQuerystring['v'];
	return $videoCode;
}

function getHreflangLinks() {
     
     global $pagina;
     global $base_site;
     global $languages;
     global $language;
     global $id;
     global $xname;
     global $db;
     
     $break = "\n";

     // Si es la página de inicio, el enlace contiene la base del sitio como canonical con el idioma por defecto
     // Para el resto añade el código de idioma a la base
     if ($pagina == 'inicio') {
          foreach($languages as $lang) {
               if ($lang == $language) {
                    echo('<link rel="canonical" href="' . $base_site . '" />' . $break);    
					echo('<link rel="alternate" href="' . $base_site . $lang . '/" hreflang="' . $lang . '" />' . $break);
               } else {
                    echo('<link rel="alternate" href="' . $base_site . $lang . '/" hreflang="' . $lang . '" />' . $break);
               }
          }
     } 
     // En el resto de página añadimos la página al código de idioma, usando la actual como canónica.
     else {
          foreach($languages as $lang) {
               $slugQuery = "SELECT slug_{$lang} FROM {$xname}_articulos WHERE clave = '{$pagina}'";
               $slugSql = $db->record($slugQuery);

               if ($lang == $language) {
                    echo('<link rel="canonical" href="' . $base_site . $lang . '/' . $slugSql['slug_' . $lang] . '/" />'. $break);
					echo('<link rel="alternate" href="' . $base_site . $lang . '/' . $slugSql['slug_' . $lang] . '/" hreflang="' . $lang . '" />'. $break);     					
               } else {
                    echo('<link rel="alternate" href="' . $base_site . $lang . '/' . $slugSql['slug_' . $lang] . '/" hreflang="' . $lang . '" />'. $break);     
               }
              
          }
     }
}

// End file