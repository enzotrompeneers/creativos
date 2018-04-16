<?php
// Last updated 24/12/2014
// Added template

// Includes
set_include_path('../');
include("../lib/admin.php"); 
require_once("webadmin2/inc/helpers.php");
error_reporting(E_ALL ^ E_DEPRECATED);
// Config & action files
require_once('webadmin2/config/config.php');
$query = "SELECT * FROM {$xname}_traducciones WHERE used = 1 ORDER BY clave";
$sql = dataset($query);
$aTraducciones = array();

foreach ($sql as $k=>$v) {
	$flags = '';
	foreach ($languages as $l){
		// Flags
		$noTranslation = ($v[$l]=='') ? 'no_translation no-'.$l : '';
		$flags .= ' <img src="images/flags/'.$l.'.gif" alt="'.$l.'" class="flag '.$noTranslation.'"/>';
	}	
	$aTraducciones[$v['clave']] = '<strong>'. $v[$language].'</strong> ('.$v['clave'].')'.$flags;
}



?><!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en" class="no-js">
<!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
<?php include('webadmin2/inc/meta.php') ?>
<link rel="stylesheet" type="text/css" href="plugins/select2/select2.css" />
<link rel="stylesheet" type="text/css" href="plugins/bootstrap-toastr/toastr.min.css" />
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<!-- DOC: Apply "page-header-fixed-mobile" and "page-footer-fixed-mobile" class to body element to force fixed header or footer in mobile devices -->
<!-- DOC: Apply "page-sidebar-closed" class to the body and "page-sidebar-menu-closed" class to the sidebar menu element to hide the sidebar by default -->
<!-- DOC: Apply "page-sidebar-hide" class to the body to make the sidebar completely hidden on toggle -->
<!-- DOC: Apply "page-sidebar-closed-hide-logo" class to the body element to make the logo hidden on sidebar toggle -->
<!-- DOC: Apply "page-sidebar-hide" class to body element to completely hide the sidebar on sidebar toggle -->
<!-- DOC: Apply "page-sidebar-fixed" class to have fixed sidebar -->
<!-- DOC: Apply "page-footer-fixed" class to the body element to have fixed footer -->
<!-- DOC: Apply "page-sidebar-reversed" class to put the sidebar on the right side -->
<!-- DOC: Apply "page-full-width" class to the body element to have full width page without the sidebar menu -->
<body class="page-header-fixed page-quick-sidebar-over-content page-style-square"> 
<?php include('webadmin2/inc/header.php'); ?>
<!-- BEGIN CONTAINER -->
<div class="page-container">
	<?php include('webadmin2/inc/sidebar.php'); ?>
	<!-- BEGIN CONTENT -->
	<div class="page-content-wrapper">
		<div class="page-content">
		<?php include('webadmin2/inc/modals.php'); ?>
			<!-- BEGIN PAGE HEADER-->
			<h3 class="page-title">
			<?=webConfig('nombre');?> - <small>WebAdmin</small>
			</h3>
			<!-- END PAGE HEADER-->
			<!-- BEGIN MAIN PAGE CONTENT-->
			<div class="row">
				<div class="col-md-12 col-sm-12">	
					
				</div>
			</div>
					<div class="row" id="clave">
						<div class="col-md-5 col-sm-5">
						<h2><?=show_label('traducciones');?></h2>
							
								<div class="form-group">
									<input class="search form-control" placeholder="<?=show_label('buscar')?>" />
								</div>
								<div class="form-group">
								<?=show_label('filtro')?>: 
									<?php foreach ($languages as $l) : ?>
									<a href="#" id="filter-<?= $l ?>" class="languageFilter"><img src="images/flags/<?= $l ?>.gif" alt="Filter <?= $l ?>"/></a>
									<?php endforeach ?>
									&nbsp;<a href="#" id="allTrads"><?=show_label('todos')?></a>
								</div>
								<ul class="list-group lista-traducciones list">
									<?php foreach ($aTraducciones as $k=>$v) : ?>
									<li class="list-group-item tradEntry"><a href="#" id="clave_<?= $k ?>" class="item"><?= $v ?></a></li>
									<?php endforeach ?>
								</ul>
								<form action="?" id="clave">
									<div class="form-group">
										<input type="text" name="new" id="new" class="form-control" />
									</div>
									<div class="form-group">
										<input type="submit" name="add" id="add" value="<?=show_label("agregar_clave")?>" class="btn green btn-block" />
									</div>
								</form>
								
								<?php if ($_SESSION['be']) : ?>
								
								<div class="portlet blue-madison box">
									<div class="portlet-title">
										<div class="caption">
											<i class="fa fa-cogs"></i>&nbsp;&nbsp;<?= show_label('exportar_traducciones'); ?>
										</div>
										<div class="tools">
											<a href="javascript:;" class="expand"></a>
										</div>
									</div>
									<div class="portlet-body display-hide">
											<p><a href="trad_doit.php?act=cvs" class="cvs"><i class="fa fa-file-excel-o"></i>&nbsp;&nbsp;<?= show_label('exportar'); ?></a></p>
										<br clear="all" />
									</div>
								</div>
								

								
								<div class="portlet red box">
									<div class="portlet-title">
										<div class="caption">
											<i class="fa fa-cogs"></i>&nbsp;&nbsp;<?= show_label('importar_traducciones'); ?>
										</div>
										<div class="tools">
											<a href="javascript:;" class="expand"></a>
										</div>
									</div>
									<div class="portlet-body display-hide">
										<form action="trad_upload_csv.php" method="post" enctype="multipart/form-data">
											<div class="form-group">
												<select name="lang_csv" id="" class="form-control input-small  select2me" >
													<?php foreach ($languages as $l) : ?>
														<option value="<?= $l ?>"><?= strtoupper($l) ?></option>
													<?php endforeach ?>
												</select>
											</div>
											<div class="form-group">
												<p><input type="file" id="file_name" name="file_name" value="Choose file" /></p>
											</div>
											<div class="form-group">
												<p><input type="submit" value="Importar CVS" id="cvsUpload" class="cvs btn green" disabled /></p>
											</div>
										</form>
									</div>
								</div>
								<?php endif ?>
								
							
						</div>
						<div class="col-md-7 col-sm-7">
							<input type="hidden" id="guardado" value="<?=show_label('guardado')?>" />
							<form action="trad_doit.php?act=edit" id="traducciones" method="post" class="form-horizontal">
							</form>
						</div>
						
						
						
					</div>			
			<!-- END MAIN PAGE CONTENT-->
		</div>
	</div>
	<!-- END CONTENT -->
