<?php
/**
 * Tools to load and convert CSV files
 *
 * @author Daniel Beard <daniel@creativos.be>
 */

namespace Brunelencantado\Csv;

use Brunelencantado\Database\DbInterface;

class CsvImport
{
	
	protected $file; // File location
	protected $map = array(); // Array that maps CSV index/field to DB field
	protected $table;
	protected $db;

	/**
	 * @brief Constructor
	 *
	 * @param String $file File path
	 * @param String $table Table without prefix
	 * @param Array $map
	 * @param DbInterface $db
	 * 
	 * @return Void
	 */
	public function __construct($file, $table, array $map, DbInterface $db)
	{
		$this->file =$this->loadFile($file);
		$this->table = $table;
		$this->map = $map;
		$this->db = $db;
	}
	
	
	protected function convert()
	{
		global $xname;
		
		// Prepare data
		while (!feof($this->file)){
			$row = fgetcsv($this->file, 1024, ',');
			
			$data = array();
			foreach ($this->map as $k=>$v){
				$value = $row[$k];
				$type = $v[1];
				
				if ($type == 'date') $value = $this->getIsoDate($value);
				
				$data[$v[0]] = $value;
			}
			
			// Save data
			$this->save($data, 'matricula');
			
		}
		
	}
	
	/**
	 *  @brief Loads file and returns resourece
	 *  
	 *  @param String $file path of file to load
	 *  @return Resource
	 */
	protected function loadFile($file)
	{
		return fopen($file, 'r');
	}
	
	/**
	 *  @brief con
	 *  
	 *  @param [in] $date Date in semi-written format
	 *  @return Date in ISO format
	 *  
	 */
	protected function getIsoDate($date)
	{
		if (!preg_match('/^[0-9]{1,2}-[A-Za-z]{3}-[0-9]{4}$/', $date)) return $date;

		
		$aDate = explode('-', $date);
		$months = array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
		
		$day = $aDate[0];
		$month = array_search($aDate[1], $months) + 1;
		$year = $aDate[2];
		
		$isoDate = $year . '-' . $month . '-' . $day;
		return $isoDate;
	}
	
	/**
	 *  @brief Saves entry to database. Inserts if doesn't exist, or updates if does
	 *  
	 *  @param String $data Data to insert/update
	 *  @return Void
	 *  
	 */
	protected function save($data, $field)
	{
		
		$data['fecha_creado'] = date('Y-m-d');
		$data['fecha_modificado'] = date('Y-m-d');
		
		if ($this->entryExists($data[$field])){
			$query = $this->db->updateQuery($data, $this->table, array($field = $data[$field]));
		} else {
			$query = $this->db->insertQuery($data, $this->table);
		}
	}
	
	/**
	 *  @brief Test to see if matricula is in db
	 *  
	 *  @param [in] $matricula Licence plate
	 *  @return Boolean
	 *  
	 */
	protected function entryExists($matricula)
	{
		global $xname;
		$query = "SELECT id FROM {$xname}_vehiculos WHERE matricula = '{$matricula}'";
		$sql = $this->db->record($query);
		return $sql['id'];
	}
}
	
// End of file