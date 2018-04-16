<?php
/**
 * Menu rendering class
 *
 * @author Daniel Beard / BE Creativos <daniel@creativos.be>
 */

namespace Brunelencantado\Content;

use Brunelencantado\Database\DbInterface;

class Menu
{

	/**
	 * @var String Class of element with submenu
	 */
	public $hasMenuClass = 'item-with-ul';
	
	/**
	 * @var String Class of dropdown menu
	 */
	public $menuClass = 'dropdown';

	/**
	 * @var String Class of active element
	 */
	public $activeClass = 'active';

	protected $db;
	protected $menuData = array();
	protected $menus = array();
	protected $languages;
	protected $pagina;
	protected $translatedLinks;
	
	/**
	 * @param DbInterface $db
	 * @param Array $languages
	 * @param String $pagina
	 */
	public function __construct(DbInterface $db, Array $languages, $pagina = null)
	{
		$this->db = $db;
		$this->languages = $languages;
		$this->pagina = $pagina;
	}
	
    /**
     * @brief Creates menu from SQL data
     * 
     * @param String $clave 
     * @return String Menu as HTML list
     */
	public function createMenu($clave = 'header')
	{
		
		$this->createMainMenu($clave);
		$this->insertSubmenus($clave);
		
		$this->translatedLinks = $this->getTranslatedLinks($this->languages, $this->pagina);

		return $this->renderMenu($clave);

	}
	
	/**
	 *  @brief Queries database for menu content
	 * 
	 *  @param String $clave
	 *  @return Array
	 *  
	 */
	protected function createMainMenu($clave)
	{
		
		$menuData = $this->getMenuData();
		$menuStructure = array();

		foreach ($menuData as $k => $v){

			if ($v['header_menu'] == 0) continue;
			if (($v['footer_menu'] == 0 && $clave == 'footer') ||  ($clave == 'footer' && $v['parent_id'])) continue; // Footer has no submenu
			$menuStructure[$v['id']] = $v;

		}

		$this->menus[$clave] = $menuStructure;

	}
	
    /**
     * @brief Gets full url from key
     * 
     * @param String $clave 
     * @return String URL
     */
	public function getUrl($clave, $language = null)
	{
		$lang = ($language) ? $language : LANGUAGE;
		$menuItem = $this->getMenuItem($clave);

		$slug = (isset($menuItem['slug'])) ? $menuItem['slug'] : $clave;
		
		$url = BASE_SITE . $lang . '/' . $slug .'/';
		
		return $url;
		
	}
	
    /**
     * @brief Gets link text from key
     * 
     * @param String $clave 
     * @return String Link text
     */
	public function getLink($clave)
	{
		
		$menuItem = $this->getMenuItem($clave);
		
		if ($menuItem) {
			
			$link = $menuItem['link'];
			
			return $link;
			
		} 
		
		return '!' . $clave;

		
	}
		
    /**
     *  @brief Gets link text from key
     * 
     * @param String $clave 
     * @return String Link text
     */
	public function getTitle($clave)
	{
		
		$menuItem = $this->getMenuItem($clave);
		
		if ($menuItem) {
			
			$title = $menuItem['titulo'];
			
			return $title;
			
		} 
		
		return '!' . $clave;

	}
	
	/**
	 * @brief Translates link
	 *
	 * @param String $language
	 * @param String $clave
	 * @param Object $item
	 * @return String URL
	 */
	public function getTranslatedLink($language, $clave, $item = null)
	{
		
		$slug = $this->translatedLinks['slug_' . $language];

		$link = BASE_SITE . $language . '/';
		$link .= ($clave != 'inicio') ? $slug . '/' : '';
		$link .= ($item) ? $item->getTranslatedLink($language) : '';

		return $link;

	}	

	/**
	 * @brief Gets all translated links
	 *
	 * @param Array $languages
	 * @param String $clave
	 * @return Array
	 */
	protected function getTranslatedLinks(Array $languages, $clave)
	{
		$aSlugs = array_map(function($item){ return 'slug_' . $item; }, $languages);
		$sSlugs = implode(', ', $aSlugs);

		$query = "SELECT {$sSlugs} FROM ".XNAME."_articulos WHERE clave = '{$clave}'";
		$sql = $this->db->record($query);

		return $sql;

	}
	
    /**
     * @brief Collects all menu data from DB
     * 
     * @return Array
     */
	protected function getMenuData()
	{
		
		$query = " 	SELECT id, clave, link_" . LANGUAGE . " AS link,  titulo_" . LANGUAGE . " AS titulo,
					slug_" . LANGUAGE . " AS slug, header_menu, footer_menu, parent_id, orden
					FROM " . XNAME . "_articulos 
					ORDER BY orden
					";
					
		$sql = $this->db->dataset($query); 
		
		$this->menuData = $sql;
		
		return $sql;
	}
	
    /**
     * @brief Gets menu item by key
     *
     * @param String $clave
     * @return Array
     */
	protected function getMenuItem($clave)
	{
		
		$menuData = $this->menuData;
		
		$row = [];
		
		foreach ($menuData as $k => $v) {
			
			if ($v['clave'] == $clave) {
				
				$row = $v;
				
			}
			
		}
		
		return $row;
		
	}
	
	/**
	 *  @brief Gets submenus and inserts into main menu
	 *  
	 *  @param String $clave
	 *  @return Void
	 */
	protected function insertSubmenus($clave)
	{
		
		foreach ($this->menus[$clave] as $k => $v){
			
			if ($v['parent_id'] == 0) continue;
			
			$parent = $v['parent_id'];
			$this->menus[$clave][$parent]['submenu'][] = $v;
			
			unset($this->menus[$clave][$v['id']]);
			
		}
	}
	
	/**
	 *  @brief Renders HTML menu from array
	 *  
	 *  @param String $clave
	 *  @return String HTML list
	 */
	protected function renderMenu($clave)
	{
		
		$data = $this->menus[$clave];

		$output = '<ul>';
		foreach ($data as $k => $v){
			$submenu = (array_key_exists('submenu', $v)) ? $v['submenu'] : false;
			
			// Insert submenu
			if ($submenu) {
				$submenuHTML = '<ul class="' . $this->menuClass . '">';
				foreach($submenu as $x => $y){
					$submenuHTML .= $this->renderListElement($y);
				}
				$submenuHTML .= '</ul>';
				$v['submenuHtml'] = $submenuHTML;
			}
			
			// Render this element
			$output .= $this->renderListElement($v);
			
		}
		$output .= '</ul>';
		
		return $output;
	}
	
	/**
	 *  @brief Renders to HTML list from data
	 *  
	 *  @param Array $data Page data
	 *  @return String <li> element
	 */
	protected function renderListElement(Array $data)
	{
		$submenuHtml = false;
		$hasMenuClass = false;
		
		if (!isset($data['clave']) || !isset($data['slug'])) return;
		
		// Submenu HTML & class
		if (array_key_exists('submenuHtml', $data)) {
			$submenuHtml = $data['submenuHtml'];
			$hasMenuClass = $this->hasMenuClass;
		} 
		
		$link = LANGUAGE . '/' . $data['slug'] . '/';
		
		if ($data['clave'] == 'inicio') $link = LANGUAGE . '/';
		
		return '	<li class="' . $data['clave'] . ' ' . $hasMenuClass . '">
						<a href="' . $link . '" title="' . $data['titulo'] . '">
							' . $data['link'] . ' 
						</a>
						' . $submenuHtml . '
					</li>' . "\n";
	}
}
