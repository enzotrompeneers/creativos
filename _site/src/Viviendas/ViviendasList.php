<?php
/**
 * Property list class
 *
 * @author Daniel Beard <daniel@creativos.be>
 */

namespace Brunelencantado\Viviendas;

use Brunelencantado\Database\DbInterface;
use Brunelencantado\Content\Menu;

class ViviendasList
{
	
	use \Brunelencantado\Pagination\PaginationTrait;

	protected $query 			= null;
	protected $filters 			= [];
	protected $oRfilters 		= [];
	protected $table 			= 'viviendas';
	protected $genericListPage 	= 'viviendas';
	protected $db;
	protected $menu;
	protected $limitPerPage;
	protected $language;

	public $remoteDomain;
	public $rows;
	public $iLimitStart;
	public $max;

	/**
	 * @brief Constructor
	 *
	 * @param DbInterface $db
	 * @param Menu $menu
	 * @param String $language Can force language, default LANGUAGE
	 */
	public function __construct(DbInterface $db, Menu $menu, $language = null)
	{
		$this->db = $db;
		$this->menu = $menu;
		$this->menu->createMenu();
		$this->language = ($language) ? $language : LANGUAGE;
		
		if ($this->paginationOn) {
			$resultadosPorPagina = webConfig('resultados_por_pagina');
			$this->limitPerPage = ($resultadosPorPagina != '*!!*resultados_por_pagina') ? $resultadosPorPagina : 6;
		}
		
	}
	
	/**
	 *  @brief Gets list of viviendas
	 *  
	 *  @param Integer $limit Optional
	 *  @param String $order Default main id DESC
	 *  @return Array
	 */
	public function getList($limit = null, $order = 'main.id DESC') 
	{

		$location = ($this->remoteDomain) ? $this->remoteDomain : BASE_SITE;
		
		$sql = $this->getData($limit, $order);
		
		$output = array();
		
		foreach ($sql as $k => $v){
			
			// General data
			$output[$k]					= $v;
			$output[$k]['id'] 			= $v['main_id'];
			
			// Is rental?
			$output[$k]['alquiler']		= ($this->isRental($v));
			$linkPage 					= ($this->isRental($v)) ? 'viviendas-alquiler' : 'viviendas-venta';
			$linkPage 					= (ALQUILERES) ? $linkPage : $this->table;

			// Title		
			$output[$k]['titulo'] 		= ($v['titulo'] != '') ? $v['titulo'] : 
											ViviendasHelpers::frase($v['dormitorios'], $v['tipo'], $v['localidad']);
			$output[$k]['intro']		= ($v['intro']) ? $v['intro'] : shorten_text($v['descripcion'], 250);
			
			// Link
			$output[$k]['link'] 		= 	$this->menu->getUrl($linkPage, $this->language) . 
												slug(trad('espana')) . '/' . 
												slug($v['costa']) . '/' . 
												slug($output[$k]['titulo']) . 
												'-' . $v['main_id'] . '.html';										

			// Price
			$thousandSeperator 			= ($this->language == 'en') ? ',' : '.';
			$output[$k]['precio']		= number_format($v['precio_de_venta'], 0, false, $thousandSeperator);
			
			
			$output[$k]['precio']		= ($output[$k]['alquiler']) ? $this->getLowestRentPrice($v) : $output[$k]['precio'];

			
			// Image		
			$output[$k]['file_img']		= 'images/' . $this->table . '/' . $v['main_id'] . '/l_' . $v['file_name'];
			$output[$k]['img'] 			= ($v['file_name']) ? 
											$location . $output[$k]['file_img'] :
											'images/noImage.png';
			$output[$k]['img'] 			= (mb_substr($v['file_name'], 0, 4)=='http') ? $v['file_name'] : $output[$k]['img']; // For remmote linked images
			
			
			
		};
		
		return $output;
		
	}
	
