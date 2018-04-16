<?php
/**
 * Kyero Export class
 *
 * First set up for Huisen Onder de Zon
 *
 * @author Daniel Beard <daniel@creativos.be>
 */

namespace Brunelencantado\XmlExport\Kyero;


class KyeroDescription
{
    protected $lang;
    protected $value;
    
    public function __construct($lang, $value) 
    {
	$this->lang = (string) $lang;
	$this->value = (string) $value;
    }
    
    public function __toString() 
    {
	return '	    <' . $this->lang . '>' . $this->value . '</' . $this->lang . '>' . PHP_EOL;
    }     
}

// End of file