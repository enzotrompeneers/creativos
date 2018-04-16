<?php
/**
 * Calendar render tool
 *
 * Takes into account past dates and reservations
 *
 * @author Daniel Beard <daniel@brunel-encantado.com>
 *
 */

namespace Brunelencantado\Calendar;

use Brunelencantado\Database\DbInterface;

class ReservationCost
{

	const MINIMUM_STAY = 1;
	const MAXIMUM_STAY = 28;

	protected $id;
	protected $data;
	protected $db;

	public $totalCost = 0;
	public $rentCost = 0;
	public $extrasCost = 0;
	public $depositCost = 250;
	public $allCorrect;
	protected $fecha_llegada;
	protected $fecha_salida;
	protected $hora_llegada;
	protected $hora_salida;
	protected $personas;
	protected $bebes;
	protected $animales;
	
	protected $extras = array();
	
	protected $totalDays;
	protected $totalPrices;
	
	public $error;


	// Constructor
	public function __construct(array $data, DbInterface $db){
		
		
		$this->data = filter_var_array($data, FILTER_SANITIZE_STRING);;
		$this->db = $db;
		$this->id = $data['id'];
	
		$this->fecha_llegada	= $data['fecha_llegada'];
		$this->fecha_salida		= $data['fecha_salida'];
		$this->hora_llegada		= $data['hora_llegada'];
		$this->hora_salida		= $data['hora_salida'];
		$this->personas			= $data['personas']; // + $data['ninos'];
		// $this->bebes			= $data['bebes'];


		
		// Need to validate dates
		if (!$this->validateDate($this->fecha_llegada) || !$this->validateDate($this->fecha_salida) || !is_numeric($this->personas)) {
			$this->error =  trad('error_incorrecto');
		}

		if (!$this->correctSequence()) {
			$this->error =  trad('error_incorrecto');
		}
		
		// All is well!
		if (!$this->error) {
			$this->getData(); 		// Process data	and get basic rental price
			$this->rentCost			= (is_array($this->totalPrices))?ceil(array_sum($this->totalPrices)):0;
			$this->depositCost		= $this->getDeposit(); // Deposit 

			$this->totalCost		= $this->rentCost; // rental cost
			// $this->outOfHours();	// Out of hours extra?
			$this->extrasCost		= $this->calculateExtras(); // Get extras cost
			$this->totalCost		+= $this->extrasCost; // Total
		}
		
	}

	// Sets the sequence in action
	protected function getData(){
		
		// Get dates as an array
		$dateArray			= $this->dateRange('d/m/Y');
		$this->totalDays	= count($dateArray);
		
		// Minimum stay
		if ($this->totalDays < self::MINIMUM_STAY) {

			$this->error =  trad('error_estancia_minima');

		}

		// Maximum stay
		if ($this->totalDays > self::MAXIMUM_STAY) {

			$this->error =  trad('error_estancia_maxima');

		}

		// Walk through dates to get prices
		foreach ($dateArray as $k => $v) {

			if (!$this->dateAvailable($this->isoDate($v, '/'))) {

				$this->error = trad('error_fecha_no_disponible');

			}

			$season	= $this->getSeason($this->isoDate($v, '/'));
			$season = ($season == 'autumn_season' || $season == 'spring_season') ? 'mid_season' : $season;
			
			$prices	= $this->getDayPrices($this->isoDate($v, '/'));
			$specialPrice = $this->getSpecialPrice($this->isoDate($v, '/'));
			
			$weekPrice = $prices[$season];
			$weekPrice = ($specialPrice) ? $specialPrice : $weekPrice;

			$dayPrice = $weekPrice / 7;
			$this->totalPrices[] = $dayPrice;
		}
	
	}

	// Make sure departure later than arrival
	protected function correctSequence() {

		$dateFromObject		= \DateTime::createFromFormat('d/m/Y', $this->fecha_llegada);
		$dateToObject		= \DateTime::createFromFormat('d/m/Y', $this->fecha_salida);

		if ($dateFromObject>$dateToObject) {

			return false;

		}

		return true;
	}
	
	// Make sure valid dates and not in past
	protected function validateDate($date) {

		$d 			= \DateTime::createFromFormat('d/m/Y', $date);
		$today		= date('d/m/Y');
		$t			= \DateTime::createFromFormat('d/m/Y', $today);

		if (!$date || $d < $t) {

			$this->error =  trad('error_pasado');

			return;

		}

		return $d->format('d/m/Y') . $date;
	}
	
	// Get date array
	protected function dateRange($outputFormat = 'Y-m-d', $step = '+1 day') {
		$dates 		= array();
		$current 	= strtotime(str_replace('/', '-', $this->fecha_llegada));
		$last 		= strtotime(str_replace('/', '-', $this->fecha_salida));
		
		while ($current<$last) {
			$dates[] 	= date($outputFormat, $current);
			$current 	= strtotime($step, $current);
		}
		
		return $dates;
	}



	// Get season from date
	protected function getSeason($date) {

		$query = "SELECT clave FROM ".XNAME."_temporadas WHERE '{$date}' BETWEEN fecha_comienzo AND fecha_fin";
		$sql = record($query);
		return $sql['clave'];

	}
	
	// Get daily price for each day
	protected function getDayPrices($date) {
		global $xname,$language;
		$season							= $this->getSeason($date);
		$query							= "	SELECT precio_temp_alta, precio_temp_media, precio_temp_baja
											FROM {$xname}_viviendas 
											WHERE id = {$this->id} ";
		$sql							= record($query);
	
		$response						= array();
		$response['high_season']		= $sql['precio_temp_alta'];
		$response['mid_season']			= $sql['precio_temp_media'];
		$response['low_season']			= $sql['precio_temp_baja'];
		// printout($response);
		return $response;
	}
	
