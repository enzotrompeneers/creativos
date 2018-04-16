<?php
/**
 * Kyero Export class
 *
 * First set up for Huisen Onder de Zon
 *
 * @author Daniel Beard <daniel@creativos.be>
 */

namespace Brunelencantado\XmlExport\Kyero;


class KyeroImages
{
    protected $id;
    protected $url;
    
    public function __construct($id, $url) 
    {
	$this->id = (string) $id;
	$this->url = (string) $url;
    }
    
    public function __toString() 
    {
	return '	    <image id="' . $this->id  . '"><url>' . $this->url . '</url></image>' . PHP_EOL;
    }     
}

// End of file