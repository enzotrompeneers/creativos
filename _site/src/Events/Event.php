<?php
/**
 * Event class
 *
 * First set up for Huis Onder de Zon
 *
 * @author Daniel Beard / BE Creativos <daniel@creativos.be>
 */

namespace Brunelencantado\Events;

use Brunelencantado\Database\DbInterface;

class Event
{

	protected $db;
	protected $table = 'eventos';
	protected $id;

	/**
	 * Constructor
	 *
	 * @param DbInterface $db
	 */
	public function __construct(DbInterface $db)
	{
		$this->db = $db;
	}
	
    /**
     * @brief Gets data for latest event
     * 
     * @return Array
     */
	public function getLatestEvent()
	{
		
		
		$data = $this->getData();
		
		if ($data['titulo'] == '') return false;
		
		$data['img'] = 'images/eventos/' . $data['id']. '/m_' . $data['file_name'];
		
		$output = $data;
		
		return $output;
		
	}
	
    /**
     * @brief Gets data by ID
     * 
	 * @param Integer $id
     * @return Array
     */
	public function getEvent($id)
	{
		
		$this->id = $id;
		
		$output = $this->getData($id);
		
		$output['images'] = $this->getImages();
		$output['files'] = $this->getFiles();
		
		return $output;
		
	}
	
	/**
	 * @brief Gets data from database
	 *
	 * @param Integer $id
	 * @return Array
	 */
	protected function getData($id = null)
	{
		
		$filter = ($id) ? " WHERE id = {$id} " : " WHERE DATE(NOW()) between fecha_desde and fecha_hasta AND main.titulo_".LANGUAGE." != ''";
 		
		// Image query
		$imageQuery = "
						, (SELECT img.file_name as file_name
						FROM ".XNAME."_images_{$this->table} img
						WHERE img.parent_id = main.id
						ORDER BY img.orden ASC
						LIMIT 1) AS file_name";
		
		$query = "
					SELECT id, titulo_".LANGUAGE." AS titulo,
					intro_".LANGUAGE." AS intro,
					descr_".LANGUAGE." AS descr
					{$imageQuery}
					FROM ".XNAME."_{$this->table} main
					{$filter}
					ORDER BY fecha_desde ASC 
					LIMIT 1";
		
		$sql = $this->db->record($query);
		
		return $sql;
		
	}
	
	/**
	 *  @brief Gets all images related to an article
	 *  
	 *  @param String $size Can be 'g', 'l', 'm' or 's'. Default 'l'
	 *  @return Array
	 */
	protected function getImages($size = 'l')
	{
		$sql = $this->getImagesData($this->id);
		
		$output = array();
		foreach ($sql as $k => $v){
			$output[] = 'images/eventos/' . $this->id . '/' . $size . '_' . $v['file_name'];
		}
		
		return $output;
	}
	
	
	/**
	 *  @brief Gets all files related to an article
	 *  
	 *  @return Array
	 */
	protected function getFiles()
	{
		$id = $this->id;
		$sql = $this->getFilesData($id);

		$output = array();
		foreach ($sql as $k => $v){
			
			$output[$k]['path'] = 'images/eventos/' . $this->id . '/' . $v['file_name'];
			$output[$k]['extension'] = $this->getExtension($v['file_name']);
			
		}
		
		return $output;
	}
	
	/**
	 *  @brief Gets array of images for a given id
	 *  
	 *  @param Integer $id Id of article
	 *  @return Array
	 */
	protected function getImagesData($id)
	{
		$query = "SELECT file_name FROM ".XNAME."_images_eventos WHERE parent_id = {$id} ORDER BY orden";
		return $this->db->dataset($query);
	}

	
	/**
	 *  @brief Gets array of files for a given id
	 *  
	 *  @param Integer $id id of article
	 *  @return Array
	 */
	protected function getFilesData($id)
	{
		$query = "SELECT file_name FROM ".XNAME."_files_eventos WHERE parent_id = {$id} ORDER BY orden";
		$sql = $this->db->dataset($query);

		return $sql;
		
	}
	
	/**
	 * @brief Gets file extension
	 *
	 * @param String $fileName
	 * @return String
	 */
	protected function getExtension($fileName)
	{
		
		$pathInfo = pathinfo($fileName);
		
		return $pathInfo['extension'];
		
	}
}


// End of file