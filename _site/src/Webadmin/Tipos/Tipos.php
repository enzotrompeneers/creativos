<?php
/**
 * Tipos - General CRUD manager for CMS
 *
 * Processes URI requests
 *
 * @author Daniel Beard <daniel@creativos.be>
 */

namespace Brunelencantado\Webadmin\Tipos;


class Tipos
{
	protected $registry = null;
	protected $db = null;
	
	/**
	 *  @brief Constructor, sets registry and db properties
	 *  
	 *  @param [in] $db Database class
	 *  
	 */
	 public function __construct (\Brunelencantado\Registry\Registry $registry)
	 {
		 $this->registry = $registry;
		 $this->db = $this->registry->getObject('db');
		
	 }	
}







// End of file