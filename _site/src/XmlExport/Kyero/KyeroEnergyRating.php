<?php
/**
 * Kyero Export class
 *
 * First set up for Huisen Onder de Zon
 *
 * @author Daniel Beard <daniel@creativos.be>
 */

namespace Brunelencantado\XmlExport\Kyero;


class KyeroEnergyRating
{
    protected $consumption;
    protected $emissions;
    
    public function __construct($consumption, $emissions) 
    {
	$this->consumption = (string) $consumption;
	$this->emissions = (string) $emissions;
    }
    
    public function __toString() 
    {
	$string  = '		<consumption>' . (string) $this->consumption . '</consumption>' . PHP_EOL;
	$string .= '		<emissions>' . (string) $this->emissions . '</emissions>' . PHP_EOL;
	return $string;
    }     
}

// End of file