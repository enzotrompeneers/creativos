<?php
// Last updated 12/07/2012


set_include_path('../');
include("../lib/admin.php"); 
error_reporting(4); 
require_once("inc/helpers.php");
require_once("webadmin2/config/config.php");


// Get variables
$table = req('table');
$field = req('field');
$title = req('title');

$table = ($table==$xname.'_localidads')?$xname.'_localidades':$table;

$texts = array();
$bools = array();
$ignores = array();
$hidden = array('id');
$starts = array(
	'tipo_vivienda_id' => array(),
);


// Check if table is in optionsArray to override default
$aTable = explode('_',$table);
$tableNoPrefix = $aTable[1];
$fieldName = (array_key_exists($tableNoPrefix,$optionsArray))?$optionsArray[$tableNoPrefix]:$optionsArray['default'];

// Form
if (!$_POST) {
?>
<script src="../js/libs/jquery.form.js" type="text/javascript"></script>
<script type="text/javascript">	
$(document).ready(function(){
	$('#add_fields').ajaxForm({
	target: '#field'
	});
});
</script>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
	<h4 class="modal-title"><?=$title?></h4>
</div>
<div id="field" class="modal-body">
	<form action="add_field.php" id="add_fields" method="post" class="form-horizontal" style="padding:12px;">
		<fieldset>
			<input type="hidden" name="table" value="<?=$table?>" />
			<input type="hidden" name="field" value="<?=$field?>" />
			<?php
			$addform = form_better($table, $r, $texts, $bools, $ignores, $starts);
			echo $addform;
			?>
			<div class="modal-footer">
				<button class="btn default" data-dismiss="modal" type="button"><?=show_label('cerrar');?></button>
				<input class="btn blue" type="submit" value="<?=show_label('guardar');?>">
			</div>
		</fieldset>
	</form>
</div>
<?php
} else { 
	// Process post as in tipos.php
	$names = array('action','submit','id','imagen','table','field');
	foreach($_POST as $k => $v){
		if(in_array($k,$names)) continue;
		$v = mysql_real_escape_string($v);
		$last_id = compare_save($last_id, $table, $k, $v);
	}
	// $query = "INSERT INTO $table ($k) VALUES ('$v')";
	// echo $query;
	mysql_query($query);
	$newId = select_max("id",$table);
	$name = $_POST['nombre_es'];
?>
<script type="text/javascript">
$(document).ready(function(){
	$('#<?=$field?>').append('<option value="<?=$newId?>"><?=req($fieldName)?></option>');
	$("#<?=$field?>").select2("val", '<?=$newId?>');
	$('.close').trigger('click');
});
</script>
<?php
 } 
 
 // End file