<?php
include('lib/admin.php');


/*
Pass translations form one table to another

$query = "SELECT * FROM cyc2_traducciones";
$sql = dataset($query);


foreach ($sql as $k=>$v) {
	
	$insert = array();
	$insert['clave'] = $v['clave'];
	$insert['used'] = $v['used'];
	foreach ($languages as $l ) {
		if (!empty($v[$l])){
			$insert[$l] = $v[$l];
		}
	}
	$insertDb = insertDb($insert,$xname.'_traducciones');
	printout($insertDb);
	mysql_query($insertDb) or die(mysql_error());
}
/*
// Put taxi stop names to coordinates

$query = "SELECT * FROM torre2_sobipro_object";
$sql = dataset($query);
// printout($sql);

foreach ($sql as $k=>$v) {
	$sid = $v['id'];
	
	$update = array();
	$update['nombre'] = humanize($v['nid']);
	$updateDb = updateDb($update,$xname.'_paradas',$sid,'sid');
	echo $updateDb.'<br />';
	mysql_query($updateDb) or die(mysql_error());
	
}
*/

// End file