<?php
/**
 * Kyero Export class
 *
 * First set up for Huisen Onder de Zon
 *
 * @author Daniel Beard <daniel@creativos.be>
 */

namespace Brunelencantado\XmlExport\Kyero;


class KyeroSurfaceArea
{
    protected $built;
    protected $plot;
    
    public function __construct($built, $plot) 
    {
	$this->built = $built == 0 ? 0 : (int) $built;
	$this->plot = $plot == 0 ? 0 : (int) $plot;
    }
    
    public function __toString() 
    {
	$string  = '		<built>' . (string)$this->built . '</built>' . PHP_EOL;
	$string .= '		<plot>' . (string)$this->plot . '</plot>' . PHP_EOL;
	return $string;
    }     
}

// End of file