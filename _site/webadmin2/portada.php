<?php
// Last updated 24/12/2014
// Added template

// Includes
set_include_path('../');
include("../lib/admin.php"); 
require_once("webadmin2/inc/helpers.php");

// Config & action files
require_once('webadmin2/inc/config.php');

if ($_POST){
	$clasicaPost = req('clasicas');
	$modernaPost = req('modernas');
	
	$selectionUpdate = "UPDATE {$xname}_portada SET clasica_id = {$clasicaPost}, moderna_id = {$modernaPost} WHERE id = 1 ";
	mysql_query($selectionUpdate) or die(mysql_error());
}


$modernasQuery = "SELECT id, CONCAT (nombre, ' (', referencia ,')' ) AS nombre FROM {$xname}_viviendas WHERE estilo_id = 1 AND visible = 1 ORDER BY referencia";
$modernas = dataset($modernasQuery);
$clasicasQuery = "SELECT id, CONCAT (nombre, ' (', referencia ,')' ) AS nombre FROM {$xname}_viviendas WHERE estilo_id = 2 AND visible = 1 ORDER BY referencia";
$clasicas = dataset($clasicasQuery);


$thisSelectionQuery = "SELECT * FROM {$xname}_portada WHERE id = 1";
$thisSelection = record($thisSelectionQuery);
$value['clasicas'] = $thisSelection['clasica_id'];
$value['modernas'] = $thisSelection['moderna_id'];

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
<link rel="stylesheet" type="text/css" href="plugins/uploadify/uploadify.css" />
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
				<div class="col-md-6 col-sm-6">	
					<h2><?=trad('portada')?></h2>
					<br/>
					<form action="" method="post">
						<?=selectA('clasicas',$clasicas);?><br clear="all" /><br clear="all" />
						<?=selectA('modernas',$modernas);?>
						<br clear="all" />
						<label for=""></label>
						<input type="submit" class="btn blue btn-block" name="submit" value="<?=show_label("guardar")?>" />
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
<script src="plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
<script src="plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<script src="plugins/jquery.blockui.min.js" type="text/javascript"></script>
<script src="plugins/jquery.cokie.min.js" type="text/javascript"></script>
<script src="plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
<script src="plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
<script src="plugins/ajaxform/jquery.form.js" type="text/javascript"></script>
<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script type="text/javascript" src="plugins/select2/select2.min.js"></script>
<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="js/metronic.js" type="text/javascript"></script>
<script src="js/layout.js" type="text/javascript"></script>
<script src="js/quick-sidebar.js" type="text/javascript"></script>

<!-- END PAGE LEVEL SCRIPTS -->
<script>
jQuery(document).ready(function() {    
   Metronic.init(); // init metronic core componets
   Layout.init(); // init layout
    var options = { 
	
	success: function(e){
		$('#tr_'+e).fadeOut('slow');
		
	}
	
	}
   $('.entry').ajaxForm(options);
   
});
</script>

<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>