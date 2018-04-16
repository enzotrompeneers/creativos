<?php
// Last updated 19/1/2011
// Created!

set_include_path('../');
include("../lib/admin.php"); 
require_once("webadmin2/inc/helpers.php");
require_once("webadmin2/config/config.php");

// Get clave data
if ($clave!='') {
	$query 		= "SELECT * FROM ".$xname."_traducciones WHERE clave = '".$clave."'";
	//echo $query;
	$trad 		= record($query);
	$value 		= $trad;
	// printout($trad);
}
echo '<meta charset="utf-8" />';
// Styles for flags
echo '<style type="text/css">';
	foreach ($languages as $k=>$v) {
		if ($k!=0) {echo "  #main fieldset textarea.{$v}{background:#fff url(\"../images/flags/{$v}.gif\") no-repeat scroll right top transparent; padding-right:20px; display:none;} \n"; }
	}
echo '#flags { margin:0; }';
echo '#submit { float:right; }';
echo '</style>'."\n";

// Language Select
echo '<script type="text/javascript">'."\n";
echo '$(document).ready(function() { ';

 foreach ($languages as $k => $v) { 
		echo "$('.click_{$v}').click(function() { \n
			$('.{$v}').show(); \n";
			
			 foreach ($languages as $x => $y) { 
				if ($v!=$y) { 
					echo "$('.{$y}').hide(); \n"; 
					// echo "alert('".$y."');"; 
				} }
echo '});'."\n";
				}
// echo "$('#".$language."').focus();";
//echo '$("input[type=\'text\']:first", document.forms[0]).focus();';
echo '});'."\n";

echo '</script>'."\n";

// Display the form
echo '<div class="form-group"><label for="" class="control-label col-md-2"> </label><div class="col-md-10"><h4>'.trad("clave").': <strong>'.$clave.'</strong></h4></div></div>';
echo '<div class="form-group"><label for="" class="control-label col-md-2"> </label><div class="col-md-10"><input type="submit" value="'.trad("guardar").'" class="btn input-xlarge blue" /></div></div>';
echo '<input type="hidden" name="clave" value="'.$clave.'" />';

foreach ($languages as $l){
	echo '<div class="form-group"><label for="'.$l.'" class="col-md-2 control-label">'.strtoupper($l).'</label>
<div class="col-md-10"><input class="form-control input-xlarge" id="'.$l.'" name="'.$l.'" value="'.$value[$l].'" type="text"></div>
</div>';

}

foreach ($languages as $l){
	//echo '<label for="'.$v.'">'.$v.'</label>';
	echo textareaA('art_'.$l,false);
}
echo '<div class="form-group"><label for="" class="control-label col-md-2"> </label><div class="col-md-10"><input type="submit" value="'.trad("guardar").'" class="btn input-xlarge blue" /></div></div>';


// End file
