<?php
/**
 * xml_import.class.php
 * Copyright (C)2015  Daniel beard <daniel@brunel-encantado.com> 
 * XML import functions // Default is for Kyero format, any other format must create child
 * 
 */

namespace Brunelencantado\XmlImport;

use \Brunelencantado\Logger\Logger;

class SqlImport extends XmlImport
{

	
	/**
     *
     * @var type 
     */
	protected $agent		= NULL;

	/**
	/**
     *
     * @var type 
     */
	protected $log			= NULL;
	/**
     *
     * @var type 
     */
	public $db				= NULL;
	
	public function __construct(array $setup=NULL,logger $log)
	{

		if (empty($setup['agent'])) 		throw new Exception('Missing agent variable');
		
		$this->agent		= $setup['agent'];
		$this->log			= $log;
		$this->configDatabase();
		
	}
	
	/**
	 * Retrieves remote properties and puts into an array
	 */	
	public function getProperties($table)
	{
		
		$query = "SELECT * FROM {$table}";
		if (is_numeric(pLIMIT)) {
			$query .= " LIMIT ".pLIMIT;
		}
		$sql = $this->query($query);
		return $sql;
	}
	
	/**
	 * Inserts database entry if it doesn't exist, updates if it does.
	 */		
	public function save(array $data,$table)
	{
		$thisId	= $this->entry_exists($data['id_xml']);
		
		if ($thisId) {
			// Update
			$sql = DatabaseHelpers::UpdateQuery($data,$table,array('id_xml' => $data['id_xml']));
			$this->query($sql);
			$this->log->write(Logger::INFO,'Updated id_xml = '.$data['id_xml']);
			return $thisId;
		} else {
			// Insert
			$data['agente']		= $this->agent;
			// $data['visible']	= 1;
			$sql = DatabaseHelpers::InsertQuery($data,$table);
			$this->query($sql);
			$this->log->write(Logger::INFO,'Added id_xml: '.$data['id_xml']);
			return $this->db->insert_id;
		}
		
	}
	 
	// Text is in another table...
	public function getText($PropID)
	{
		global $xname;
		$query = "
					SELECT * FROM
					desc_en en
					JOIN desc_es es
						ON en.PropID = es.PropID
					WHERE en.PropID = '{$PropID}'
					";
		$sql = record($query);
		return $sql;
	}
	
	// Images in another table
	public function getImages($PropID)
	{
		global $xname;
		$query = "SELECT * FROM images WHERE PropID = {$PropID}";
		// printout($query );
		$sql = dataset($query);
		return $sql;
	}
	
	// Images from files
	public function getImagesFromFiles($PropID)
	{
		$folder = dirname(__FILE__).'/propimages/'.$PropID.'/';
		
		if (is_dir($folder)){
			$files = scandir($folder);
			
			$aFiles = array();
			foreach ($files as $file) {
				$aFile = explode('.',$file);
				
				if (!empty($aFile[1])){
					$extension = strtolower($aFile[1]);
				}
				
				if ($file != '.' && $file != '..'&& $file != 'thumbs' && $file != '_vti_cnf' && $extension == 'jpg'){
					$aFiles[] = $file;
				}
			}
			return $aFiles;
			
		} else {
			return false;
		}
	}

	 /**
	 * Enters images from array 
	 */	
	 public function addImages($id, array $aImages)
	 {
		global $xname;
		
		// First check to see if images have changed
		
		// Remote xml images
		$aRemoteImages	= $aImages;

		// Local images
		$localImagesQuery	= "SELECT file_name FROM {$xname}_images_viviendas WHERE parent_id = {$id}";
		$localImagesSql		= $this->query($localImagesQuery);
		$aLocalImages = array();
		while ($l = $localImagesSql->fetch_row()) {
			$aLocalImages[] =  $l[0];
		}
		
		// Serialize to compare as string
		$sLocalImages	= serialize($aLocalImages);
		// $sRemoteImages	= serialize($aRemoteImages);
		$sRemoteImages	= '';
		
		// If changed, delete old images and add new ones
		// if ($sLocalImages != $sRemoteImages) {
		
			// First delete all images for that entry
			$delImagesQuery	= "DELETE FROM {$xname}_images_viviendas WHERE parent_id = {$id}";
			$this->query($delImagesQuery);
			
			// Add each image to a vivienda
			// printout($aImages);
			$n = 1;
			foreach ($aImages as $k=>$v) {
	
	
				// Download and move images
				$origen = dirname(__FILE__).'/propimages/'.$PropID.'/';
				
				if (file_exists($origen)) {

					$insert					= array();
					$insert['file_name']	= $v;
					$insert['parent_id']	= $id;
					$insert['orden']		= $n;
					$insert['agente']		= $this->agent;
					$insertImageQuery		= DatabaseHelpers::InsertQuery($insert,$xname.'_images_viviendas');
					$this->query($insertImageQuery);
					
					$destination = dirname(__FILE__).'/../../../images/viviendas/'.$id;
					$this->downloadImage($origen,$destination,$insert['file_name']);
				} else {
					$this->log->write(Logger::WARNING,'File does not exist: '.$origen);
				}

				$n++;
			}
			

			
		$this->log->write(Logger::INFO,'Adding images to id: '.$id);
		// }
	 }

	protected function downloadImage($origen,$destination,$filename)
	{
		// Set images size
		$giant_image_h = '1600';
		$giant_image_v = '1080';
		$large_image_h = '890';
		$large_image_v = '640';
		$medium_image_h='420';
		$medium_image_v='350';
		$small_image_h='193';
		$small_image_v='160';
		
		if(!file_exists($destination)) { (mkdir($destination,0777));};
		
		pictureresize($origen.'/'.$filename,"$destination/s_$filename",$small_image_h,$small_image_v);
		pictureresize($origen.'/'.$filename,"$destination/m_$filename",$medium_image_h,$medium_image_v);
		pictureresize($origen.'/'.$filename,"$destination/l_$filename",$large_image_h,$large_image_v);
		pictureresize($origen.'/'.$filename,"$destination/g_$filename",$giant_image_h,$giant_image_v);
		
	}
	
	public static function parseOptions($option)
	{
		$output = '';
		switch($option){
			case 'Available':
				$output = 2;
				break;
			
			case 'Yes':
				$output = 1;
				break;
			
			case 'Possible':
				$output = 3;
				break;
		}
		return $output;
	}
}







// End of file