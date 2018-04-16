<?php 
set_include_path('../');
include("../lib/admin.php"); 
// Make sure session is TRUE
if ($_SESSION['Admin'] != TRUE) { header('Location: ../admin');exit;}
$query = "SELECT * FROM {$xname}_zonas WHERE localidad_id={$id}";
$sql = dataset($query);
foreach ($sql as $k=>$v) {
	echo '<option value="'.$v['id'].'">'.$v['nombre_es'].'</option>';
}
?>

