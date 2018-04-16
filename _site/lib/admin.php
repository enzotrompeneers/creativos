<?php

use Noodlehaus\Config;

use \Brunelencantado\Logger\Logger;
use \Brunelencantado\Logger\LoggerWeb;
use \Brunelencantado\Database\MySqliDatabase;
use \Brunelencantado\Content\Menu;
use \Brunelencantado\Content\Page;
use \Brunelencantado\Content\Panoramics;
use \Brunelencantado\Viviendas\Buscador;
use \Brunelencantado\Quicklinks\Quicklinks;

require 'vendor/autoload.php'; // Composer autoload

// Error reporting
error_reporting(1); ini_set('display_errors', '1');
error_reporting(E_ALL ^ E_DEPRECATED);
session_start();

$config = new Config([dirname(__FILE__) . '/../config/config.yaml']); 
require_once dirname(__FILE__) . '/helpers.php'; // Includes

// *** GENERAL CONFIG ***//
define ('TEST', $config->get('test'));
define ('ALQUILERES', $config->get('alquileres'));
define ('LOCALHOST', (in_array($_SERVER["SERVER_ADDR"], ["127.0.0.1","::1"])));

// Base site
$base_site = ($config->get('base_site'))?: 'http://'.$_SERVER['HTTP_HOST'].substr($_SERVER['SCRIPT_NAME'], 0, strrpos($_SERVER['SCRIPT_NAME'], "/")+1);
define('BASE_SITE', str_replace('webadmin2/', '', $base_site));

// Languages
$languages = $config->get('locales');
$languagesWebadmin = $config->get('locales_admin');
$currentLanguage = (isset($_GET['idioma'])) ? filter_var($_GET['idioma'], FILTER_SANITIZE_STRING) : null;
$language = ($currentLanguage) ? $currentLanguage : $config->get('default_locale');
$language = (isset($_SESSION['language']) && !isset($_GET['idioma'])) ? $_SESSION['language'] : $language;
if (!isset($webadmin)) define ('LANGUAGE', $language);

// *** DATABASE *** //
$dbConfig = $config->get('db');
$mode = $dbConfig['mode'];
$xname = $dbConfig['xname'];
define ('XNAME', $xname);
$aConnectionData = [
    'hostname' => $dbConfig['mysql'][$mode]['hostname'],
    'database' => $dbConfig['mysql'][$mode]['database'],
    'username' => $dbConfig['mysql'][$mode]['username'],
    'password' => $dbConfig['mysql'][$mode]['password'],
];
// Standard db connection - to be eliminated when possible
$link = mysql_connect($aConnectionData['hostname'], $aConnectionData['username'], $aConnectionData['password']) or die('Could not connect to database server'); // Database credentials
mysql_select_db($aConnectionData['database']) or die('Database connection error');
mysql_query ("SET NAMES 'utf8'");

// Logger tool
define('pTEXT', false);	// text/plain output and save to log file or HTML. Use showLog to get HTML output
define('pSQL', true);	// Shows SQL statements
$log = new LoggerWeb();

// Database mysqli connection
$db = new MySqliDatabase($aConnectionData, $log);
$db->setDebugMode(true);

// Email config
$emailConfig = $config->get('mail');
$emailConfig['default_from_name'] = webConfig('nombre');
$emailConfig['default_from_address'] = webConfig('email');

// Various variables
$id = req('id');
$clave = req('clave');
$slug = req('slug');
$traducciones = getTrad($db);
$item = null;

// Domains
$domains = $config->get('domains');

// Page content
$page = new Page($slug, $db);
$pagina = $page->getClave();
$children = $page->getChildren();
$images = $page->getImages();
$files = $page->getFiles();

// Menu
$menu = new Menu($db, $languages, $pagina, $domains);
$headerMenu = $menu->createMenu('header');
$footerMenu = $menu->createMenu('footer');

// Recaptcha
$recaptchaConfig = $config->get('recaptcha');
$recaptchaCodeConfig = $recaptchaConfig['public'];
$recaptchaSecretCodeConfig = $recaptchaConfig['secret'];
$recaptchaCode = (!LOCALHOST) ? $recaptchaCodeConfig : '6LfyvjQUAAAAAOFPtwFJz0bQI2EKJGVzCP8QbyN6';
$recaptchaSecretCode = (!LOCALHOST) ? $recaptchaSecretCodeConfig : '6LfyvjQUAAAAAOFPtwFJz0bQI2EKJGVzCP8QbyN6';

// Search 
$search = new Buscador($db);

// Panoramicas
$oPanoramicas = new Panoramics($db);
$panoramicas = $oPanoramicas->getList();

// Quicklinks
$oQuicklinks = new Quicklinks($db, $menu);
$quicklinks = $oQuicklinks->getList();



// End file