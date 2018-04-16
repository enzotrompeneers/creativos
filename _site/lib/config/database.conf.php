<?php
define ('TEST', true);

// $xname defines table prefix
$xname = 'becreativos';
define('XNAME', $xname);

// Database credentials depending on user/environment
define('DBMODE', 'development');
$aDATABASE	= array();

$aDATABASE['development']['username']		= 'root';
$aDATABASE['development']['password']		= '';
$aDATABASE['development']['database']		= 'creativo_webdb';
$aDATABASE['development']['hostname']		= 'localhost';

$aDATABASE['staging']					= array();
$aDATABASE['staging']['username']		= 'root';
$aDATABASE['staging']['password']		= '';
$aDATABASE['staging']['database']		= 'disenowe_becreativos';
$aDATABASE['staging']['hostname']		= 'localhost';


$aDATABASE['production']				= array();
$aDATABASE['production']['username']	= 'becreativos_webusr';
$aDATABASE['production']['password']	= 'michke1242';
$aDATABASE['production']['database']	= 'becreativos_webdb';
$aDATABASE['production']['hostname']	= 'localhost';

// Exceptions to correct automatic fuck-ups
$singularArray = array(
	'localidades' => 'localidad'
);

/* End-Of-File */