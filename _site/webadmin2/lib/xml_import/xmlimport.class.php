<?php
/**
 * xml_import.class.php
 * Copyright (C)2015  Daniel beard <daniel@brunel-encantado.com> 
 * 
 * XML import functions // Default is for Kyero format, any other format must create child
 * 
 */

require_once dirname(__FILE__) . '/../helpers/databasehelpers.class.php';



class XmlImport
{
	 /**
     *
     * @var type 
     */
	protected $url 			= NULL;
	/**
     *
     * @var type 
     */
	protected $agent		= NULL;
	/**
     *
     * @var type 
     */
	protected $localFile	= NULL;
	/**
     *
     * @var type 
     */
	protected $log			= NULL;
	/**
     *
     * @var type 
     */
	public $xml				= NULL;
	/**
     *
     * @var type 
     */
	public $db				= NULL;
	
	public function __construct(array $setup=NULL,logger $log)
	{

		if (empty($setup['url'])) 			throw new Exception('Missing url variable');
		if (empty($setup['agent'])) 		throw new Exception('Missing agent variable');
		
		$this->url			= $setup['url'];
		$this->agent		= $setup['agent'];
		$this->log			= $log;
		$this->localFile	= dirname(__FILE__).'/xml/'.$this->agent.'.xml';

		//Connect to the database
		if ($this->configDatabase()) { $this->log->write(Logger::ERROR,'Database not connected '.$this->localFile); }
		
		// Download XML file, save and put into simpleXml object
		if (pREMOTE==true) $this->getUrl();
		$this->xml = simplexml_load_file($this->localFile,'SimpleXMLElement');
		$this->log->write(Logger::SUCCESS,'Loaded: '.$this->localFile);
		return $this->xml;
	}
	
	/**
	 * Inserts database entry if it doesn't exist, updates if it does.
	 */		
	public function save(array $data,$table)
	{
		$thisId	= $this->entry_exists($data['id_xml']);
		
		if ($thisId) {
			// Update
			$sql = DatabaseHelpers::UpdateQuery($data,$table,array('id_xml' => $data['id_xml'],'agente' => $this->agent));
			$this->query($sql);
			$this->log->write(Logger::INFO,'Updated id_xml = '.$data['id_xml']);
			return $thisId;
		} else {
			// Insert
			$data['agente']		= $this->agent;
			$data['visible']	= 1;
			$sql = DatabaseHelpers::InsertQuery($data,$table);
			$this->query($sql);
			$this->log->write(Logger::INFO,'Added id_xml: '.$data['id_xml']);
			return $this->db->insert_id;
		}
		
	}
	
