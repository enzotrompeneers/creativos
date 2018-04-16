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


// error_reporting(E_ALL); 
$jsScripts 		= new \Brunelencantado\Jstools\Concat; 
$jsScripts->add('plugins/jquery.min.js');
// Core plugins
$jsScripts->add('plugins/jquery-migrate.min.js');
$jsScripts->add('plugins/jquery-ui/jquery-ui-1.10.3.custom.min.js');
$jsScripts->add('plugins/bootstrap/js/bootstrap.min.js');
$jsScripts->add('plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js');
$jsScripts->add('plugins/jquery-slimscroll/jquery.slimscroll.min.js');
$jsScripts->add('plugins/jquery.blockui.min.js');
$jsScripts->add('plugins/jquery.cokie.min.js');
$jsScripts->add('plugins/uniform/jquery.uniform.min.js');
$jsScripts->add('plugins/bootstrap-switch/js/bootstrap-switch.min.js');
$jsScripts->add('plugins/ajaxform/jquery.form.js');

// This page plugins
$jsScripts->add('plugins/bootstrap-daterangepicker/moment.min.js');

$jsScripts->add('plugins/select2/select2.min.js');
$jsScripts->add('plugins/uploadify/jquery.uploadify-3.1.min.js');
$jsScripts->add('plugins/datatables/media/js/jquery.dataTables.min.js');
$jsScripts->add('plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js');
$jsScripts->add('plugins/bootstrap-datepicker/js/bootstrap-datepicker.js');
$jsScripts->add('../js/libs/jquery.colorbox-min.js');
$jsScripts->add('js/metronic.js');
$jsScripts->add('js/layout.js');
$jsScripts->add('js/quick-sidebar.js');
$jsScripts->add('js/tipos.js');


echo $jsScripts->concat(true);
?>
<script type="text/javascript" src="plugins/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="plugins/ckeditor/adapters/jquery.js"></script>



<!-- END PAGE LEVEL SCRIPTS -->
<script>
jQuery(document).ready(function() {    
   Metronic.init(); // init metronic core componets
   Layout.init(); // init layout
});
</script>

<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>