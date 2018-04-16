<?php
/**
 * News list class
 *
 * @author Daniel Beard / BE Creativos <daniel@creativos.be>
 */

namespace Brunelencantado\News;

use Brunelencantado\Database\DbInterface;

class NewsDetail
{


	protected $db;
	protected $id;
    protected $table = 'noticias';
    protected $data;


	/**
	 * @brief Constructor
	 *
	 * @param DbInterface $db
	 * @return Void
	 */
	public function __construct(DbInterface $db, $id = null)
	{

		$this->db = $db;
		$this->id = $id;

	}

    /**
	 *  @brief Gets post details from the database and formats
	 *  
	 *  @return Array
	 */
    public function getDetails()
    {

        $data = $this->getData();

        $data['images'] = $this->getImages();

        $this->data = $data;

        return $data;

    }

	/**
	 * Gets the goodies from the database
	 *
	 * @param Integer $data
	 * @return Array
	 */
	protected function getData()
	{

        $where = ($this->id) ? " WHERE id = {$this->id}" : "";

		// Main query
		$query = "	SELECT id, fecha,
					titulo_".LANGUAGE." AS titulo,
					descr_".LANGUAGE." AS descripcion
					FROM ".XNAME."_{$this->table} main
					{$where}
                    ORDER BY fecha DESC
                    LIMIT 1
					";

		$sql = $this->db->record($query);

		return $sql;
		
    }
    
	/**
	 *  @brief Gets array of images
	 *  
	 *  @return Array [ 'g', 'l', 'm', 's' ]
	 */
	protected function getImages()
	{
		$query = "SELECT parent_id, file_name FROM ".XNAME."_images_{$this->table} WHERE parent_id = {$this->id} ORDER BY orden";
		$sql = $this->db->dataset($query);
		
		$output = array();
		$n = 0;
		$path = BASE_SITE . 'images/' . $this->table . '/' . $this->id;
		
		foreach ($sql as $k => $v) {

			$isLocal = (mb_substr($v['file_name'], 0, 4) == 'http');
			
			$output[$n]['g'] = ($isLocal) ? $v['file_name'] : $path . '/g_' . $v['file_name'];
			$output[$n]['l'] = ($isLocal) ? $v['file_name'] : $path . '/l_' . $v['file_name'];
			$output[$n]['m'] = ($isLocal) ? $v['file_name'] : $path . '/m_' . $v['file_name'];
			$output[$n]['s'] = ($isLocal) ? $v['file_name'] : $path . '/s_' . $v['file_name'];
			
			$n++;
		}
		
		return $output;
	}

    /**
	 * @brief Gets last portion of link, translated
	 *
	 * @param String $language
	 * @return String
	 */
	public function getTranslatedLink($language)
	{

		return slug($this->data['titulo']) . '-' . $this->data['id'] . '.html';

    }
    
}


// End of file