	 /**
	 * Returns location id, if not exists, creates new location and logs new location
	 */		
	 public function getLocationId($location,$lang) {
		global $xname,$languages;
		$exists		= false;
		
		// Check to see if exists in localidades table
		$localidadesQuery	= "SELECT id FROM {$xname}_localidades WHERE nombre_{$lang} = '{$location}'";
		$localidadesSql		= $this->query($localidadesQuery);
		
		if (is_object($localidadesSql) && $localidadesSql!='') {
			$localidadesRow		= $localidadesSql->fetch_object();
			if (is_object($localidadesRow)) {
				$currentId 			= $localidadesRow->id;
			}
		} else {
			// Check to see if in new_entries table
			$entriesQuery	= "SELECT converted_id FROM {$xname}_new_entries WHERE entry = '{$location}'";
			$entriesSql		= $this->query($entriesQuery);
			
			if ($entriesSql) {
				$entriesRow		= $entriesSql->fetch_object();			
				$currentId 		= $entriesRow->converted_id;
			} 
			
		}
		
		// If it already exists in either localidades or new_entries, we return it, otherwise we enter it into both tables
		if (!empty($currentId)) {
			// Exists, so return id
			return $currentId;
		} else {
			// Add it to table
			$insert	= array();
			foreach ($languages as $l) {
				$insert['nombre_'.$l]	= $location;
			}
			$insertQuery	= DatabaseHelpers::InsertQuery($insert,$xname.'_localidades');
			$this->query($insertQuery);
			$this->log->write(Logger::INFO,'New location: '.$location);
			
			// Now add to new_entries table
			$insert					= array();
			$insert['tabla']		= $xname.'_localidades';
			$insert['entry']		= $location;
			$insert['new_id']		= $this->db->insert_id;
			$insertQuery			= DatabaseHelpers::InsertQuery($insert,$xname.'_new_entries');
			$this->query($insertQuery);
			return $insert['new_id'];
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
		$aRemoteImages	= array();
		foreach ($aImages as $l) {
			$aRemoteImages[]	= $l['url'];
		}
		
		// Local images
		$localImagesQuery	= "SELECT file_name FROM {$xname}_images_viviendas WHERE parent_id = {$id}";
		$localImagesSql		= $this->query($localImagesQuery);
		$aLocalImages = array();
		while ($l = $localImagesSql->fetch_row()) {
			$aLocalImages[] =  $l[0];
		}
		
		$sLocalImages	= serialize($aLocalImages);
		$sRemoteImages	= serialize($aRemoteImages);
		
		// If changed, delete old images and add new ones
		if ($sLocalImages != $sRemoteImages) {
		
			// First delete all images for that entry
			$delImagesQuery	= "DELETE FROM {$xname}_images_viviendas WHERE parent_id = {$id}";
			$this->query($delImagesQuery);
			
			// Add each image to a vivienda
			foreach ($aImages as $k=>$v) {
				$insert					= array();
				$insert['file_name']	= $v['url'];
				$insert['parent_id']	= $id;
				$insert['orden']		= $v['orden'];
				$insert['agente']		= $this->agent;
				$insertImageQuery		= DatabaseHelpers::InsertQuery($insert,$xname.'_images_viviendas');
				$this->query($insertImageQuery);
			}
		$this->log->write(Logger::INFO,'Adding images to id: '.$id);
		}
	 }
	 
	 
	 /**
	 * Deletes database entries that are not in the XML
	 */	
	 public function deleteEntries(){
		 global $xname;
		 
		 
		 // First we get an array of the XML property xml_id's
		 $xmlIdXmlArray = array();
		 foreach ($this->xml->property as $vivienda){
			 $xmlIdXmlArray[] = (string) $vivienda->id;
		 }
		 
		 $databaseIdXmlQuery = "SELECT id_xml FROM {$xname}_viviendas WHERE agente = '{$this->agent}'";
		 $databaseIdSql	 = $this->query($databaseIdXmlQuery);
		 $databaseIdXmlArray = array();
		 while ($databaseIdXml = $databaseIdSql->fetch_row()) {
			 $databaseIdXmlArray[]	=  $databaseIdXml[0];
			 
		 }
			
		 // And we check to see if the database entry is in the XML file, if not, we delete it
		 foreach ($databaseIdXmlArray as $databaseIdXml){
			 if (!in_array($databaseIdXml,$xmlIdXmlArray)) {
				 $this->log->write(Logger::WARNING,'Deleting: '.$databaseIdXml);
				 // Delete entry
				 $deleteQuery = "DELETE FROM {$xname}_viviendas WHERE id_xml = '{$databaseIdXml}'";
				 $this->query($deleteQuery);
			 }
		 }
		 
		 
	 }
	 

	
	 /**
	 * Gets remote url and saves to localFile
	 */		
	protected function getUrl()
	{
		$xmlData = file_get_contents($this->url);
		if (file_put_contents($this->localFile, $xmlData)) {
			$this->log->write(Logger::SUCCESS,'Saved '.$this->localFile);
		}
		
		/* //CURL
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$this->url);
		$this->log->write(Logger::SUCCESS,'Downloaded '.$this->url);
		$fp = fopen($this->localFile, 'w');
		curl_setopt($ch, CURLOPT_FILE, $fp);
		curl_exec ($ch);
		curl_close ($ch);
		fclose($fp);
		$this->log->write(Logger::SUCCESS,'Saved '.$this->localFile);
		*/
	}
	
	
	 /**
	 * Does the entry exist in the database?
	 */	
	protected function entry_exists ($idXml)
	{
		global $xname;
		$query	= "SELECT id,id_xml FROM {$xname}_viviendas WHERE id_xml = {$idXml}";
		$sql	= $this->db->query($query);
		if ($sql) {
			$row	= $sql->fetch_object();
			if (!empty($row->id_xml)) {
				return $row->id;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	 /**
     * @retval TRUE if mysqli objects were created fine, FALSE otherwise
     */	
    protected function configDatabase()
    {
		global $aDATABASE;
		$this->db = new mysqli(
			$aDATABASE[DBMODE]['hostname'], 
			$aDATABASE[DBMODE]['username'], 
			$aDATABASE[DBMODE]['password'], 
			$aDATABASE[DBMODE]['database']);

			// Error handling
			if ($this->db->connect_error) {
				$this->log->write(Logger::ERROR, 'Error on mysqli$tempConf[RUNNING_MODE]::__construct --> (Err_no: ' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
				return FALSE;
			}
	}
	
	 /**
     * Runs query and logs it if pSQL flag true
     */	
	protected function query($query)
	{
		// Print statement
		if (pSQL) {
			$this->log->write(Logger::SQL,$query);
		}
		// Print error
		if ($this->db->error){
			$this->log->write(Logger::ERROR, 'MySql Error (Err_no: ' . $this->db->errno . ') ' . $this->db->error);
		}	
		// Execute query
		if (pLIVE) {
		
			return $this->db->query($query);
		}

		
	}
	
	
}







// End of file