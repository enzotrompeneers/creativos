<?php
set_include_path('../');
include("../lib/admin.php"); 
require_once("inc/helpers.php");
//error_reporting(E_ALL); ini_set('display_errors', '1');
$table = $_GET['table'];
$title = $_GET['titulo'];
initListing($table);
$query = "SELECT * FROM $table ORDER BY orden ASC";
$records = dataset($query);

$list_data = record("SELECT table_fields FROM {$xname}_list_data WHERE table_name='$table'");
$arrPieces = getPieces($list_data);

?>
<style type="text/css">
table tr { cursor:pointer;margin:4px 0; }
</style>
<script type="text/javascript">
$(document).ready(function() {
	$("#myTable tbody").sortable({ opacity: 0.8, cursor: 'move', update: function() {
			
			var order = $(this).sortable("serialize") + '&update=update&table=' + $table.attr('value'); 
			$.post("orderUpdate.php", order, function(theResponse){
				$("#response").html(theResponse);
				$("#response").slideDown('slow');
				//slideout();
			}); 															 
		}								  
	}).disableSelection();
});
</script>
<div id="orden">
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
	<h4 class="modal-title"><?=show_label('cambiar_orden');?> <?=$title?></h4>
</div>
<div class="modal-body">
<table id="myTable" class="tablesorter table table-bordered table-striped  table-condensed table-hover" style="width:90%;" > 
    <thead> 
    <tr>
        <th>Id</th> 
        <?php
        // We compare the fields from the cs_list_data table and the meta fields, and only show the intersection                           
        $intersec = array_intersect($arrPieces, $arrMetaFields);
        foreach($intersec as $th){ 
		   $labelname = str_replace("_id","",$th);
		   echo("<th>".humanize($labelname)."</th>"); 
		
		}
        ?>       
    </tr> 
    </thead> 
    <tbody> 
    <?php
      if(!empty($records)){ 
        foreach($records as $k => $v){
		$id = stripslashes($v['id']);
    ?>
        <tr id="arrayorder_<?php echo $id ?>"> 
            <td><?= $v['id']; ?></td> 
            <?php
	   	   // We compare the fields from the cs_list_data table and the meta fields, and only show the intersection                              
	   $intersect = array_intersect($arrPieces, $arrMetaFields);
	   foreach($intersect as $mf){  
		   $val = $v[$mf];
		   
		   // Check if table ends in _id, if so, get its value from the parent table
		   if(preg_match("/_id$/",$mf)) {
			   $parent = pluralize(substr($mf,0,-3));
			   
			   $parent = ($mf=='localidad_id')?$xname.'_localidades':$parent;
			   // echo $parent;
			   $idQuery = "SELECT * FROM ".$parent." WHERE id = ".$val;
			   //echo $idQuery;
			   $res = record($idQuery);
			   $val = $res['nombre_es'];
			   $val = ($parent==$xname.'_fichas')?$res['titulo_es']:$val;
			   $val = ($parent==$xname.'_promociones')?$res['nombre']:$val;
			   //echo $val;
		   }
		   
		   // Check if value is a 1 or 0, to be replaced by an image 
		   if(in_array($mf,$symbolArray)){
			   switch($val){
				   case "1":
							  echo('<td><center><i class="glyphicon glyphicon-ok"></i> </center></td>'); 
							  break;
				   case "0":
							  echo('<td><center><i class="glyphicon glyphicon-remove" ></i> </center></td>'); 
							  break;
				   default:
							  echo("<td>".$val."</td>"); 
				}
		    } else {
			echo("<td>".$val."</td>"); 
		   }
	   }
	   
		?>

        </tr> 
    <?php } }?>
    </tbody> 
    </table>
	</div>
	<div class="modal-footer">
		<button class="btn default" data-dismiss="modal" type="button"><?=show_label('cerrar');?></button>
	</div>
</div>