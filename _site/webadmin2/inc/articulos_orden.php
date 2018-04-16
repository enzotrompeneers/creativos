<?php
set_include_path('../../');
include("../../lib/admin.php"); 
require_once("helpers.php");
// error_reporting(E_ALL); ini_set('display_errors', '1');
$table = $xname.'_articulos';
// $table = $_GET['table'];
initListing($table);
$records = dataset("SELECT * FROM $table ORDER BY orden ASC");
$list_data = record("SELECT table_fields FROM {$xname}_list_data WHERE table_name='$table'");
$list_data['table_fields'] = 'clave titulo_'.$languages[0].' titulo_'.$languages[1].'';
$arrPieces = getPieces($list_data);

// Select array for list
$cambiarQuery = "
				SELECT *, titulo_{$language} AS titulo
				FROM {$xname}_articulos
				WHERE parent_id < 1
				ORDER BY orden,id
				";
$cambiarSql = dataset($cambiarQuery);
$n = 1;
$lista = '';
foreach ($cambiarSql as $k=>$v) {

	$lista .= '<li id="list_'.$v['id'].'" class=" dd-item"><div class="dd-handle" dd3-handle><strong>'.$v['titulo'].'</strong> ('.$v['clave'].')</div>';
	$parentQuery = "SELECT id,parent_id, clave, titulo_{$language} AS titulo FROM {$xname}_articulos WHERE parent_id = {$v['id']}";
	$parentSql = dataset($parentQuery);
	if ($parentSql) {
		$lista .= '<ol>';
		foreach ($parentSql as $x=>$y) {
			$lista .= '<li class="sub dd-item" id="list_'.$y['id'].'"><div class="dd-handle"><strong>'.$y['titulo'].'</strong> ('.$y['clave'].')</div></li>';
			$n++;
		}
		$lista .= '</ol></li>';
	} else {
		$lista .= '</li>';
			$n++;
	}

}

?>
<script type="text/javascript">
$(document).ready(function() {
	$('ol#sortable').nestedSortable({
            handle: 'div',
            items: 'li',
            toleranceElement: '> div',
			forcePlaceholderSize: true,
			opacity: .6,
			update: function(){
				// var order = $(this).sortable("serialize")+'&update=update&table=' + $(this).attr('rel');
				serialized = $('ol#sortable').nestedSortable('serialize');
				// alert(serialized);
				$.post("inc/articulos_orderUpdate.php", serialized, function(theResponse){
			}); 
		}
    });
});
</script>
<div id="orden">
	<h3><?=trad('cambiar_orden')?></h3>
	<ol id="sortable" rel="<?=$xname?>_articulos" class="dd-list">
		<?=$lista?>
	</ol>
</div>