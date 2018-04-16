<?php
// Form arrays

if ($pagina=='servicio_aeropuerto'){

	$tipo_servicio = array(
		array('id'=>'','nombre'=>trad('tipo_servicio')),
		array('id'=>'torrevieja_aeropuerto','nombre'=>trad('torrevieja_aeropuerto')),
		array('id'=>'aeropuerto_torrevieja','nombre'=>trad('aeropuerto_torrevieja'))
	);
	$aeropuertos = array(
		array('id'=>'','nombre'=>trad('aeropuerto')),
		array('id'=>'alicante','nombre'=>trad('aeropuerto_alicante')),
		array('id'=>'murcia','nombre'=>trad('aeropuerto_murcia')),
		array('id'=>'valencia','nombre'=>trad('aeropuerto_valencia')),

	);
	$numero = array(
		array('id'=>'1','nombre'=>1),
		array('id'=>'2','nombre'=>2),
		array('id'=>'3','nombre'=>3),
		array('id'=>'4','nombre'=>4)
	);
}







// End file