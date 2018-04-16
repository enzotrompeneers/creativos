<?php
// Make sure session is TRUE
if (empty($_SESSION['Admin']) || $_SESSION['Admin'] != true) { header('Location: ../admin');break;}
error_reporting(4); ini_set('display_errors', '1');
// error_reporting(E_ALL ^ E_DEPRECATED);


// Language
if (isset($_SESSION['language'])){
	$language = $_SESSION['language'];
} else {
	if (!empty(req('idioma'))) {
		
		$language = req('idioma');
		$_SESSION['language'] = $language;
	} else {
		$language = $languagesWebadmin[0];
		$_SESSION['language'] = $language;
	}
}



// Returns translation or humanization depending on $translations variable
$traducciones = getTrad();
function show_label($clave) {
	global $language,$xname,$translations;
	if ($translations==TRUE) {
		return trad($clave);
	} else {
		return humanize ($clave);
	}
}

	// Getting the column names
	function column_names($tbl_name) {
		$query = "SELECT * FROM $tbl_name WHERE 1 = 0";
		if(!($result_id = mysql_query ($query))) return false;
		$names = array();
		for($i = 0; $i < mysql_num_fields($result_id); $i++){
			if($field = mysql_fetch_field ($result_id, $i)) $names[] = $field->name;
		}
		mysql_free_result($result_id);
		return $names;
	}
	
	// Handling the inserting/updating of a query
	function compare_save($id, $table, $k, $v){
		$update = "update $table set $k = '$v' where id = $id";
		$insert = "insert into $table ($k) values ('$v')";
		$sql = ($id > 0) ? $update : $insert;
		//echo("<p>".$sql."</p>");
		if($k != 'id') mysql_query($sql);
		return (($id > 0) ? $id : mysql_insert_id());
	}
	
	// Geting the column names from the table
	function initListing($table){
		$query = "SHOW FULL COLUMNS FROM {$table}";
		 // echo $query;
		$metadata = dataset($query);
		// we get the column names
		$form = column_names($table);
		global $arrMetaFields;
		global $arrMetaTypes;
		$arrMetaFields = array();
		$arrMetaTypes = array();
		// we add the column names and fieldtypes to an array
		$i = 0;
		while(++$i < count($form)){
			$metafield = $metadata[$i]['Field'];
			$metatype = substr($metadata[$i]['Type'],0,3);
			array_push($arrMetaFields,$metafield);
			array_push($arrMetaTypes,$metatype);
		}
	}
	
	// Creating the form
	function form_better($table, $record=array(),$textarea=array(),$bools=array(),$ignore=array(),$pagestarts=array()){
		// Get the column names
		$metadata = dataset("show full columns from $table"); 
		$form = column_names($table);
		
		if(count($pagestarts) > 0) $s = "<div id='tab_start' class='tab-pane active'>";
		$i = 0;
	
		while(++$i < count($form) && $form[$i]){
			// Get the field type: varchar, text,...
			$metatype = substr($metadata[$i]['Type'],0,3);
			// Get the field comment
			$comment = $metadata[$i]['Comment'];
			$hasparent = (strpos($form[$i],"_id")) ? 1 : 0;
			// Checks to determine field type
			$type = ($metatype == 'tex') ? 'textarea' : (($hasparent > 0 || $metatype == 'enu') ? "select" : "text");
			if($comment == 'checkbox') $type='checkbox';
			if($comment == 'noedit') $type='textarea-noedit';
			if($comment == 'file') $type='file';
			if($comment == 'color') $type='color';
			
			if(strpos($comment,'options:')!==false) $type = 'iselect';
			// If we are dealing with a foreign key
			if(preg_match("/_id$/",$form[$i])) {
				$parent = pluralize(substr($form[$i],0,-3));
			}else{
				if($metatype == 'enu'){
					$values = dataset("select * from {$form[$i]}");
					$valstr = "'" . implode("','",$values) . "'";
					foreach($values as $k => $v)
						$enums[] = $v['name'];
					mysql_query("alter table $table modify column {$form[$i]} enum($valstr)");
				}
				$parent = ($type == 'select') ? $form[$i] : $table;
			}
			//make tab divs
			foreach($pagestarts as $k => $v){
				if($form[$i] == $k) 
					$s .= "</div><div id='tab_$k' class='tab-pane'>";
				//if(in_array($form[$i], $v)) $s .= "<div class='subformpage'>";
			}			
			if(in_array($form[$i], $ignore)) continue;
			$thisValue = (!empty($record[$form[$i]])) ? $record[$form[$i]] : '';
			$s .= label($form[$i], $thisValue, $type, $parent);
		}
		if(count($pagestarts) > 0)	$s .= "<br clear='all' /></div>";
		return $s;
	}
	
	// Labeling function
	function label($name, $value='',$type='text',$table=''){
		global $id,$xname,$language;

		// Create the labels
		$labelname = str_replace("_id","",$name); //remove the _id
		$s = '<div class="form-group">'.PHP_EOL;
		$s .= '<label for="'.$name.'" class="control-label col-md-2 ">'.show_label($labelname).'</label>'.PHP_EOL; 
		// In case of a select
		$s .= '<div class="col-md-10">'.PHP_EOL;
		if($type=='select'){

			$campo = 'nombre_'.$language;
		
			// Select dropdown
			$s .= '<select name="'.$name.'" id="'.$name.'" class="form-control input-xlarge select2me" data-placeholder="Select...">'.PHP_EOL;
			$s .= options($table, $campo, $value);
			$s .= '</select>'.PHP_EOL;
			$s .='<a href="add_field.php?field='.$name.'&table='.$table.'&title=" class="btn green add-value" data-toggle="modal" data-target="#ajax">'.show_label("crear_valor").'</a>';
			$s .= '</div></div>'.PHP_EOL;
			return $s;
		}
		if($type=='iselect'){
			$table_array 	= explode("_",$table);
			$folder			= $table_array[1];
			$comment 		= get_comment($folder,$name);
			$comment_array	= explode(":",$comment);
			$ioptions		= explode(',',$comment_array[1]);
			
			// Select dropdown
			$s .= '<select name="'.$name.'" id="'.$name.'" class="form-control input-xlarge select2me" data-placeholder="Select...">'.PHP_EOL;
			foreach ($ioptions as $i) {
				$selected = ($i==$value)?' selected ':'';
				$s .= '<option value="'.$i.'" '.$selected.'>'.$i.'</option>';
			}
			$s .= '</select>'.PHP_EOL;
			$s .= '</div></div>'.PHP_EOL;
			return $s;
		}
		// If field comment is 'checkbox'
		$checked = (intval($value) == 1 && $type == 'checkbox') ? "checked" : "";
		$notchecked = ($checked != "checked") ? "checked":"";
		
		// Name of folder
		$table_array = explode("_",$table);
		$folder = $table_array[1];

		 
	switch ($type) {
		case 'text': // Normal input
			$nameArray = explode('_',$name);
			$class = ($nameArray[0]=='fecha')?'date-picker':'';
			$s .= '<input type="text" name="'.$name.'" value="'.$value.'" id="'.$name.'" class="'.$class.' form-control input-xlarge" />';
		 break;
		case 'checkbox': // Checkbox
			$s .= '<div class="radio-list"><label class="radio-inline"><input type="radio"  name="'.$name.'" value=1 '.$checked.' id="'.$name.'" /> '.show_label('si').'</label>
			<label class="radio-inline"><input type="radio"  name="'.$name.'" value="0" '.$notchecked.'  id="'.$name.'" /> '.show_label('no').'</label></div>';
			break;
		case 'file': // File upload
			$value_array = explode('.',$value);
			$upper = count($value_array);
			$ext = $value_array[1];
			$extensions = array('jpg','gif','png','txt');
			if (in_array($ext,$extensions,true)) {
				$thickbox = ' colorbox';
			} else {
				$target = ' target="_new"';
			}
			$s .= '<input type="file" name="'.$name.'" value="'.$value.'" id="'.$name.'" /><br clear="all" />
			<label class="noborder"></label><div class="alert alert-info" id="file_'.$name.'"><a href="../images/'.$folder.'/'.$id.'/'.$value.'" class="floatLeft  '.$thickbox.'" '.$target.'>'.$value.'</a></div>';
			if ($value!='') {
				$s .= '<a href="del_file.php?id='.$id.'&file='.$value.'&field='.$name.'&table='.$table.'" class="remove_file ajax btn red" id="del_'.$name.'"><i class="fa fa-times"> </i>&nbsp;'.show_label('borrar').'</a>';
			}
			break;
		case 'color':
			$s .= '<input type="text" name="'.$name.'" value="'.$value.'" id="'.$name.'" class="'.$class.' form-control input-xlarge minicolors" />';
			break;
		case 'textarea-noedit': // Textarea without rich text editor
				$s .= "<div class=\"textarea\"><textarea rows='' cols='' class='noEdit' name='$name' id='$name'>$value</textarea></div><br  />";
			break;
		default: // Textarea with rich text editor
			$s .= "<div class=\"textarea\"><textarea rows='' cols='' class='ckeditor' name='$name' id='$name'>$value</textarea></div><br  />";
			break;
		
	}	
	$s .= '</div></div>';
	return $s;
	}
	
	
	// Options for a selectbox
	
	function options($table, $text, $match){
	global $xname,$optionsArray,$language,$pluralArray,$exceptionsArray;
	// printout($pluralArray);
	if ($pluralArray) {
		foreach ($pluralArray as $k=>$v) {
			$table = ($table==$xname.'_'.$k)?$xname.'_'.$v:$table;
		}
	}
		if(strpos($match,'_id')) {
			$table = pluralize(substr($match,0,-3));
			$text = 'nombre_'.$language;
		}
		
		// Exceptions
		foreach ($exceptionsArray as $k=>$v) {
			$table = ($table==$xname.'_'.$k)?$xname.'_'.$v:$table;
			// printout($table.' - '.$k);
		}
		
		// Options array defines field to be shown
		foreach ($optionsArray as $k=>$v) {
			$text = ($table==$xname.'_'.$k)?$v:$text;
		}
		
		
		$query = "select id, {$text} from $table order by {$text} asc";
		 // printout( $query);
		if(!($q = dataset($query))) return '';
		
		$options = '';
		foreach($q as $r){
			$sel = ($match == $r['id']) ? "selected='selected'" : "";
			$options .= "<option value='{$r['id']}' $sel >{$r[$text]}</option>".PHP_EOL;
		}
		return $options;
	
	}
	
	// Pluralize function, 
	// If we have a fk (e.g: promotion_id) this function gets the correct table name (e.g: promotions)
	function pluralize($w){
		// if we have a table prefix, like dc_modelos 
		global $xname;
		$prefix = $xname."_";
		//
		$word = strtolower($w);
			$end1 = substr($word,-1); //last char
			$end2 = substr($word,-2); //last 2 chars
			$end3 = substr($word,-3); //last 3 chars
			//echo("word: ".$word." -> end1: ".$end1." - end2: ".$end2);
		$end = "s";
		// if last char is 'y' (e.g: property): chop of last and attach 'ies'
		if($end1 == 'y') { $word = substr($word,0,-1); $end = "ies"; };
		// if last chars are 'ion' (e.g: promocion): attach es
		if($end3 == 'ion') { $end = "es"; }; 
		if($end2 == 'in') { $end = "es"; }; 
		if($end3 == 'ial') { $end = "es"; }; 
		if($end3 == 'tor') { $end = "es"; }; 
		//
		if(in_array($end1,array('s', 'x'))) $end = "es";
		if(in_array($end2,array('ch','sh','ss',))) $end = "es";
		return $prefix.$word.$end;
	}
	

	
	// Chop spaces of a string and put them into an array
	function getPieces($list_data){
		$pieces = explode(" ",$list_data['table_fields']);
		// print_r($pieces);
		return $pieces;
		
	}
	
	// Get a section of a table
	function sections($lst,$starts){
		$startlist = explode(" ",$starts);
		foreach($lst as $word){
			if(in_array($word,$startlist)){
				$collection[]=$current_list;
				$current_list=array();
			}
			$current_list[]=$word;
		}
		$collection[]=$current_list;
		return $collection;
	}
	
	// Is valid email??
	
	function isValidEmail($email){
	return eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email);
}

