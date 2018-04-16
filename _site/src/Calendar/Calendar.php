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

use \Brunelencantado\Logger\Logger;
use \Brunelencantado\Logger\LoggerWeb;

class Calendar
{
	protected $db = null;
	protected $log = null;
	
	protected $calendar = null;
	
	/**
	 * Constructor
	 *
	 * @param \Brunelencantado\Database\MySqliDatabase $db
	 * @param \Brunelencantado\Logger\Logger $log
	 */
	public function __construct(\Brunelencantado\Database\MySqliDatabase $db, \Brunelencantado\Logger\Logger $log)
	{
		$this->db = $db;
		$this->log = $log;
	}
	
	/**
	 * Renders calendar
	 *
	 * @param Int $id
	 * @param Int $year
	 * @return String Calendarin HTML format
	 */
	public function renderCalendar($id, $year, Array $months)
	{
		
		$calendar = ''; // This holds our calendar
		
		// fecha en la que nos encontramos
		$numMes 	= date('n');
		$ano	 	= $year;
		
		//Creamos array con fechas reservadas
		$aFechasRes 	= array();
		$aFechasIniRes 	= array();
		$aFechasFinRes 	= array();
		
		$reservedDates = $this->getReservedDates($id);
		if ($reservedDates){
			foreach ($reservedDates as $k => $v){
				$arrival 			= $this->convertDate($v['fecha_llegada']);
				$departure 			= $this->convertDate($v['fecha_salida']);
				$aFechasRes[] 		= date('dmy', $arrival);
				$aFechasIniRes[] 	= date('dmy', $arrival);
				$aFechasFinRes[] 	= date('dmy', $departure);	
				while($arrival < $departure){
					$arrival 			+= (3600*24);
					$aFechasRes[] 		= date('dmy', $arrival);
				}
			}
		}
		for($i = 0 ; $i < 12; $i++){
			$mesPrint = mktime(0, 0, 0, $numMes, 1, $ano);
			$datePrint = getdate($mesPrint);
			$diasMes = date( "t", $mesPrint );
			$month = $months[LANGUAGE][$datePrint['mon'] - 1];
			
			$calendar .= '<div  class="month calendar">
            <table cellspacing="0" summary="Calendar: '. $month . " " . $datePrint["year"].' ">
            	<thead>
                	<tr>
                    	<th nowrap="" class="month-label" colspan="7">'. $month . " " . $datePrint["year"].'</th>
                    </tr>
                	<tr>
                		<th class="day-label"><abbr title="Monday">m</abbr></th>
                		<th class="day-label"><abbr title="Tuesday">t</abbr></th>
                		<th class="day-label"><abbr title="Wednesday">w</abbr></th>
                		<th class="day-label"><abbr title="Thursday">t</abbr></th>
                		<th class="day-label"><abbr title="Friday">f</abbr></th>
                		<th class="day-label weekend"><abbr title="Saturday">s</abbr></th>	
                		<th class="day-label weekend"><abbr title="Sunday">s</abbr></th>
                	</tr>
                </thead>
            	<tbody>';
			// Para cuadrar hacen falta 6 lineas
			$semanasMes = 0;
			//miramos si el mes no empieza en lunes
			$diaSem = date('w', $mesPrint);
			if($diaSem != 1){ //no empieza el lunes. rellenamos huecos
				if($diaSem == 0) $diaSem = 7;
				$calendar .= '<tr>';
				$semanasMes++;
				for($j = $diaSem; $j >= 2; $j--)
					$calendar .= '<td class="a">-</td>';
			}
			
			for($j = 1; $j <= $diasMes; $j++){
				$diaPrint = mktime(0, 0, 0, $numMes, $j, $ano);
				$diaSem = date('w', $diaPrint);
				
				if($diaSem == 1) { //lunes
					$semanasMes++;
					$calendar .= '<tr>';
				}
				
                    if($this->esReserva($diaPrint, $aFechasIniRes)) {
                         if($this->esReserva($diaPrint, $aFechasFinRes)) {
                              $calendar .= '<td class="diaDoble">'.$j.'</td>';
                         } else {
                              $calendar .= '<td class="diaLlegada">'.$j.'</td>';
                         }
                    } 
                    elseif ($this->esReserva($diaPrint, $aFechasFinRes)) $calendar .= '<td class="diaSalida">'.$j.'</td>'; 
                    elseif ($this->esReserva($diaPrint, $aFechasRes)) $calendar .= '<td class="u">'.$j.'</td>'; 
                    elseif ($this->esPasado($diaPrint)) $calendar .= '<td class="diaPasado">'.$j.'</td>'; 
                    else $calendar .= '<td class="a">'.$j.'</td>';
				
				if($diaSem == 0) { //domingo
					$calendar .= '</tr>';
				}
			}
			if($diaSem != 0){
				for ($j = $diaSem; $j < 7; $j++){
					$calendar .= '<td class="a">-</td>';
				}
			}
			$calendar .= '</tr>';
			while($semanasMes < 6){
				$calendar .= '<tr>';
				for ($j = 0; $j < 7; $j++){
					$calendar .= '<td class="a">-</td>';
				}
				$calendar .= '</tr>';
				$semanasMes++;
			}
			$calendar .= '				</tbody>
				</table>
			</div>';
			if($numMes >= 12){
				$numMes = 1;
				$ano++;
			} else {
				$numMes++;
			}
		}
		return $calendar;
	}
	
	
	
	protected function getReservedDates($id)
	{
		global $xname;
		$query = "SELECT fecha_llegada, fecha_salida FROM ".XNAME."_reservas WHERE vivienda_id = $id AND confirmado = 1";
		$sql = $this->db->query($query);
		return $sql;

	}
	
	protected function convertDate($date)
	{
		$dateSolo  	= split(" ", $date);
		$aDate		= explode("-", $dateSolo[0]);
		$newDate	= mktime(0 , 0, 0, $aDate[1], $aDate[2], $aDate[0]);
		return $newDate;
		
	}
	
	protected function esReserva($dia, $aFechas){
		for($i = 0; $i< sizeof($aFechas); $i++){
			if(date('dmy', $dia) == $aFechas[$i]) return true;
		}
		return false;
	}
	
	protected function esPasado($dia){
		$today		= $today = mktime(0, 0, 0, date('m'), date('d'), date('y'));
		if ($dia<$today) return true;
		return false;
	}	
}




// End file