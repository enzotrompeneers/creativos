<?php
/**
 * Kyero Export class
 *
 * First set up for Huisen Onder de Zon
 *
 * @author Daniel Beard <daniel@creativos.be>
 */

namespace Brunelencantado\XmlExport\Kyero;


class KyeroURLS
{
    protected $lang;
    protected $url;
    
    public function __construct($lang, $url) 
    {
	$this->lang = (string) $lang;
	$this->url = (string) $url;
    }
    
    public function __toString() 
    {
	return '	    <' . $this->lang . '>' . $this->url . '</' . $this->lang . '>' . PHP_EOL;
    }     
}

// End of file