// Files upload
function file_upload ($name,$table,$folder,$id) {
	if(!empty($_FILES[$name]['tmp_name'])){
		$tempfile = $_FILES[$name]['tmp_name'];
		$savefile = $_FILES[$name]['name'];
		$imgdir = "../images/{$folder}/{$id}";
		if(!file_exists($imgdir)) (mkdir($imgdir,0777));
		$copied = copy($_FILES[$name]['tmp_name'], "$imgdir/$savefile");
		$copied = (!$copied) ? '<h1>¡Fallo en subir archivo!</h1>' :'';
		// Update table
		$query = "UPDATE $table SET {$name} = '{$savefile}' WHERE id = {$id}";
		echo $query;
		mysql_query($query);
		echo $copied;
		//echo $imgDir;
	}
}
// Copies all files from one folder ($src) to another ($dst)
function copy_all($src,$dst) {
    $dir = opendir($src);
    @mkdir($dst);
    while(false !== ( $file = readdir($dir)) ) {
        if (( $file != '.' ) && ( $file != '..' )) {
            if ( is_dir($src . '/' . $file) ) {
                copy_all($src . '/' . $file,$dst . '/' . $file);
            }
            else {
                copy($src . '/' . $file,$dst . '/' . $file);
            }
        }
    }
    closedir($dir);
}


