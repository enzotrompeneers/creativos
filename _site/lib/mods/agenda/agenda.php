<?php
// Last upadate: 21/11/2014
// For Yes-Inmo

$age_inc = TRUE;

///***** SHOW AGENDA LISTING *****///
	
function show_agenda($pagesize=6) {
	global $language,$xname,$languages;
	$agendaQuery = "SELECT *,titulo_{$language} AS titulo,descr_{$language} AS descripcion FROM {$xname}_agendas ORDER BY fecha DESC LIMIT 6";
	$agenda = dataset($agendaQuery);
	
	// printout($agenda);
	include('view_agenda.php');
}





// End file

