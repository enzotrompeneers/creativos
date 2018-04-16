<?php
// Last updated 24/12/2014
// Added template

// Includes
set_include_path('../');
include("../lib/admin.php"); 

require_once("webadmin2/inc/helpers.php");

// Config & action files
require_once('webadmin2/config/config.php');
require_once('webadmin2/inc/tipos_actions.php'); // Actions

require 'vendor/autoload.php'; // Composer autoload

// $rol = getRole($_SESSION['Admin']);
// checkUser($rol, $folder); // Exit if user does not have credentials

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
<link rel="stylesheet" type="text/css" href="css/uploadifive.css" />
<link rel="stylesheet" type="text/css" href="plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css"/>
<link rel="stylesheet" type="text/css" href="plugins/jquery-nestable/jquery.nestable.css" />
<link rel="stylesheet" type="text/css" href="plugins/jquery-minicolors/jquery.minicolors.css" />
<link rel="stylesheet" href="../js/libs/css/colorbox.css" type="text/css" />
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
				<!-- BEGIN TIPOS FORM -->
				<form action="" method="post" class="form-horizontal" role="form" enctype="multipart/form-data">
					<fieldset>
					<input type="hidden" class="hidden" name="id" id="formId" value="<?= $id; ?>" />
					<input type="hidden" class="hidden" name="table" id="table" value="<?= $table; ?>" />
					<input type="hidden" class="hidden" name="action" value="<?= $action; ?>" />
					<div class="form-buttons">
						<?php if ($id) : ?>
						<a class="btn green" href="?table=<?=$table;?>&titulo=<?=$tableName?>"><?=show_label("add_to")?>  <strong><?=$tableName ?></strong></a>
						<a class="btn yellow" href="?act=clonar&id=<?=$id?>&table=<?=$table;?>&titulo=<?=$tableName?>"><?=show_label("clonar")?></a>
						<?php endif ?>		
						<?php if ($ordenExists) : ?>
						<a class="btn bg-yellow-crusta" href="orden.php?table=<?=$table;?>&titulo=<?=$tableName?>" data-toggle="modal" data-target="#ajax"><?=show_label("cambiar_orden")?></a>
						<?php endif ?>
						<input type="submit" class="btn blue" name="submit" value="<?=show_label("guardar")?>" />
					</div>
						<div class="portlet box red tipos">
							<div class="portlet-title">
								<div class="caption">
									<i class="icon-note"></i>
									<?=$tableName ?>
									<?= $reference; ?>
								</div>
								<div class="tools">
									<a class="collapse" href="javascript:;" data-original-title="" title=""></a>
								</div>
							</div>
							<div class="portlet-body">
								<?php if (is_array($starts)) : ?>
								<div class="tabbable-custom">
									<ul class="nav nav-tabs">
									<?php $n=1; ?>
									<?php foreach ($starts as $k=>$v) : ?>
										<li <?php if ($n==1) echo 'class="active"'; ?>><a href="#tab_<?=$k?>" data-toggle="tab"  aria-expanded="true"><?=$v?></a></li>
									<?php $n++; ?>
									<?php endforeach ?>
									</ul>
									<div class="tab-content">
									<?= $addform ?>
											
											
									<?php if ($folder == 'viviendas') : ?>
									
									<div id="tab_opciones" class="tab-pane">
									
										<div id="opciones">
										
											
											
											<div v-for="(opcion, index) in opcionesTotales" class="form-group">
												<label for="" class="control-label col-md-2 ">{{ opcion.nombre_<?= $language ?> }} </label>
												<div class="col-md-10">
													<div class="radio-list">
														<label @click.prevent="addOption(opcion.id)" class="radio-inline">
															<div class="radio">
															<span :class="{ checked: hasOption(opcion.id) }">
																<input type="radio"  value="1" :id="'opcion_' + index" :checked="hasOption(opcion.id)">
															</span>
															</div> <?= show_label('si') ?>
														</label>
														<label @click.prevent="removeOption(opcion.id)" class="radio-inline">
															<div class="radio" id="">
															<span :class="{ checked: !hasOption(opcion.id) }">
																<input type="radio" value="0" :id="'opcion_' + index" >
															</span>
															</div> <?= show_label('no') ?> 
														</label>
													</div>
												</div>
											</div>
											
										
											
										
										</div>
									
									</div>
									
									<div id="tab_extras" class="tab-pane">
									
										<div id="extras">
										
											<div v-for="(extra, index) in extrasTotales" class="form-group">
												<label for="" class="control-label col-md-2 ">{{ extra.nombre_<?= $language ?> }} </label>
												<div class="col-md-2">
													<div class="radio-list">
													
														<label @change="saveExtras()" class="input-icon right">
															<i class="fa fa-euro "></i>
															<input type="number" class="form-control " v-model="extra.value" :id="'extra_' + index" >
															
														</label>
		
													</div>
												</div>
											</div>
										
										</div>
									
									</div>
									
									<?php endif ?>
									
									
									<?php if (table_has_images($folder)) : ?>
									<div id="tab_upload_images" class="tab-pane">
										<div id="images_int" role="images">
											<input type="file" name="Imagedata" id="Imagedata" class="Imagedata" />
											<div id="gal" class="sort">
												<ul id="album" role="images"></ul>
												<div id="image-queue"></div>
												<div id="response"></div>
											</div>
										</div>
									</div><br clear="all" />
									<?php endif ?>
									<?php if (table_has_images($folder,'files')) : ?>
									<div id="tab_upload_files" class="tab-pane">
										<div id="images_int">
											<input type="file" name="Filedata" id="Filedata" class="Filedata" />
											<div id="files" class="sort" role="files">
												<div id="fileList"></div>
												<div id="file-queue"></div>
												<div id="response"></div>
												<br/>
												<br/>
												
											</div>
											<br/>
										</div>
									</div><br clear="all" />
									<?php endif ?>		
									</div>
								</div>
								<?php else : ?>
								<?= $addform ?>
								<?php endif ?>	
								<input type="submit" class="btn blue btn-block" name="submit" value="<?=show_label("guardar")?>" />
							</div>
						</div>						
					</fieldset>
				</form>
				<!-- END TIPOS FORM -->
				</div>
			</div>
			<div class="row">
				<div class="col-md-12 col-sm-12">
				<?php include("webadmin2/inc/tipos_listado.php"); // Listado ?>
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

