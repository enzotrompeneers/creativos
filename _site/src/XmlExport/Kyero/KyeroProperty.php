<?php
/**
 * Kyero Export class
 *
 * First set up for Huisen Onder de Zon
 *
 * @author Daniel Beard <daniel@creativos.be>
 */

namespace Brunelencantado\XmlExport\Kyero;


class KyeroProperty
{
    protected $id = 0;
    protected $date = '';
    protected $ref = '';
    protected $price = 0;
    protected $price_freq = 'sale';
    protected $currency = 'EUR';
    protected $part_ownership = 0;
    protected $leasehold = 0;
    protected $new_build = 0;
    protected $type = 'villa';
    protected $town = '';
    protected $province = '';
    protected $location_detail = '';
    protected $notes = '';      
    
    protected $beds = 0;
    protected $baths = 0;
    protected $pool = 0;
    
    protected $location = NULL;
    protected $surface_area = NULL;
    protected $energy_rating = NULL;    
    protected $url = array();
    protected $desc = array();
    protected $features = array();
    protected $images = array();

    public function __construct() 
    {
	$this->date = date(TIMESTAMP_STRING);
    }   
    
    public function __get($var)
    {
        return $this->$var;
    }

    public function __set($var, $value)
    {
	$this->$var = $value;
    }    
    
    public function addUrl(KyeroURLS $url)
    {
	array_push($this->url, $url);
    }     
    
    public function addDescription(KyeroDescription $description)
    {
	array_push($this->desc, $description);
    }    
    
    public function addFeature($feature)
    {
		array_push($this->features, $feature);
    }  
    
    public function addImage(KyeroImages $image)
    {
	array_push($this->images, $image);
    }
    
    public function __toString() 
    {
	$attributtes = get_object_vars($this);
	$string  = '    <property>' . PHP_EOL;
	
	foreach ($attributtes as $var => $value) {
	    $string .= Kyero3::getXmlTag($var, $value);
	}
	
	$string .= '    </property>' . PHP_EOL;
	return $string;	
    }    
}

// End of file