function getLink($table, $title, $icon = 'icon-tag', $id=null){
	global $xname, $language;
	
	$active = (!empty($_GET['table']) && $xname."_".$table==$_GET['table'])?'class="active"':'';
	
	$output = '<li '.$active.'>
		<a href="tipos.php?table='.$xname.'_'.$table.'&titulo='.$title.'&id='.$id.'&idioma='.$language.'" title="'.$title.'">
		<i class="'.$icon.'">&nbsp;</i>
		'.$title.'</a>
		</li>';
	return $output;
}


// Creates input with labels, ids and all that
function inputA($type,$clave,$label=true){
	global $value, $error;
	$check = (!empty($value[$clave]) && $value[$clave]==1)?'checked':'';
	// Label - none for hidden fields
	if ($type!='hidden') {
		$output 	=  '<div class="form-group">';
		if ($label==true) {
			$output 	.=  '<label for="'.$clave.'" class="col-md-2 control-label">'.show_label($clave).'</label>'."\n";
		}
	}

	$thisValue = (!empty($value[$clave])) ? $value[$clave] : '';
	$thisValue = ($type=='checkbox') ? 1 : $thisValue;
	
	$output .=  '<div class="col-md-10" ><input type="'.$type.'" class="form-control input-xlarge" id="'.$clave.'" name="'.$clave.'" '.$check.'  value="'.$thisValue.'" /></div>'."\n";
	$output 	.=  '</div>';
	return $output;
	
}

