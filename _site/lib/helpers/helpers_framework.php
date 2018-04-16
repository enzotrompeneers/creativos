<?php
/****** FRAMEWORK HELPERS *******/
// Includes view from clave
function load_view($clave){
	global $language,$user;
	$view = 'views/'.$clave.'.php';
	include($view);
}



// Creates input with labels, ids and all that
function input($type,$clave,$label=TRUE,$required=FALSE){
	global $value,$error;
	$output = '';
	if ($type!='submit'){
		// Required?
		$required = ($required==TRUE)?'required':'';
		// $asterisk = ($required==TRUE)?'<span class="asterisk">*</span>':'';
		$check = (!empty($value[$clave]) && $value[$clave]==1)?'checked':'';

		// Errors
		// if ($error[$clave]!='') { $errorMessage = '<em class="inputError">'.$error[$clave].'</em>'; $class='invalid';};
		$errorMessage = (!empty($error[$clave])) ?  '<em class="inputError">'.$error[$clave].'</em>':'';
		$class = ($errorMessage)?'invalid':'';
		
		// Label - none for hidden fields
		if ($type!='hidden' && $label==TRUE) {
			$output =  '<label for="'.$clave.'">'.trad($clave).'</label>'."\n";
		}
		// $thisValue = ($type=='checkbox')?1:$value[$clave];
		if ($type=='checkbox') { $value[$clave]=1; }
		// if (!empty($value[$clave])) $thisValue = $value[$clave];
		$thisValue = (!empty($value[$clave]))?$value[$clave]:'';
		
		$placeholder = ($label==FALSE)?'placeholder="'.trad($clave).'"':''; // Placeholder
		
		$output .=  '<input type="'.$type.'" id="'.$clave.'" value="'.$thisValue.'" '.$required.' class="'.$class.' form-control '.$required.'" name="'.$clave.'" '.$check.' '.$placeholder.' /> '.$errorMessage."\n";
		
		return $output;
	} else {
		// Submit
		return '<label for=""></label><input type="submit" id="'.$clave.'" value="'.trad($clave).'" class="button right form-control" style="margin-right:15px;" name="'.$clave.'" />';
	}
}

// Creates select pulldown
function select($clave,$array,$label=FALSE,$required=FALSE,$small=FALSE){
	// Globals are defined in array in controller
	global $value,$class,$first,$pagina;
	
	$array = (is_array($array))?$array:array(array('id' => '', 'nombre' => trad($clave)));
	// printout($array );
	$output = '';
	if ($clave != 'genero')
		{$firstText = ($first[$clave]!='')?$first[$clave]:trad('elije'); }
	$size = ($small==TRUE)?' small':''; // Is small?
	// Labels are optional
	if ($label==TRUE) { 
		$output .=  '<label for="'.$clave.'" class="'.$size.'">'.trad($clave).'</label>'."\n"; 
		} 
	
	// Required?
	$required = ($required==TRUE)?' required':'';
	// $asterisk = ($required==TRUE)?'<span class="asterisk">*</span>':'';
	
	if ($pagina=='reventas') { $value['tipoventa'] = 2; }
	if ($pagina=='obra_nueva') { $value['tipoventa'] = 1; }

	
	$output .=  '<select name="'.$clave.'" id="'.$clave.'" '.$required.' class="main-selectbox '.$size.'" >'."\n";
	// First value?
	// print_r($first);
	if ($first[$clave]!='') { $output .=  '	<option value="">'.$firstText.'</option>'."\n"; }
	
	// Options come form $array
	$n = 0;
	foreach ($array as $k=>$v) {
		$selected = (!empty($value[$clave]) && $value[$clave]==$v['id'])?'selected':'';
		$output .=  '	<option value="'.$v['id'].'" '.$selected.'>'.$v['nombre'].'</option>'."\n";
		$n++;
	}
	$output .=  '</select>';
	return $output;
}

