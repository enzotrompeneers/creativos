<?php

set_include_path('../');
include("../lib/admin.php"); 
error_reporting(4); 
require_once("inc/helpers.php");
require_once("webadmin2/config/config.php");

$idioma					= req('idioma');
$_SESSION['language']	= $idioma;
header('location: articulos.php');

// End file