<?php
// Last updated 24/12/2014
// Added template

// Includes
set_include_path('../');
include("../lib/admin.php"); 
require_once("webadmin2/inc/helpers.php");

// Config & action files
require_once('webadmin2/config/config.php');
require_once("webadmin2/inc/articulos_logic.php"); // Actions

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
<link rel="stylesheet" type="text/css" href="plugins/jquery-nestable/jquery.nestable.css" />
<link rel="stylesheet" type="text/css" href="css/uploadifive.css" />
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
					<h2><?=show_label('paginas');?></h2>
					<?php include("webadmin2/inc/articulos_form.php"); ?> 
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
<script src="plugins/bootstrap-daterangepicker/moment.min.js" type="text/javascript"></script>
<script src="plugins/bootstrap-daterangepicker/daterangepicker.js" type="text/javascript"></script>
<script src="plugins/ckeditor/ckeditor.js"></script>
<script src="plugins/ckeditor/adapters/jquery.js"></script>
<script type="text/javascript" src="plugins/select2/select2.min.js"></script>
<script type="text/javascript" src="js/jquery.uploadifive.min.js"></script>
<script src="plugins/jquery.mjs.nestedSortable.js"></script>
<script type="text/javascript" src="plugins/datatables/media/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js"></script>
<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script type="text/javascript" src="plugins/jquery-minicolors/jquery.minicolors.min.js"></script>
<script src="js/metronic.js" type="text/javascript"></script>
<script src="js/layout.js" type="text/javascript"></script>
<script src="js/quick-sidebar.js" type="text/javascript"></script>
<script src="js/tipos.js" type="text/javascript"></script>

<!-- END PAGE LEVEL SCRIPTS -->
<script>
jQuery(document).ready(function() {    
   Metronic.init(); // init metronic core componets
   Layout.init(); // init layout
});
</script>
<style>
<?php
foreach ($languages as $k => $v) { ?>
.<?=$v?> { background:url(img/flags/<?=$v?>.png) top right no-repeat; border:1px solid #ccc; width:500px;}  
div.<?=$v?> { background:url(img/flags/<?=$v?>.png) top right no-repeat; border:none; width:100%;}  
<?php } ?>
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
<?php $timestamp = time();?>
<script type="text/javascript">
$(document).ready(function() { 

		$('#Imagedata').uploadifive({
			'auto'             : true,
			'checkScript'      : 'check-exists.php',
			'formData'         : {
								   'timestamp' : '<?php echo $timestamp;?>',
								   'token'     : '<?php echo md5('unique_salt' . $timestamp);?>'
								 },
			'queueID'          : 'queue',
			'removeCompleted' : true,
			'uploadScript'     : 'uploadifive.php',
			'formData': {'id':$fkAlbum.attr('value'), 'table':$table.attr('value'), 'type':'image','<?php echo session_name();?>' : '<?php echo session_id();?>'},
			'onUploadComplete' : function(file) {
			$.get('dragdrop.php',{'id':$fkAlbum.attr('value'),'table':$table.attr('value')}, function(data){
				$('#album').html(data);
			});
			}
		});
		$('#Filedata').uploadifive({
			'auto'             : true,
			'checkScript'      : 'check-exists.php',
			'formData'         : {
								   'timestamp' : '<?php echo $timestamp;?>',
								   'token'     : '<?php echo md5('unique_salt' . $timestamp);?>'
								 },
			'queueID'          : 'fileQueue',
			'removeCompleted' : true,
			'uploadScript'     : 'uploadifive.php',
			'formData': {'id':$fkAlbum.attr('value'), 'table':$table.attr('value'), 'type':'file','<?php echo session_name();?>' : '<?php echo session_id();?>'},
			'onUploadComplete' : function(file) {
			$.get('dragdrop_files.php',{'id':$fkAlbum.attr('value'),'table':$table.attr('value')}, function(data){
				$('#fileList').html(data);
			});
			}
		});

	
	
}); 

</script>
<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>