// Creates textarea with labels, ids and all that
function textarea($clave,$label=TRUE,$required=FALSE){
	global $value,$error;
	// Required?
	$required = ($required==TRUE)?'required':'';
	// $asterisk = ($required==TRUE)?'<span class="asterisk">*</span>':'';
	// Errors
	if ($error[$clave]!='') { $errorMessage = '<em class="inputError">'.$error[$clave].'</em>'; $class='invalid';} else { $errorMessage = ''; $class = ''; };
	if ($label==TRUE){
	$output = '<label for="'.$clave.'" style="float:left;clear:left;">'.trad($clave).$errorMessage.'</label><br clear="all" />'."\n";
	}
	$placeholder = ($label==FALSE)?' placeholder="'.trad($clave).'"':'';
	$output .= '<textarea id="'.$clave.'" class="'.$required.' '.$class.' form-control" name="'.$clave.'" '.$placeholder.'>'.$value[$clave].'</textarea>'."\n";
	
	return $output;
}

// Gets app link
function app($controller,$clave){
	global $language,$xname;
	$link	= $language.'/app/'.$controller.'/';
	$clave	= ($clave!='')?$clave.'/':'';
	$link 	.= $clave;
	return $link;
}

// Get config details
function webConfig($clave){
	global $language,$xname;
	$query = "SELECT valor FROM ".$xname."_config WHERE clave = '$clave'";
	//	echo $query;
	$sql = mysql_query($query);
	
	$row=mysql_fetch_row($sql);
	
	if(!$row) {
		return ("*!!*".$clave);
	}
	if($row[0]=="") {
		return("*!*".$clave);
	} else {
		return ($row[0]);
	}
	
}
// Gets first select trad
function first_select($clave) {
	$output = array (
		'id'		=>	'',
		'nombre'	=>	trad($clave)
	);
	return $output;
}


// Collects data for select pulldown
function get_select_data($clave,$default=null, $campo=null){
	global $language,$xname,$singularArray;
	$claveSingular = rtrim($clave,'s');
	$campo = ($campo==null)?'nombre_'.$language:$campo;
	
	// Exceptions
	if (!empty($singularArray)) {
		foreach ($singularArray as $k=>$v) {
			$claveSingular = ($clave==$k)?$v:$claveSingular;
			// printout($table.' - '.$k);
		}
	}
	
	// Database query
	$query = "
			SELECT c.id AS id, c.{$campo} AS nombre
			FROM {$xname}_{$clave} c
			WHERE c.id IN (SELECT DISTINCT {$claveSingular}_id FROM {$xname}_viviendas)
			ORDER BY c.{$campo}
			";
	$sql = dataset($query);
	$output = $sql;
	// printout($query);
	 
	 // First choice
	if ($default!=NULL && is_array($output)) {
		$label = array('id'=>'','nombre'=>$default); // Primera opción
		array_unshift($output,$label);
	}
	
	return $output;
}

// Returns a range of numbers for select pulldown
function get_select_range($range='1-6',$step=1,$default=NULL) {
	$minMax = explode('-',$range);
	$min = $minMax[0];
	$max = $minMax[1];
	
	$output = array();
	for($n=$min;$n<=$max;$n+=$step){
		$output[$n]['id'] = $n;
		$output[$n]['nombre'] = $n;
		
	}
	if ($default!=NULL) {
		$label = array('id'=>'','nombre'=>$default); // Primera opción
		array_unshift($output,$label);
	}	
	// printout($aRange);
	return $output;
}

// Returns a range of numbers for select pulldown
function get_select_price_range($priceRange,$default=NULL) {
	$rangoArray  = explode(',', $priceRange);
	$output = array();
	$n = 0;
	foreach ($rangoArray as $r) {
	 $output[$n]['id'] = $r.'000';
	 $output[$n]['nombre'] = precio($r*1000).'&nbsp;&euro;';
	 $n++;
	}	 
	if ($default!=NULL) {
		$label = array('id'=>'','nombre'=>$default); // Primera opción
		array_unshift($output,$label);
	}		
	return $output;
}

// End file