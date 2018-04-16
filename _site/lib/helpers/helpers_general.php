<?php
// General helper functions

// Clean POST & GET
function req($input) {
if (isset($_GET[$input])) {
	$value	= mysql_real_escape_string($_GET[$input]);
} else {
	$value	= (isset($_POST[$input]))?mysql_real_escape_string($_POST[$input]):null;
}
	return $value;
}


// descarga desde un directorio
function download_page($path){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$path);
        curl_setopt($ch, CURLOPT_FAILONERROR,1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        $retValue = curl_exec($ch);                      
        curl_close($ch);
        return $retValue;
}

// *** HELPER FUNCTIONS *** //
// Kill magic quotes
if (get_magic_quotes_gpc()) {
    function stripslashes_deep($value)
    {
        $value = is_array($value) ?
                    array_map('stripslashes_deep', $value) :
                    stripslashes($value);
        return $value;
    }
    $_POST = array_map('stripslashes_deep', $_POST);
    $_GET = array_map('stripslashes_deep', $_GET);
    $_COOKIE = array_map('stripslashes_deep', $_COOKIE);
    $_REQUEST = array_map('stripslashes_deep', $_REQUEST);
}

//comprueba si una direccion de email es correcta
function valid_email($email) {
	if(preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/", $email)) {
		return True;
	}
	else {
		return False;
	}
}



