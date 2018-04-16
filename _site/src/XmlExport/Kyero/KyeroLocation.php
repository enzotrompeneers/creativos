<?php
/**
 * Kyero Export class
 *
 * First set up for Huisen Onder de Zon
 *
 * @author Daniel Beard <daniel@creativos.be>
 */

namespace Brunelencantado\XmlExport\Kyero;


class KyeroLocation
{
    protected $latitude;
    protected $longitude;
    
    public function __construct($latitude, $longitude) 
    {
	$this->latitude = number_format((float)$latitude, 6);
	$this->longitude = number_format((float)$longitude, 6);
    } 
    
    public function __toString() 
    {
	$string  = '		<latitude>' . (string)$this->latitude . '</latitude>' . PHP_EOL;
	$string .= '		<longitude>' . (string)$this->longitude . '</longitude>' . PHP_EOL;
	return $string;
    }     
}

// End of file