</div>
<!-- END CONTAINER -->
<!-- BEGIN FOOTER -->
<div class="page-footer">
	<div class="page-footer-inner">
		 <?=date('Y')?> &copy; <a href="http://www.bewebdesign.es/">Be Webdesign</a>.
	</div>
	<div class="scroll-to-top">
		<i class="icon-arrow-up"></i>
	</div>
</div>
<!-- END FOOTER -->
<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
<!-- BEGIN CORE PLUGINS -->
<!--[if lt IE 9]>
<script src="../../assets/global/plugins/respond.min.js"></script>
<script src="../../assets/global/plugins/excanvas.min.js"></script> 
<![endif]-->
<script src="plugins/jquery.min.js" type="text/javascript"></script>
<script src="plugins/jquery-migrate.min.js" type="text/javascript"></script>
<!-- IMPORTANT! Load jquery-ui-1.10.3.custom.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
<script src="plugins/jquery-ui/jquery-ui-1.10.3.custom.min.js" type="text/javascript"></script>
<script src="plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="plugins/jquery.blockui.min.js" type="text/javascript"></script>
<script src="plugins/jquery.cokie.min.js" type="text/javascript"></script>
<script src="plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
<script src="plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
<script src="plugins/ajaxform/jquery.form.js" type="text/javascript"></script>
<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script type="text/javascript" src="plugins/select2/select2.min.js"></script>
<script src="plugins/bootstrap-toastr/toastr.min.js"></script>
<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="js/metronic.js" type="text/javascript"></script>
<script src="js/layout.js" type="text/javascript"></script>
<script src="js/quick-sidebar.js" type="text/javascript"></script>
<script src="js/traducciones.js" type="text/javascript"></script>
<script src="js/list.min.js" type="text/javascript"></script>
<script type="text/javascript" src="plugins/jquery-minicolors/jquery.minicolors.min.js"></script>
<script type="text/javascript" src="../js/libs/select_search.js"></script>
<script type="text/javascript">
$(document).ready(function() { 
	// List filter
	var options = {
    valueNames: [ 'item' ]
};

var claveList = new List('clave', options);
}); 
</script>
<!-- END PAGE LEVEL SCRIPTS -->
<script>
jQuery(document).ready(function() {    
   Metronic.init(); // init metronic core componets
   Layout.init(); // init layout
});
</script>
<style>
<? 
foreach ($languages as $k => $v) { ?>
.<?=$v?> { background:url(img/flags/<?=$v?>.png) top right no-repeat; border:1px solid #ccc; width:500px;}  
div.<?=$v?> { background:url(img/flags/<?=$v?>.png) top right no-repeat; border:none; width:100%;}  
<? } ?>
</style>
<script language="javascript" type="text/javascript"> 
$(document).ready(function(){


/*** CLICK ***/
<?php
foreach ($languages as $k => $v) { 
		if ($k!=0) { echo '$(".'.$v.'").css("display","none");';}
	}
?>
	
<?php foreach ($languages as $k => $v) { ?>
		$('.click_<?=$v?>').click(function() {
			$('.<?=$v?>').show();
			<?php foreach ($languages as $x => $y) { 
				if ($v!=$y) { echo "$('.$y').hide();";  }
			 } ?>
		});
<?php } ?>
$('#clave_saltar').change(function() {
	var $this_page = location.href;
	$this_page = $this_page.split('?');
	$this_page = $this_page[0];
	 window.location = $this_page+'?clave='+$(this).val();
});

<?php if (req('act')=='add') { ?>
	$('#articulos #es').focus();
<?php } ?>

});
</script>
<script>
$(function() {
	$('#tabs').tabs();
});
</script>
<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>