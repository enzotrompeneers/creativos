<?php
/**
 * Quicklinks listing class
 *
 * @author Daniel Beard / BE Creativos <daniel@creativos.be>
 */

namespace Brunelencantado\Quicklinks;

use Brunelencantado\Database\DbInterface;
use Brunelencantado\Content\Menu;

class Quicklinks
{

	protected $db;
	protected $menu;
	protected $data = [];
	protected $plural = 's';

	public $rows = 0;

	/**
	 * @brief Constructor
	 *
	 * @param DbInterface $db
	 * @param Menu $menu
	 */
	public function __construct(DbInterface $db, Menu $menu)
	{

		$this->db = $db;
		$this->menu = $menu;

	}

	/**
	 * @brief Lists quicklinks
	 *
	 * @param Integer $limit
	 * @return Array
	 */
	public function getList($limit = null)
	{
		
		$data = $this->getData();

		$output = [];
		foreach($data as $k => $v) {
			
			$output[$k] = $v;
			$output[$k]['title'] = $v['tipo'] . ' ' . trad('en') . ' ' . $v['localidad'];
			$output[$k]['link'] = $this->menu->getUrl('viviendas') . slug($output[$k]['title']) . '/' . $v['id'] . '/';

		}

		return $output;

	}
	
	/**
	 * @brief Gets and returns filters for this quicklink by ID
	 *
	 * @param Integer $id
	 * @return Array
	 */
	public function getFilters($id)
	{

		$thisQuicklink = '';	
		foreach ($this->data as $k => $v){

			if ($v['id'] == $id) {

				$thisQuicklink = $v;
			}

		}

		if (!$thisQuicklink) return;

		$filters = [];
		$filters['tipo_id'] = $thisQuicklink['tipo_id'];	
		$filters['localidad_id'] = $thisQuicklink['localidad_id'];	
		
		return $filters;

	}

	/**
	 * @brief Gets quicklink data from database
	 *
	 * @param Integer $limit
	 * @return Array
	 */
	protected function getData($limit = null)
	{
		
		$limitSql = ($limit) ? " LIMIT {$limit} " : "";
		
		$query = "
			SELECT qui.id AS id, tipo_id, localidad_id,
			CONCAT (tip.nombre_".LANGUAGE.", '{$this->plural}') AS tipo,
			loc.nombre AS localidad
			FROM ".XNAME."_quicklinks qui
			JOIN ".XNAME."_tipos tip
			ON qui.tipo_id = tip.id
			JOIN ".XNAME."_localidades loc
			ON qui.localidad_id = loc.id
			ORDER BY qui.orden
			{$limitSql}
			";
		
		$sql = $this->db->dataset($query);

		$this->data = $sql;
		$this->rows = $this->db->rows;
		
		return $sql;
		
	}
}


// End of file