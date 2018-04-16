<?php
/**
 * MODULE: Sitemap Generator for Brunel-Encatado sites
 *
 * This files creates a sitemap generator and echos in xml format to use in 
 * Google Wemaster Tools.
 *
 * PHP version 5
 *
 * @category   core
 * @package    modules
 * @author     José Pérez Martínez <jose@brunel-encantado.com>
 * @copyright  2014 Brunel Encantado
 * @see        sitemap.inc.php
 * @version    SVN: $Id$
 * 
 * You need to add the next line in your .htaccess:
 * # Sitemap generator
 * RewriteRule ^sitemap\.xml$          lib/mods/sitemap/sitemap.php
 * 
 */

require_once dirname(__FILE__) . '/../../admin.php';
require_once dirname(__FILE__) . '/sitemap.inc.php';

// sitemap generator
$sitemap = new SitemapGenerator;

// configure options
$sitemap->domain = 'www.kazacostablanca.com';	// domain
$sitemap->languages = $languages;		// languages availables in sitemap
$sitemap->exclude_articles = array('inicio', 'sendmail', 'buscar', 'ficha', 'getpdf'); // articulos excluded in the mail pages listing
$sitemap->detailpage = array('ficha', 'getpdf'); // subpages for each detailpage

// Send XML to client
echo $sitemap;

/* end-of-file */