// Resizes images depending on parameters entered
function pictureresize($source, $dest, $MAXWIDTH, $MAXHEIGHT){
	// Image type
	$format = pathinfo($dest, PATHINFO_EXTENSION);
	printout($dest);
	if ($format=='png') {
		$p_source=imagecreatefrompng($source);
	} else {
		$p_source=imagecreatefromjpeg($source);
	}
	list($width, $height) = getimagesize($source);
	$scale=min($MAXWIDTH/$width, $MAXHEIGHT/$height);
	$new_width=floor($width*$scale);
	$new_height=floor($height*$scale);
	$p_img = imagecreatetruecolor($new_width,$new_height);
	imagecopyresampled($p_img, $p_source, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
	if(imagejpeg($p_img, $dest)) echo "";
}

// Creates folder with the supplied permissions
function mkdir_rec($dir, $access=0777){
	// Make dir and set correct permissions
	$bits = split("/", $dir);	$newlevel = '.';
	foreach($bits as $level) {
		$newlevel .= '/' . $level;
		if(!file_exists($newlevel)) {
			echo "Dir: $newlevel ...";
			mkdir($newlevel, 0777);
		}
	}
}


// Returns a random number with a maximum of $max
function random_number($max) {
srand(time());
$random = (rand()%$max)+1;
return ($random);
}
$random_max = 7;
function random_string($length) {
    $chars = "abcdefghijkmnopqrstuvwxyz023456789";
    srand((double)microtime()*1000000);
    $i = 0;
    $pass = '' ; 
    while ($i <= $length-1) {
        $num = rand() % 33;
        $tmp = substr($chars, $num, 1);
        $pass = $pass . $tmp;
        $i++;
    }
    return $pass; 
}
// Returns the full querystring of the current URL
$querystring = "?";
foreach($_GET as $k=>$v){ 
	if ($k!='page' && $k!='submit'){
		$querystring = $querystring.$k.'='.$v.'&';
		}
} 


// Converts any string to slug format (no funny characters or spaces, everything seperated by '-')
function slug($snail) {
 
 $a = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'ß', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'Ā', 'ā', 'Ă', 'ă', 'Ą', 'ą', 'Ć', 'ć', 'Ĉ', 'ĉ', 'Ċ', 'ċ', 'Č', 'č', 'Ď', 'ď', 'Đ', 'đ', 'Ē', 'ē', 'Ĕ', 'ĕ', 'Ė', 'ė', 'Ę', 'ę', 'Ě', 'ě', 'Ĝ', 'ĝ', 'Ğ', 'ğ', 'Ġ', 'ġ', 'Ģ', 'ģ', 'Ĥ', 'ĥ', 'Ħ', 'ħ', 'Ĩ', 'ĩ', 'Ī', 'ī', 'Ĭ', 'ĭ', 'Į', 'į', 'İ', 'ı', 'Ĳ', 'ĳ', 'Ĵ', 'ĵ', 'Ķ', 'ķ', 'Ĺ', 'ĺ', 'Ļ', 'ļ', 'Ľ', 'ľ', 'Ŀ', 'ŀ', 'Ł', 'ł', 'Ń', 'ń', 'Ņ', 'ņ', 'Ň', 'ň', 'ŉ', 'Ō', 'ō', 'Ŏ', 'ŏ', 'Ő', 'ő', 'Œ', 'œ', 'Ŕ', 'ŕ', 'Ŗ', 'ŗ', 'Ř', 'ř', 'Ś', 'ś', 'Ŝ', 'ŝ', 'Ş', 'ş', 'Š', 'š', 'Ţ', 'ţ', 'Ť', 'ť', 'Ŧ', 'ŧ', 'Ũ', 'ũ', 'Ū', 'ū', 'Ŭ', 'ŭ', 'Ů', 'ů', 'Ű', 'ű', 'Ų', 'ų', 'Ŵ', 'ŵ', 'Ŷ', 'ŷ', 'Ÿ', 'Ź', 'ź', 'Ż', 'ż', 'Ž', 'ž', 'ſ', 'ƒ', 'Ơ', 'ơ', 'Ư', 'ư', 'Ǎ', 'ǎ', 'Ǐ', 'ǐ', 'Ǒ', 'ǒ', 'Ǔ', 'ǔ', 'Ǖ', 'ǖ', 'Ǘ', 'ǘ', 'Ǚ', 'ǚ', 'Ǜ', 'ǜ', 'Ǻ', 'ǻ', 'Ǽ', 'ǽ', 'Ǿ', 'ǿ','а', 'б', 'в', 'г', 'д', 'ђ', 'е', 'ж', 'з', 'и', 'ј', 'к', 'л', 'љ', 'м', 'н','њ', 'о', 'п', 'р', 'с', 'т', 'ћ', 'у', 'ф', 'х', 'ц', 'ч', 'џ', 'ш','й','ы','А', 'Б', 'В', 'Г', 'Д', 'Ђ', 'Е', 'Ж', 'З', 'И', 'Ј', 'К', 'Л', 'Љ', 'М', 'Н','Њ', 'О', 'П', 'Р', 'С', 'Т', 'Ћ', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Џ', 'Ш','Й','Ы','Я','я');
 $b = array('A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'D', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'IJ', 'ij', 'J', 'j', 'K', 'k', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'l', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o', 'O', 'o', 'O', 'o', 'OE', 'oe', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'S', 's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Y', 'Z', 'z', 'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A', 'a', 'AE', 'ae', 'O', 'o','a', 'b', 'v', 'g', 'd', 'd', 'e', 'z', 'z', 'i', 'j', 'k', 'l', 'lj', 'm', 'n', 'nj', 'o', 'p','r', 's', 't', 'c', 'u', 'f', 'h', 'c', 'c', 'dz', 's','j','y','A', 'B', 'B', 'G', 'D', 'D', 'E', 'Z', 'Z', 'I', 'J', 'K', 'L', 'LJ', 'M', 'N', 'NJ', 'O', 'P','R', 'S', 'T', 'C', 'U', 'F', 'H', 'C', 'C', 'DZ', 'S','J','Y','Ya','ya');
 $snail =  str_replace($a, $b, $snail); 
 $snail = strtolower($snail);
 $snail = preg_replace('/[^a-z0-9-]/', '-', $snail);
 $snail = preg_replace('/-+/', "-", $snail);
 $snail = rtrim($snail,'-');
 $slug = $snail;
 return $slug;
}

// Deletes directory and all contents
 function delete_directory($dirname)  {
    if (is_dir($dirname))
       $dir_handle = opendir($dirname);
    if (!$dir_handle)
       return false;
    while($file = readdir($dir_handle)) {
       if ($file != "." && $file != "..") {
          if (!is_dir($dirname."/".$file))
             unlink($dirname."/".$file);
          else
             delete_directory($dirname.'/'.$file);    
       }
    }
    closedir($dir_handle);
    rmdir($dirname);
    return true;
	}

	// returns number of files in folder

function num_files($dir, $recursive=false, $counter=0) {
    static $counter;
    if(is_dir($dir)) {
      if($dh = opendir($dir)) {
        while(($file = readdir($dh)) !== false) {
          if($file != "." && $file != "..") {
              $counter = (is_dir($dir."/".$file)) ? num_files($dir."/".$file, $recursive, $counter) : $counter+1;
          }
        }
        closedir($dh);
      }
    }
    return $counter;
  }
	
// Formats precio depending on language
function precio($precio) {
	global $language;
	switch ($language) {
		case 'en':
			return number_format($precio, 0, '.', ',');
			break;
		case 'es':
			return number_format($precio, 0, ',', '.');
			break;
		default:
			return number_format($precio, 0, ',', '.'); 
			break;
	}
}

// Formats precio depending on language
function format_currency($precio) {
	global $language;
	switch ($language) {
		case 'en':
		return number_format($precio, 2, '.', ',');
		break;
		case 'es':
		return number_format($precio, 2, ',', '.');
		break;
		default:
		return number_format($precio, 2, ',', '.'); 
		break;
	}
	// $precio = money_format('%!.2n', $precio);
	return $precio;
}	

// Print_r but with the <pre>
function printout($array) {
	echo '<pre style="font-size:14px;padding:24px;background:#eee;color:#333;width:auto;float:left;margin:0 0 24px;border:1px solid #ccc;">';
	print_r($array);
	echo '</pre>';
}


/**
 * Retrieves all metadata from a given file by his path
 */
function filedata($path) 
{
	global $mimetypes;
	clearstatcache();		// Vaciamos la caché de lectura de disco 
	$data["exists"]		= is_file($path);				// Comprobamos si el fichero existe 
	$data["writable"]	= is_writable($path);			// Comprobamos si el fichero es escribible 
	$data["chmod"]		= ($data["exists"] ? substr(sprintf("%o", fileperms($path)), -4) : FALSE);	// Leemos los permisos del fichero 
	$data["ext"]		= substr(strrchr($path, "."),1);	// Extraemos la extensión, un sólo paso 
	$data["path"]		= array_shift(explode(".".$data["ext"],$path));	// Primer paso de lectura de ruta  
	$data["name"]		= array_pop(explode("/",$data["path"]));		// Primer paso de lectura de nombre  
	$data["name"]		= ($data["name"] ? $data["name"] : FALSE);		// Ajustamos nombre a FALSE si está vacio
	$data['mime']		= is_array($mimetypes[$data["ext"]])? $mimetypes[$data["ext"]][0]: $mimetypes[$data["ext"]]; 
 
	// Por ultimo, ajustamos la ruta a FALSE si está vacia y
	// ajustamos el nombre a FALSE si está vacio o a su valor en caso contrario 
	$data["path"]		= ($data["exists"] ? ($data["name"] ? realpath(array_shift(explode($data["name"],$data["path"]))) : realpath(array_shift(explode($data["ext"],$data["path"])))) : ($data["name"] ? array_shift(explode($data["name"],$data["path"])) : ($data["ext"] ? array_shift(explode($data["ext"],$data["path"])) : rtrim($data["path"],"/")))) ;          
	$data["filename"]	= (($data["name"] OR $data["ext"]) ? $data["name"].($data["ext"] ? "." : "").$data["ext"] : FALSE); 

	return $data; 
}
	

// Huminazing words, clean text, no underscores
function humanize($word){
	$human = '';
	$parts = explode("_", $word); //chop word to pieces and remove the underscore
	foreach($parts as $part) $human .= "$part "; //add parts together
	return ucfirst($human); //uppercase first character
}

// Image crop
function mycrop($src, array $rect)
{
    $dest = imagecreatetruecolor($rect['width'], $rect['height']);
    imagecopy(
        $dest,
        $src,
        0,
        0,
        $rect['x'],
        $rect['y'],
        $rect['width'],
        $rect['height']
    );

    return $dest;
}


// End file