	/**
	 *  @brief Get results form database
	 *  
	 *  @param Integer $limit Limit
	 *  @return Array
	 */
	protected function getData($limit = false, $order = null)
	{
		
		// Image query
		$imageQuery = "
						, (SELECT img.file_name as file_name
						FROM ".XNAME."_images_{$this->table} img
						WHERE img.parent_id = main.id
						ORDER BY img.orden ASC
						LIMIT 1) AS file_name";

						
		// Rental price
		$rentalPrice = (ALQUILERES) ? " precio_temp_baja," : "";
		$personas= (ALQUILERES) ? " personas," : "";
		
		// Main query
		$query = "
					SELECT main.id AS main_id, referencia, dormitorios, banos, sup_vivienda,
					sup_parcela, precio_de_venta, precio_anterior, precio_desde, fecha_creado, oferta,
					descripcion_".LANGUAGE." AS descripcion, intro_".LANGUAGE." as intro,
					piscina_id AS piscina,
					{$rentalPrice} 
					{$personas}
					lat, lon, reservado, vendido, 
					main.titulo_".$this->language." AS titulo, loc.nombre AS localidad,
					tip.nombre_".$this->language." AS tipo, cla.id AS clase_id, cla.nombre_".$this->language." AS clase,
					cos.nombre_".$this->language." AS costa
					{$imageQuery}
					FROM ".XNAME."_{$this->table} main
					LEFT JOIN ".XNAME."_localidades loc ON main.localidad_id = loc.id
					LEFT JOIN ".XNAME."_tipos tip ON main.tipo_id = tip.id
					LEFT JOIN ".XNAME."_clases cla ON main.clase_id = cla.id
					LEFT JOIN ".XNAME."_costas cos ON main.costa_id = cos.id
					WHERE 1 = 1
		";	

		// Filters
		if (!empty($this->filters)){
			foreach ($this->filters as $filter){
				$query .= " AND ({$filter})";
			}
		}
		
		// OR filters
		if (!empty($this->orFilters)){
			$query .= " AND (";
			foreach ($this->orFilters as $index => $filter){
				$query .= ($index > 0) ? " OR " : "";
				$query .= " {$filter}";
			}
			$query .= ")";
		}

		// Order
		if ($order){
				switch ($order) {
				case 'maxprice':
					$orderSql = 'precio_de_venta DESC';
					break;
				case 'minprice':
					$orderSql = 'precio_de_venta ASC';
					break;
				case 'latest':
					$orderSql = 'fecha_modificado DESC';
					break;
				case 'random':
					$orderSql = 'RAND()';
					break;
				default:
					$orderSql = 'main.id DESC';
			}
			
			$query .= " ORDER BY {$orderSql}";
			
		}
		
		// Limit
		if ($limit) {
			$query .= " LIMIT {$limit}";
		} else {
			
			if ($this->paginationOn == true) {
				// Pagination setup
				$this->pagination = $this->setupPagination($this->db->query($query));
				$iLimitStart = $this->pagination->prePagination();
				$query .= " LIMIT {$iLimitStart}, {$this->limitPerPage} ";				
			}

		}
		
		$this->query = $query;
		
		return $this->db->dataset($query);
		
	}
	
	 /**
	 *  @brief Adds filter to query
	 *  
	 *  @param String $filter SQL filter to add to query. For example: ' dormitorios = 3 '
	 *  @return Void
	 */
	public function addFilter($filter)
	{
		$this->filters[] = $filter;
	}
	
	 /**
	 *  @brief Adds OR filter to query
	 *  
	 *  @param String $filter filter to add to query
	 *  @return Void
	 */
	public function addOrFilter($filter)
	{
		$this->orFilters[] = $filter;
	}
	
    /**
     * @brief Sanitizes whole array of data for SQLconsumption
     * 
     * @param Array $data Data to be sanitized 
     * 
     * @return Array Sanitized data
     */
	public function sanitize(array $data)
	{
		
		$output = [];
		
		foreach ($data as $k => $v) {
			
			if ($v == '') continue;
			
			$output[$k] = filter_var($v, FILTER_SANITIZE_STRING);
			
		}
		
		return $output;
		
	}

	/**
	 *  @brief Changes the product table
	 *  
	 *  @param String $tableName
	 *  @return Void
	 */
	public function setTable($tableName)
	{
		$this->table = $tableName;
	}
	
	/**
	 * @brief Returns property list from array of ids
	 *
	 * @param Array $ids
	 * @return Void
	 */
	public function getListFromIds(array $ids) {
		
		foreach ($ids as $id) {

			$this->addOrFilter("main.id = {$id}");

		}
		
		return $this->getList();
	
	}

	/**
	 * @brief Sets what the artiulos page is dorlinks
	 *
	 * @param String $page
	 * @return Void
	 */
	public function setViviendaPage($page)
	{
		
		$this->genericListPage = $page;
		
	}