	// Get special season price if applies
	protected function getSpecialPrice($date)
	{

		$query = "SELECT temporadas_json FROM ".XNAME."_viviendas WHERE id = {$this->id}";
		$sql = $this->db->record($query);

		$specialDates = json_decode($sql['temporadas_json']);

		if ($specialDates) {
			
			foreach ($specialDates as $dateRange) {
				
				if ($date >= $dateRange->fechaComienzo && $date <= $dateRange->fechaFin) {
					
					return $dateRange->precio;
		
				}
	
			}

		}

	}

	// Get deposit for this property
	protected function getDeposit()
	{
		$query = "SELECT deposito FROM ".XNAME."_viviendas WHERE id = {$this->id}";
		$sql = record($query);

		$deposito = ($sql['deposito']) ? $sql['deposito'] : $this->depositCost;

		return $deposito;
	}
	
	// Calculate extras
	protected function calculateExtras()
	{
		
		$selectedExtras = $this->getAllExtras();
		$extrasCosts = $this->getExtrasCostsThisProperty();
		$extrasInfo = $this->getExtrasInfo();

		$extrasTotal = 0;
		
		foreach ($selectedExtras as $key => $extra){

			$extraCost = $extrasCosts[$extra];

			$thisExtra = $extrasInfo[$extra];
			
			if ($thisExtra['no_contabilizar'] == 1) continue;

			if ($thisExtra['por_dia'] == 1) $extraCost = $extraCost * $this->totalDays;
			if ($thisExtra['por_semana'] == 1) $extraCost = $extraCost * $this->totalDays / 7;
			if ($thisExtra['por_persona'] == 1) $extraCost = $extraCost * $this->personas;
			if ($thisExtra['por_bebe'] == 1) $extraCost = $extraCost * $this->bebes;
			if ($thisExtra['por_animal'] == 1) $extraCost = $extraCost * $this->animales;
			
			$extrasTotal += $extraCost;
	
		}

		return $extrasTotal;
		
	}

	// Get extras from post
	protected function getAllExtras()
	{

		$extras = [];
		
		foreach ($this->data as $k => $v) {

			if (mb_substr($k, 0, 6) == 'extra_') {
				
				$aExtra = explode('_', $k);

				// Jump the security deposit as it should not count in the final price
				if ($aExtra[1] == 6) continue; 

				$extras[] = $aExtra[1];

			}

		}

		return $extras;
		
	}

	// Get array for all extras costs
	protected function getExtrasCostsThisProperty()
	{

		$costQuery = "SELECT extras_json FROM ".XNAME."_viviendas WHERE id = {$this->id}";
		$costSql = $this->db->record($costQuery);
		$oCosts = json_decode($costSql['extras_json']);
		$aCosts = [];

		if ($oCosts) {
			foreach($oCosts as $cost) {
				$aCosts[$cost->id] = $cost->value;
			}
		}


		return ($aCosts);

	}

	// Get extras type
	protected function getExtrasInfo()
	{

		$query = "SELECT * FROM ".XNAME."_extras";
		$sql = $this->db->dataset($query);

		$extras = [];

		foreach ($sql as $k => $v) {

			$extras[$v['id']] = $v;

		}

		return $extras;

	}
	
	// Is date available?
	protected function dateAvailable($date)
	{
		$query = "	SELECT vivienda_id 
					FROM ".XNAME."_reservas  
					WHERE '{$date}' BETWEEN fecha_llegada AND fecha_salida 
					AND vivienda_id = {$this->id} 
					AND confirmado = 1 
				";

		$sql = record($query);

		if (!$sql) return true;

	}
	
	// Do we have an extras cost?
	protected function outOfHours()
	{
		// validate times
		if (!$this->isTime($this->hora_llegada) || !$this->isTime($this->hora_salida)){
			$this->error =  trad('error_hora');
		}
		
		// Form data
		$aCheckIn 	= explode(':', $this->hora_llegada);
		$aCheckOut 	= explode(':', $this->hora_salida);
		$checkIn 	= new \DateTime();
		$checkOut 	= new \DateTime();
		$checkIn->setTime($aCheckIn[0], $aCheckIn[1]);
		$checkOut->setTime($aCheckOut[0], $aCheckOut[1]);
		
		// Limit times
		$checkInLimit 	= new \DateTime();
		$aCheckInLimit 	= explode(':', webconfig('check_in_limit'));
		$checkInLimit->setTime($aCheckInLimit[0], $aCheckInLimit[1]);
		$checkOutLimit 	= new \DateTime();
		$aCheckOutLimit = explode(':', webconfig('check_out_limit'));
		$checkOutLimit->setTime($aCheckOutLimit[0], $aCheckOutLimit[1]);
		
		// Add extra cost if out of hours
		if ($checkIn >= $checkInLimit) {
			$this->extras['out_of_hours_checkin'] = webConfig('out_of_hours');
		}
		if ($checkOut <= $checkOutLimit) {
			$this->extras['out_of_hours_checkout'] = webConfig('out_of_hours');
		}
	}
	
	// is valid time?
	protected function isTime($time)
	{
		if (preg_match("/(2[0-3]|[01][0-9]):([0-5][0-9])/", $time))
		{

			return true;
			
		}
	}

	// Get date in ISO format
	protected function isoDate($date, $delimiter='/') {

		$dateArray			= explode($delimiter, $date);
		$output				= $dateArray[2] . '-' . $dateArray[1] . '-'.$dateArray[0];

		return $output;

	}
	


}









// End file