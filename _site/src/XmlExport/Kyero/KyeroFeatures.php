<?php
/**
 * Kyero Export class
 *
 * First set up for Huisen Onder de Zon
 *
 * @author Daniel Beard <daniel@creativos.be>
 */

namespace Brunelencantado\XmlExport\Kyero;


class KyeroFeatures
{
    protected $feature;
	protected $value;
    
    public function __construct($feature) 
    {
	$this->feature = (string) $feature;
	// $this->value = (string) $value;
    }
    
    public function __toString() 
    {
	return '	    <feature><![CDATA[' . htmlspecialchars($this->feature) . ']]></feature>' . PHP_EOL;
    }     
}

// End of file