// Creates select pulldown
function selectA($clave,$array,$label=true){
	// Globals are defined in array in controller
	global $value,$first;
	$output 	=  '<div class="form-group">'."\n";
	// Labels are optional
	if ($label){
		$output .=  '<label for="'.$clave.'" class="col-md-2 control-label">'.show_label($clave).'</label>'."\n";
	}
	$output .=  '<div class="col-md-10"><select name="'.$clave.'" id="'.$clave.'" class="form-control input-xlarge  select2me" >'."\n";
	// First value?
	// print_r($first);
	if ($first[$clave]!='') { $output .=  '	<option value="">'.$firstText.'</option>'."\n"; }
	
	// Options come form $array
	$n = 0;
	foreach ($array as $k=>$v) {
		$selected 	= (!empty($value[$clave]) && $value[$clave]==$v['id'])?'selected':'';
		$output 	.=  '	<option value="'.$v['id'].'" '.$selected.'>'.$v['nombre'].'</option>'."\n";
		$n++;
	}
	$output 	.=  '</select></div>';
	$output 	.=  '</div>';
	return $output;
}

// Creates textarea with labels, ids and all that
function textareaA($clave, $noedit=false, $l=null){
	global $value, $error;
	
	$output 		=  '<div class="form-group '.$l.'">'."\n";
	$output 		.= '<label for="'.$clave.'" class="col-md-2 control-label">'.show_label($clave).'</label>'."\n";
	
	$edit			= ($noedit==TRUE)?'noedit':'ckeditor';
	
	$output			.= '<div class="col-md-10"><textarea id="'.$clave.'"  class="form-control input-xlarge '.$edit.'" name="'.$clave.'" >'.$value[$clave].'</textarea></div></div>'."\n";
	
	return $output;
}

// Checks webadmin user
function getAdmin ($id){
	global $xname;
	$query = "SELECT username FROM {$xname}_admins WHERE id = {$id}";
	$sql = record($query);
	$rol = $sql['username'];
	return $rol;
}

// Checks webadmin user role
function getRole ($id){
	global $xname;
	$query = "SELECT rol FROM {$xname}_admins WHERE id = {$id}";
	$sql = record($query);
	$rol = $sql['rol'];
	return $rol;
}

// Exits if table is in admins array and user is not admin
function checkUser($rol, $table){
	global $adminTables;
	
	if ($rol!=='admin' && in_array($table, $adminTables)) {
		exit;
	}
}

// Get image path if local, or full path if remote
function getImagePath($image, $folder, $parent_id, $size = 's'){
	global $base_site;
	$base_site = str_replace('webadmin2/','',$base_site);
	if (mb_substr($image, 0, 4) == 'http') {
		return $image;
	} 
	
	$path = $base_site . 'images/' . $folder . '/' . $parent_id . '/' . $size . '_' . $image;
	return $path;
	
}

// End file