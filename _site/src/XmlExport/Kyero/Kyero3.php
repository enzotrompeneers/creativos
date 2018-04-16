<?php
/**
 * Kyero Export class
 *
 * First set up for Huisen Onder de Zon
 *
 * @author Daniel Beard <daniel@creativos.be>
 */

namespace Brunelencantado\XmlExport\Kyero;



class Kyero3
{
    const XML_CONTENT_TYPE = "Content-type: text/xml; charset=UTF-8'";
    protected $feed_version = 3;
    protected $properties = array();    
    protected $languages = array();
        
    public function __construct(array $languages = array()) 
    {
	$this->languages = $languages;
    }
    
    public function __get($var)
    {
        return $this->$var;
    }

    public function __set($var, $value)
    {
	$this->$var = $value;
    }
    
    public function addProperty(KyeroProperty $property)
    {
	array_push($this->properties, $property);
    }
    
    public function __toString() 
    {
	$string  = '<?xml version="1.0" encoding="utf-8" standalone="yes"?>'. PHP_EOL;
	$string .= '<root>'. PHP_EOL;
	$string .= '    <kyero>'. PHP_EOL;
	$string .= '        <feed_version>' . (string)$this->feed_version . '</feed_version>'. PHP_EOL;
	$string .= '    </kyero>'. PHP_EOL;
	
	foreach ($this->properties as $property) {
	    $string .= (string) $property;
	}
	
	$string .= '</root>'. PHP_EOL;
	
	return $string;
    }
    
    public static function getXmlTag ($name, $value)
    {
	$string = '';
	if (is_array($value)) {
	    if (count($value) > 0) {
		$string .= '        <' . $name . '>' . PHP_EOL;		
		foreach ($value as $item) {
		    if ($item != NULL) {
			$string .= ((string) $item . PHP_EOL);
		    }
		}		    
	    $string .= '        </' . $name . '>' . PHP_EOL;
	    }
	}
	else {
	    if ($value != NULL) {
		$string .= '        <' . $name . '>' . (string) $value . '</' . $name . '>' . PHP_EOL;
	    }
	}	
	return $string;
    }     
}


// End of file