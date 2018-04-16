<?php
/**
 * Price: Calculates prices from a form
 *
 * @author Daniel Beard <daniel@brunel-encantado.com>
 */

namespace Brunelencantado\Price;

class Calculator
{
	
	
	protected $fechaLlegada = null;
	protected $horaLlegada = null;
	protected $fechaSalida = null;
	protected $horaSalida = null;
	protected $vehiculoGrande = null;
	protected $dias = 0;
	protected $precio = 0;	
	protected $aeropuertoSalida = null;
	protected $aeropuertoLlegada = null;
	protected $camposVacios = array();
	protected $extrasArray = array();
	protected $extras = array();
	protected $errores = array();

	
	/**
	 * Create a new Instance
	 */
	public function __construct($fechaSalida,$horaSalida,$fechaLlegada,$horaLlegada,$vehiculoGrande,$extrasArray)
	{
		$this->fechaSalida		= \DateTime::createFromFormat('d/m/Y', $fechaSalida);
		$this->horaSalida		= $horaSalida;
		$this->fechaLlegada		= \DateTime::createFromFormat('d/m/Y', $fechaLlegada);
		$this->horaLlegada		= $horaLlegada;
		$this->vehiculoGrande	= ($vehiculoGrande=='true')?true:false;
		$this->extrasArray		= $extrasArray;
	}
	
	/**
	* Set airports
	*/
	public function setAeropuerto($tipo,$aeropuerto)
	{
		if ($tipo=='salida'){
			$this->aeropuertoSalida = $aeropuerto;
		} else {
			$this->aeropuertoLlegada = $aeropuerto;
		}
		
	}
	
	/*
	* Pass all validation tests
	*/
	 public function validate()
	 {
		// Departure details
		if (empty($this->aeropuertoSalida)) $this->camposVacios[] 	= trad('aeropuerto_salida');
		if (empty($this->fechaSalida)) 	$this->camposVacios[] 		= trad('fecha_salida');
		if (empty($this->horaSalida)) 	$this->camposVacios[] 		= trad('hora_salida');
		
		// Arrival details
		if (empty($this->aeropuertoLlegada)) $this->camposVacios[] 	= trad('aeropuerto_llegada');
		if (empty($this->fechaLlegada)) $this->camposVacios[] 		= trad('fecha_llegada');
		if (empty($this->horaLlegada)) 	$this->camposVacios[] 		= trad('hora_llegada');
		
		if (empty($this->camposVacios)){
			 // Validate dates and times
			 $this->validate_date($this->fechaSalida);
			 $this->validate_date($this->fechaLlegada);
			 $this->validate_time($this->horaSalida);
			 $this->validate_time($this->horaLlegada);
			 $this->validate_dates();
		 }
	 }
	 
	 /*
	 * Calculate the price based on tarifas table
	 */
	 public function calculate()
	 {
		global $language,$xname;
		
		if (empty($this->camposVacios) && empty($this->errores)) {
			$daysObject			= $this->fechaSalida->diff($this->fechaLlegada); 
			$this->dias			= $daysObject->format('%a');
			// Extra day if time difference bigger than MAX_TIME_DIFF
			if ($this->time_difference()>MAX_TIME_DIFF){
				$this->dias++;
			}
			// Dropping off and picking up counts as 1 day
			if ($this->dias<1){
				$this->dias = 1;
			}
			$tipoPrecio			= ($this->vehiculoGrande===true)?'precio_superior':'precio_standard';
			$query				= "	SELECT dias,{$tipoPrecio}
									FROM {$xname}_tarifas 
									WHERE dias	= {$this->dias}
									ORDER BY dias DESC
									";
			$sql				= record($query);
			$this->precio		= $sql[$tipoPrecio];
			
			//** Extras **//
			
			// Transfer
			if ($this->differentAirport()) {
				$this->precio += TRANSFER_FEE;
				$this->extras[] = trad('transferencia_aeropuertos').'</strong> ('.TRANSFER_FEE.' &euro; '.trad('anadido').')';
			}
			
			// Large vehicle
			if ($this->vehiculoGrande===true) $this->extras[] = trad('vehiculo_grande');
			
			// Time difference  = extra day
			if ($this->time_difference()>MAX_TIME_DIFF) $this->extras[] = trad('dia_extra_diff_tiempo');			
			
			// Other selectable extras
			foreach ($this->extrasArray as $k=>$v){
				if (strtolower($v)=='true') {
					$this->precio += webConfig($k);
					$this->extras[] = trad($k).' ('.webConfig($k).' &euro;)';
				}
				
			}
			

			

		}
	 }	 
	 