<?php 
// error_reporting(E_ALL); 
use \Brunelencantado\TextTools\Concat;
$jsScripts 		= new Concat; 
$jsScripts->add('plugins/jquery.min.js');
// Core plugins
$jsScripts->add('plugins/jquery-migrate.min.js');
$jsScripts->add('plugins/jquery-ui/jquery-ui-1.10.3.custom.min.js');
$jsScripts->add('plugins/bootstrap/js/bootstrap.min.js');
$jsScripts->add('plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js');
$jsScripts->add('plugins/jquery-slimscroll/jquery.slimscroll.min.js');
$jsScripts->add('plugins/ajaxform/jquery.form.js');

// This page plugins
$jsScripts->add('plugins/bootstrap-daterangepicker/moment.min.js');

$jsScripts->add('plugins/select2/select2.min.js');
$jsScripts->add('plugins/datatables/media/js/jquery.dataTables.min.js');
$jsScripts->add('plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js');
$jsScripts->add('plugins/bootstrap-datepicker/js/bootstrap-datepicker.js');
$jsScripts->add('plugins/bootstrap-confirmation/bootstrap-confirmation.min.js');
$jsScripts->add('../js/libs/jquery.colorbox-min.js');
$jsScripts->add('js/metronic.js');
$jsScripts->add('js/layout.js');
$jsScripts->add('js/quick-sidebar.js');
$jsScripts->add('js/jquery.uploadifive.min.js');
$jsScripts->add('js/tipos.js');



?>
<script type="text/javascript" src="<?=  $jsScripts->concat(true) ?>"></script>
<?php if ($folder == 'viviendas') : ?>
	
	<script type="text/javascript" src="../js/vendor/vue.js"></script>
	<script type="text/javascript" src="../js/vendor/axios.js"></script>
	<script type="text/javascript" src="js/opciones.js"></script>
	
<?php endif ?>
<script type="text/javascript" src="plugins/jquery-minicolors/jquery.minicolors.min.js"></script>
<script type="text/javascript" src="plugins/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="plugins/ckeditor/adapters/jquery.js"></script>
<script>
jQuery(document).ready(function() {    
   Metronic.init(); // init metronic core componets
   Layout.init(); // init layout
   
});
</script>
<?=$mapScript?>
<?php include("webadmin2/inc/mapa.php"); // Mapa ?>
<script type="text/javascript">
<?php $timestamp = time();?>
	$(function() {
		$('#Imagedata').uploadifive({
			'auto'             : true,
			
			'formData'         : {
								   'timestamp' : '<?php echo $timestamp;?>',
								   'token'     : '<?php echo md5('unique_salt' . $timestamp);?>'
								 },
			'queueID'          : 'image-queue',
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
			
			'formData'         : {
								   'timestamp' : '<?php echo $timestamp;?>',
								   'token'     : '<?php echo md5('unique_salt' . $timestamp);?>'
								 },
			'queueID'          : 'file-queue',
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