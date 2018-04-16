<?php
/**
 * Sitemap Generator for Brunel-Encatado sites
 *
 * This files generates valid sitemaps in xml format to use in Google
 * Wemaster Tools.
 *
 * PHP version 5
 *
 * @category   core
 * @package    modules
 * @author     José Pérez Martínez <jose@brunel-encantado.com>
 * @copyright  2014 Brunel Encantado
 * @version    SVN: $Id$
 */

/**
 * @class SitemapGenerator
 * Generates a valid XML sitemap for Google Webmaster Tools
 */
class SitemapGenerator {

    /**
     * Webpage domain
     * @var string $domain
     */
    public $domain = NULL;
    
    /**
     * List of avaliable languages
     * @var array $languages
     */
    public $languages = array();
    
    /**
     * Pages not printed on the root folder
     * @var array $exclude_articles
     */
    public $exclude_articles = array();
    
    /**
     * Pages considered for each detail property/product page
     * @var $detailpage array
     */
    public $detailpage = array();

    /**
     * Constructor
     * @param bool $debug, if TRUE prints in text/plain, otherwise prints
     *                     in XML
     */
    public function __construct($debug=FALSE) 
    {
        if ($debug) {
            header("Content-type: text/plain; charset=UTF-8'");
        }
        else {
            header("Content-type: text/xml; charset=UTF-8'");
        }
    }
    
    /**
     * Magic method! 
     * Called when we cast the object to string. For example:
     * 
     * echo $object     or
     * (string) $object
     * 
     */
    public function __toString() 
    {
        echo '<?xml version="1.0" encoding="UTF-8"?>', PHP_EOL;
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">', PHP_EOL;
        $this->articles2xml();
        $this->properties2xml();
        echo '</urlset>', PHP_EOL;
    }

//-- data to xml -------------------------------------------------------------//
    
    /**
     * Echos the XML nodes for the main front pages
     */
    private function articles2xml()
    {
        $articles = $this->get_articles();

        // Root page
        $this->get_xml_url_loc();
        
        // All the frontpages
        foreach ($articles as $index => $article) {
            foreach ($this->languages as $lang) {
                $this->get_xml_url_loc($lang, $article['slug_' . $lang]);
            }
        }
    }
    
    /**
     * Echoes all properties in sitemap format
     */
    private function properties2xml()
    {
        $pages = $this->get_detailpages();
        $properties = $this->get_properties();
        
        // All the frontpages
        foreach ($properties as $index => $property) {
            foreach ($pages as $page) {
                foreach ($this->languages as $lang) {
                    $this->get_xml_url_loc($lang, $page['slug_' . $lang] . '/' . $property['id'] . '/' . slug($property['nombre']));
                }
            }
        }
    }

//-- sql functions -----------------------------------------------------------//    
    
    /**
     * Return SQL string for ARTICULOS
     * @global string $xname client db preffix
     * @return string SQL query for ARTICULOS in each language
     */
    private function get_articles_query() 
    {
        global $xname;
        $sql = 'select clave';
        foreach ($this->languages as $lang) {
            $sql .= ', slug_'. $lang;
        }
        $sql .= ' from '. $xname .'_articulos where clave not in ';
        $sql .= '("'. implode('","', $this->exclude_articles) . '")';        
        return $sql;
    }

    /**
     * Returns all the ARTICULOS exceps the keys given by $exclude_articles
     * @return recordset List of ARTICULOS
     */
    private function get_articles() 
    {
        return dataset($this->get_articles_query());
    }        
    
    /**
     * Return SQL string for ARTICULOS of detail pages
     * @global string $xname client db preffix
     * @return string SQL query for ARTICULOS  of detail pages in each language
     */
    private function get_detailpages_query()
    {
        global $xname;
        $sql = 'select clave';
        foreach ($this->languages as $lang) {
            $sql .= ', slug_'. $lang;
        }
        $sql .= ' from '. $xname .'_articulos where clave in ';
        $sql .= '("'. implode('","', $this->detailpage) . '")';        
        return $sql;        
    }    
    
    /**
     * The ARTICULOS for each detail page
     * @return recordset Collection of ARTICULOS
     */
    private function get_detailpages()
    {
        return dataset($this->get_detailpages_query());
    }

    /**
     * Return SQL string for VIVIENDAS
     * @global string $xname client db preffix
     * @return string SQL query for VIVIENDAS in each language
     */
    private function get_properties_query()
    {   
        global $xname;
        return 'select id, nombre from ' . $xname . '_viviendas;';
    }    
    
    /**
     * Returns all the VIVIENDAS in db
     * @return recordset List of VIVIENDAS
     */
    private function get_properties()
    {
        return dataset($this->get_properties_query());
    }
    
//-- misc --------------------------------------------------------------------//    
    
    /**
     * Generates the correct <url><loc> ... </loc></url> tag
     * @param string $lang Language for the current url
     * @param string $article the ARTICULO and options
     */
    private function get_xml_url_loc($lang='', $article='') 
    {
        echo '  <url>', PHP_EOL, '      <loc>http://', $this->domain;
        if ($lang) {
            echo '/' , $lang, '/';
        }
        if ($article) {
            echo $article, '/';
        }
        echo '</loc>', PHP_EOL, '  </url>', PHP_EOL;        
    }

//-- end-of-class ------------------------------------------------------------//
}

/* end-of-file */