<?php
/**
 * Rental booking class
 *
 * @author Daniel Beard <daniel@creativos.be>
 *
 */

namespace Brunelencantado\Calendar;

use Brunelencantado\Database\DbInterface;

class Booking {
	
	protected $db;
	protected $propertyRef;
	protected $extras;

	protected $table = 'reservas';
	protected $error = [];
	
	/**
	 * @brief Constructor
	 *
	 * @param DbInterface $db
	 */
	public function __construct(DbInterface $db){

		$this->db = $db;

	}
	
	/**
	 * @brief Saves booking to database
	 *
	 * @param Array $data
	 * @param Integer $clientId
	 * @return Integer New booking id
	 */
	public function save(array $data, $clientId)
	{

		$data = filter_var_array($data, FILTER_SANITIZE_STRING);

		$booking = [];
		$booking['cliente_id'] = $clientId;
		$booking['vivienda_id'] = $data['id'];
		$booking['fecha_llegada'] = $this->isoDate($data['fecha_llegada']);
		$booking['fecha_salida'] = $this->isoDate($data['fecha_salida']);
		$booking['hora_llegada'] = $data['hora_llegada'];
		$booking['hora_salida'] = $data['hora_salida'];
		// $booking['adultos'] = $data['adultos'];
		// $booking['ninos'] = $data['ninos'];
		// $booking['personas'] = $data['adultos'] + $data['ninos'];
		// $booking['bebes'] = $data['bebes'];
		$booking['personas'] = $data['personas'];

		$booking['fecha_creado'] = date('Y-m-d');
		$booking['fecha_modificado'] = date('Y-m-d');
		$booking['ip'] = $_SERVER['REMOTE_ADDR'];
		$booking['hash'] = md5(uniqid(mt_rand(), true));
		$booking['confirmado'] = 0;

		$booking['coste_alquiler'] = $data['coste_alquiler'];
		$booking['coste_extras'] = $data['coste_extras'];
		$booking['deposito'] = $data['deposito'];
		$booking['total'] = $booking['coste_alquiler'] + $booking['coste_extras'];

		$booking['mensaje'] = $data['mensaje'];
		$booking['extras'] = $this->getExtras($data);

		$newId = $this->db->insertQuery($booking, XNAME . '_' . $this->table);

		return $newId;

	}

	/**
	 * @brief Returns errors in json format
	 *
	 * @return Object json
	 */
	public function jsonErrors()
	{

		$errors = array();

		$errors['errors'] = $this->error;
		$errors['message'] = 'error';

		header('Content-Type: application/json');

		return json_encode($errors);

	}
	
	/**
	 * @brief Gets property reference from id
	 *
	 * @param Integer $id
	 * @return Array
	 */
	public function getPropertyRef($id) {

		$query		= "SELECT referencia FROM ".XNAME."_viviendas WHERE id = {$id}";
		$sql		= $this->db->record($query);
		
		return $sql['referencia'];

	}


	/**
	 * @brief Gets list of extra names
	 *
	 * @param Array $data
	 * @return Array
	 */
	public function getExtras($data)
	{

		$extras = '';

		foreach ($data as $k => $v) {

			if ($this->isExtra($k)) {

				// unset($this->data[$k]);
				$extras .= $this->getExtraName($k) . "\n\r";

			}

		}

		return $extras;

	}

	/**
	 * @brief gets rid of extras fields
	 *
	 * @param Array $data
	 * @return Array
	 */
	public function purgeExtras($data)
	{

		foreach ($data as $k => $v) {

			if ($this->isExtra($k)) {

				unset($data[$k]);
				
			}

		}

		return $data;

	}

	protected function isExtra($name)
	{
		
		if (mb_substr($name, 0, 6, 'utf-8') == 'extra_') return true;

	}
	
	protected function getExtraName($name)
	{

		$extraId = 	ltrim($name, 'extra_');

		if (empty($this->extras)){

			$query = "SELECT * FROM ".XNAME."_extras";
			$sql = $this->db->dataset($query);

			$extras = [];

			if ($sql){

				foreach($sql as $k => $v){

					$extras[$v['id']] = $v['nombre_' . LANGUAGE];

				}

			$this->extras = $extras;

			}

		}

		return $this->extras[$extraId];

	}

	// Get date in ISO format
	protected function isoDate($date, $delimiter='/') {

		$dateArray			= explode($delimiter, $date);
		$output				= $dateArray[2] . '-' . $dateArray[1] . '-'.$dateArray[0];

		return $output;

	}	

    /**
     * @brief Table setter
     *
     * @param String $table
     * @return Void
     */
    public function setTable($table)
    {

        $this->table = $table;

    }
	
}