<?php
/**
 * Sitemap Generator
 * 
 * @author Daniel Beard / BE Creativos <daniel@creativos.be>
 */

namespace Brunelencantado\Sitemap;

use Brunelencantado\Database\DbInterface;

class Sitemap {


    protected $db;
    
    /**
     * Webpage domain
     * @var String $domain
     */
    public $domain = null;
    
    /**
     * List of avaliable languages
     * @var Array $languages
     */
    public $languages = array();
    
    /**
     * Pages not printed on the root folder
     * @var Array $exclude_articles
     */
    public $exclude_articles = array();
    
    /**
     * Pages considered for each detail property/product page
     * @var Array $detailpage
     */
    public $detailpage = [];

    /**
     * @brief Constructor
     * 
     * @param Boolean $debug, if TRUE prints in text/plain, otherwise prints in XML
     */
    public function __construct(DbInterface $db, $debug = false) 
    {
		$this->db = $db;
        if ($debug) {
            header("Content-type: text/plain; charset=UTF-8'");
        }
        else {
            header("Content-type: text/xml; charset=UTF-8'");
        }
    }
    
    /**
     * @brief Renders full sitemap
     * 
     * @return String XML sitemap
     */
    public function render() 
    {
        $output =  '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
        $output .=  '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">' . PHP_EOL;
        $output .= $this->articles2xml();
        // $this->properties2xml();
        $output .=  '</urlset>' . PHP_EOL;
		
		return $output;
    }

//-- data to xml -------------------------------------------------------------//
    
    /**
     * Echos the XML nodes for the main front pages
     */
    private function articles2xml()
    {
        $articles = $this->get_articles();

		$output = '';
		
        // Root page
        $output .= $this->get_xml_url_loc();
        
        // All the frontpages
        foreach ($articles as $index => $article) {
            foreach ($this->languages as $lang) {
               $output .=  $this->get_xml_url_loc($lang, $article['slug_' . $lang]);
            }
        }
		
		return $output;
    }
    
    /**
     * Echoes all properties in sitemap format
     */
    private function properties2xml()
    {
        $pages = $this->get_detailpages();
        $properties = $this->get_properties();
		
		$output = '';
        
        // All the frontpages
        foreach ($properties as $index => $property) {
            foreach ($pages as $page) {
                foreach ($this->languages as $lang) {
                    $output .=$this->get_xml_url_loc($lang, $page['slug_' . $lang] . '/' . $property['id'] . '/' . slug($property['nombre']));
                }
            }
        }
		
		return $output;
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
        $sql = 'SELECT clave';
        foreach ($this->languages as $lang) {
            $sql .= ', slug_'. $lang;
        }
        $sql .= ' FROM '. $xname .'_articulos WHERE clave NOT IN ';
        $sql .= '("'. implode('","', $this->exclude_articles) . '")';        
        return $sql;
    }

    /**
     * Returns all the ARTICULOS exceps the keys given by $exclude_articles
     * @return recordset List of ARTICULOS
     */
    private function get_articles() 
    {
        return $this->db->dataset($this->get_articles_query());
    }        
    
    /**
     * Return SQL string for ARTICULOS of detail pages
     * @global string $xname client db preffix
     * @return string SQL query for ARTICULOS  of detail pages in each language
     */
    private function get_detailpages_query()
    {
        global $xname;
        $sql = 'SELECT clave';
        foreach ($this->languages as $lang) {
            $sql .= ', slug_'. $lang;
        }
        $sql .= ' FROM '. $xname .'_articulos WHERE clave IN ';
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
        return 'SELECT id, nombre FROM ' . $xname . '_viviendas;';
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
        $output = '  <url>' . PHP_EOL . '      <loc>' . $this->domain;
        if ($lang) {
            $output .= $lang . '/';
        }
        if ($article) {
            $output .= $article . '/';
        }
        $output .= '</loc>' . PHP_EOL . '  </url>' . PHP_EOL;     

		return $output;		
    }

//-- end-of-class ------------------------------------------------------------//
}

/* end-of-file */