	/**
	 * @brief Looks forthe lowest rental price
	 *
	 * @return String price with €/week
	 */
	protected function getLowestRentPrice($data)
	{

		return ($data['precio_temp_baja'] > 0) ? $data['precio_temp_baja'] . '&euro; / ' . trad('semana') : trad('consultar');

	}

	/**
	 * @brief Is this a rental property?
	 *
	 * @param Array $data
	 * @return Boolean
	 */
	protected function isRental($data)
	{

		return ($data['clase_id'] == 3 && ALQUILERES);

	}

	/**
	 * @brief Set language / useful for links
	 *
	 * @param String $language
	 * @return Void
	 */
	public function setLanguage($language)
	{

		$this->language = $language;

	}

	/**
     * @brief Adds OR type filters in array format
     * 
     * @param Array $arrayFilter
     * @param String $clave
     * 
     * @return Void
     */
	public function addArrayFilter($arrayFilter, $clave)
	{
		
		if (!is_array($arrayFilter) && !empty($arrayFilter)) {
			
			return;
			
		}
		
		$sql = '(';
		
		foreach ($arrayFilter as $k => $v) {
			
			$v = filter_var($v, FILTER_SANITIZE_STRING);
		
			$sql .= $clave . ' = ' . $v . ' OR ';
			
		}
		
		$sql = rtrim($sql, 'OR ');
		$sql .= ')';
		
		$this->addFilter($sql);
		
	}

	/**
	 * @brief Adds location as filter
	 *
	 * @param String $localidad
	 * @return Void
	 */
	public function addLocationFilter($localidad)
	{

		$isCosta = (substr($localidad, 0, 6) == 'parent');

		if ($isCosta) {

			$this->addFilter('loc.costa_id = ' . str_replace('parent-', '', $localidad));

			return;

		}

		$this->addFilter('localidad_id = ' . $localidad);

	}

	/**
	 * @brief Returns HTML table with favourites
	 *
	 * @param Array $favourites
	 * @return String HTML table
	 */
	public function getFavourites(array $favourites)
	{

		$favoritosHTML = '<table id="viviendas">';
		$favoritosHTML .= '<tr><td style="width:120px;paddig:4px;background:#fff;text-align:center;">&nbsp;</td>
		<td style="width:120px;padding:4px;background:#fff;text-align:center;"><strong>'.trad('ref', $this->language).'</strong></td>
		<td style="width:120px;padding:4px;background:#fff;text-align:center;"><strong>'.trad('detalles', $this->language).'</strong></td>
		<td style="width:120px;padding:4px;background:#fff;text-align:center;"><strong>'.trad('precio_de_venta', $this->language).'</strong></td>	
		<td style="width:120px;padding:4px;background:#fff;text-align:center;"><strong>'.trad('enlace', $this->language).'</strong></td></tr>';
		

		$n = 0;
		$thumbnails= [];
		foreach ($favourites as $k => $v) {

			$background		= ($n % 2 == 0) ? '#fff' : '#eaeaea';
			$id 			= $v['id'];
			$referencia  	= $v['referencia'];
			$titulo			= $v['titulo'];
			$img 			= $v['img'];
			$link 			= $v['link'];
			$precio 		= $v['precio'];
			
			$favoritosHTML .= '<tr>
				<td width="120" style="padding:4px;background:'.$background.';text-align:center;width:120px;"><a href="'.$link.'" target="_new">
				<img src="cid:embedded'.$k.'" alt="' . $titulo . '" width="120" />
				</a></td>
				<td width="110" style="padding:4px;background:'.$background.';text-align:center;"><p style="width:110px;">'.$referencia.'</p></td>
				<td width="200" style="padding:4px;background:'.$background.';text-align:left;">
					<p style="">
						<strong>' . $titulo . '</strong> <br/>
						<span style="display: none;">
						' . trad('sup_vivienda') . ': ' . $v['sup_vivienda'] . 'm&sup2; <br/>
						' . trad('sup_parcela') . ': ' . $v['sup_parcela'] . 'm&sup2; <br/>
						</span>
					</p>
				</td>
				<td width="110" style="padding:4px;background:'.$background.';text-align:center;"><p style="width:110px;">'.$precio.'</p></td>		
				<td width="100" style="padding:4px;background:'.$background.';text-align:center;"><p style="width:100px;"><a href="'.$link.'" >'.trad('click').'</a></p></td>
			</tr>';

			$n++;
			
		}
		
		$favoritosHTML .= '</table> <br/>';

		return $favoritosHTML;

	}
	
}



// End of file