<?php
// languages. First is default
$languages = array('es','en','nl'); // Front end
$languagesWebadmin = array('es','en','nl'); // Back end
$language = (!empty($_GET['idioma']))?$_GET['idioma']:$languages[0]; // Total languages // default is first language
define('LANGUAGE', $language);

// If not language, send to home page
if (!in_array($language,$languages)){ header('location:'.$base_site.$languages[0].'/'); } 

// Locale
setlocale(LC_ALL, $language.'_'.strtoupper($language));
date_default_timezone_set('Europe/Madrid');

/* End-Of-File */