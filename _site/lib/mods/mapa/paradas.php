<?php
// Includes
set_include_path('../../../');
include("../../../lib/admin.php"); 


$query = "SELECT * FROM {$xname}_paradas";
$sql = dataset($query);

echo '<?xml version="1.0"?>'."\n";
echo '<markers>'."\n";
foreach ($sql as $k=>$v){
	echo '	<marker lat="'.$v['lat'].'" lng="'.$v['lon'].'"  name="'.$v['nombre'].'" icon="taxi.png" id="m_'.$v['sid'].'"></marker>'."\n";
}
echo '</markers>';

// End file