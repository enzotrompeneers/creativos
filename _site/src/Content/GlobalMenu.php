<?php
/**
 * Menu rendering class for Global - adds countries to dropdowns
 *
 * First set up for Global Hotels for Sale
 *
 * @author Daniel Beard / BE Creativos <daniel@creativos.be>
 */

namespace Brunelencantado\Content;


class GlobalMenu extends Menu
{
	
	/**
	 *  @brief Gets data and creates menu from data
	 *  
	 *  @param [in] $clave Key 
	 *  
	 *  @return HTML list
	 *  
	 */
	public function createMenu($clave, $options = array())
	{
		
		$this->getMenuData($clave);
		$this->insertSubmenus($clave);

		if ($options) {
			foreach ($options as $o){
				$this->addCountries($clave, $o);
			}
			
		}

		return $this->renderMenu($clave);

	}	
	
	/**
	 *  @brief Adds country pulldown for "Hotels for sale" & "B&B for sale" menu items
	 *  
	 *  @param [in] $clave Key
	 *  @return void
	 *  
	 */
	protected function addCountries($clave, $option)
	{
		$query = "
					SELECT id, nombre_".LANGUAGE." AS nombre
					FROM ".XNAME."_paises
					WHERE id IN (SELECT DISTINCT pais_id FROM ".XNAME."_hoteles)
					ORDER BY nombre
					";
		$sql = $this->db->dataset($query);
		
		foreach ($sql as $k => $v){
			$menuItemData = array();
			$menuItemData['clave'] 	= $option;
			$menuItemData['slug'] 	= $this->menus[$clave][$option]['slug'] . '/' . slug($v['nombre']) . '/' . $v['id'];
			$menuItemData['link']	= $v['nombre'];
			$menuItemData['titulo']	= $this->menus[$clave][$option]['titulo'] . ' ' . $v['nombre'];
		
			$this->menus[$clave][$option]['submenu'][] = $menuItemData;			
		}

		
		
	}
	
}


// End of file