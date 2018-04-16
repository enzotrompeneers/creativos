<?php
/**
 * Registry class
 *
 * Holds important objects like database, logger, etc...
 *
 * @author Daniel Beard <daniel@brunel-encantado.com>
 */

namespace Brunelencantado\Registry;

class Registry
{
	public $frontendLanguages = array('en', 'es');
	public $backendLanguages = array('en', 'es');
	
	public function __construct(){}
	
	public function getObject($name)
	{
		return $this->{$name};
	}
	
	public function setObject($name, $object)
	{
		$this->{$name} = $object;
	}
	
}




// End file