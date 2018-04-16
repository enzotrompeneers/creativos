<?php
// Includes
set_include_path('../../../');
include("../../../lib/admin.php"); 
$mailing = true;
include("../../../webadmin2/inc/helpers.php"); 
include("../../../webadmin2/mods/mailing/mailing_helpers.php"); 
// error_reporting(E_ALL);
?>
	<form action="mods/mailing/mailing_search.php" method="get" id="searchForm">
		<?=selectA('tipoventa',getData('classes'));?>
		<?=selectA('tipovivienda',getData('tipos'));?>
		<?=selectA('localizacion',getData('localizaciones'));?>
		<?=selectA('dormitorios',getRange(1,10));?>
		<?=selectA('banos',getRange(1,10));?>
		<?=inputA('number','precio_desde');?>
		<?=inputA('number','precio_hasta');?>
		<?=selectA('piscina',getData('piscinas'));?>
		<?=selectA('aparcamiento',getData('aparcamientos'));?>
		<?=selectA('vistas',getData('vistas'));?>
		<?=selectA('jardines',getData('jardines'));?>
		<?=selectA('orientaciones',getData('orientaciones'));?>
		<?=selectA('airco',getYesNo());?>
		<?=selectA('terraza',getYesNo());?>
		<?=inputA('text','text');?>
		<input type="submit" value="search" class="form-control input-xlarge btn blue" />
	</form>
<div id="formRight"></div>
<script type="text/javascript">
$(function(){
	options = { target:        '#formRight'	}
	$('#searchForm').ajaxForm(options);
	$('.anadir').live('click',function(e){
		$id = $(this).attr('id').substr(1);
		$lang = $('#lang').val();
		$.post('mods/mailing/mailing_getVivienda.php',{'id':$id, 'lang':$lang}, function(data){
			if (data) {
				$('#viviendas').append(data);
				// $viviendasContent = $('#viviendas').html();
				// $('#viviendasInput').val($viviendasContent); 
				$('#datos').append('<input type="hidden" name="vivienda[]" id="f'+$id+'" value="'+$id+'" />');
				
			}
		});
	$(this).css('background','#ccc');
	e.preventDefault();
	})
});
</script>