	/**
	* Make sure times are correct and formatted
	*/
	protected function validate_time ($time)
	{
		$timePattern	= '/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/';
		if (!preg_match($timePattern,$time)) {
			$this->errores[]	= trad('error_formato').': '.$time;
			return false;
		} 
		return $time;
	}
	
	/**
	* Make sure valid dates and not in past
	*/
	protected function validate_date($date) {
		$d = $date;
		
		// Valid date?
		if (!$d) 	{
			$this->errores[]	= trad('error_formato').': '.$date;
			return false;
		}
		
		// Is it in the past?
		$today		= date('d/m/Y');
		$t			= \DateTime::createFromFormat('d/m/Y', $today);
		if ($d<$t) {
			$this->errores[]	= trad('error_pasado');
			return false;
		}
		return $d && $d->format('d-m-Y') == $date;
	}

	/**
	* Make sure dates are in correct order
	*/
	protected function validate_dates()
	{
		if ($this->fechaSalida > $this->fechaLlegada) {
			$this->errores[]	= trad('error_fechas');
		}
		
	}	
	
	/**
	* Get time difference
	*/
	protected function time_difference()
	{
		$inTime 	= strtotime($this->horaSalida);
		$outTime 	= strtotime($this->horaLlegada);
		$diff 		= $outTime-$inTime;
		$diffHours 		= $diff/3600;
		return $diffHours;
		
	}
	
	
	/**
	* Get airport data
	*/
	protected function get_airport_name($id)
	{
		global $language,$xname;
		$query = "SELECT nombre_{$language} AS nombre FROM {$xname}_parkings WHERE id = {$id}";
		
		$sql = record($query);
		return $sql['nombre'];
	}
	
	/**
	* Check to see if same airport
	*/
	protected function differentAirport()
	{
		if ($this->aeropuertoSalida!==$this->aeropuertoLlegada) {
			return true;
		}
		return false;
		
	}
	
	/**
	* Render final price and details
	*/
	public function __toString() 
	{
		$messages = false;
		$messageList = '<ul>';
		
		// Empty field messages
		foreach ($this->camposVacios as $m){
			$messageList .= '<li>'.$m.'</li>';
			$messages = true;
		}
		
		// Bad data fields
		foreach ($this->errores as $e){
			$messageList .= '<li>'.$e.'</li>';
			$messages = true;
		}
		$messageList .= '</ul>';
		
		// Final output
		$output = '
					<div class="large-7 columns sticky">
					';
		if (!empty($this->errores) || !empty($this->camposVacios)) {
			$output .=	'
						<p><strong>'.trad('entre_datos').':</strong></p>
							'.$messageList;
		} else {
			$output .= '<p><strong>'.trad('sus_datos').':</strong></p>';
			$output .= '<p class="line"><strong>'.trad('checkin').':</strong> '.$this->fechaSalida->format('d/m/Y').' ('.$this->get_airport_name($this->aeropuertoSalida).')</p>';
			$output .= '<p class="line"><strong>'.trad('checkout').':</strong> '.$this->fechaLlegada->format('d/m/Y').' ('.$this->get_airport_name($this->aeropuertoLlegada).')</p>';
			
			/** EXTRAS **/
			if (!empty($this->extras)) {
				$output .= '<br /><p><strong>'.trad('extras').':</strong></p>';
				$output .= '<ul>';
				foreach ($this->extras as $e){
					$output .= '<li class="line">'.$e.'</li>';
					
				}
				$output .= '</ul>';
				
			}
			
		}
		$output .=	'			</div>
					';
		if ($this->precio > 0) {
		$output .= '
					<div class="large-5 columns yourPrice">
						<p><strong>'.trad('tu_precio').':</strong></p>
						<p class="precio" rel="'.$this->precio.'" id="precioFinal">'.$this->precio.' &euro;</p>
						
					</div>
					';
		}
		return $output;
	}	

}


// End of file