<?php
/**
 * News list class
 *
 * @author Daniel Beard / BE Creativos <daniel@creativos.be>
 */

namespace Brunelencantado\News;

use Brunelencantado\Database\DbInterface;
use Brunelencantado\Content\Menu;

class NewsList
{

	use \Brunelencantado\Pagination\PaginationTrait;

	protected $db;
	protected $menu;
	protected $table = 'noticias';
	protected $hideFuturePosts = true;

	protected $genericListPage = 'noticias';
	protected $descriptionCharacters = 100;

	/**
	 * @brief Constructor
	 *
	 * @param DbInterface $db
	 * @param Menu $menu
	 * @return Void
	 */
	public function __construct(DbInterface $db, Menu $menu)
	{

		$this->db = $db;
		$this->menu = $menu;

	}

	/**
	 * @brief Get list of news posts
	 *
	 * @param Integer $limit
	 * @return Array List of posts
	 */
	public function getList($limit = null)
	{
		
		$data = $this->getData($limit);

		$output = [];

		foreach ($data as $k => $v){

			$output[$k] = $v;
			$output[$k]['descripcion'] = shorten_text($output[$k]['descripcion'], $this->descriptionCharacters, true);

			$output[$k]['link'] = $this->menu->getUrl($this->genericListPage) . slug($v['titulo']) .  '-' .$v['id'] . '.html';

		}

		return $output;

	}
	
	/**
	 * @brief Gets the goodies from the database
	 *
	 * @param Integer $limit
	 * @return Array
	 */
	protected function getData($limit = null)
	{
		
		// Show future posts?
		$noFuture = ($this->hideFuturePosts) ? " WHERE fecha <= '" . date('Y-m-d') . "'" : ""; 

		// Image query
		$imageQuery = "
		, (SELECT img.file_name as file_name
		FROM ".XNAME."_images_{$this->table} img
		WHERE img.parent_id = main.id
		ORDER BY img.orden ASC
		LIMIT 1) AS img";

		// Main query
		$query = "	SELECT id,
					titulo_".LANGUAGE." AS titulo,
					descr_".LANGUAGE." AS descripcion,
					fecha
					{$imageQuery}
					FROM ".XNAME."_{$this->table} main
					{$noFuture} 
					ORDER BY fecha DESC
					";

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

		$sql = $this->db->dataset($query);

		return $sql;
		
	}

	/**
	 * @brief Sets number of characters to show on short description
	 *
	 * @param Integer $chars
	 * @return Void
	 */
	public function setDescriptionCharacters($chars)
	{

		$this->descriptionCharacters = $chars;

	}
}


// End of file