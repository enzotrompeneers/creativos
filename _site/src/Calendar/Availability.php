<?php
/**
 * Date availablity class
 *
 * @author Daniel Beard <daniel@creativos.be>
 *
 */

namespace Brunelencantado\Calendar;

use Brunelencantado\Database\DbInterface;

class Availability {
	
    protected $db;
    protected $data;

	protected $table = 'reservas';
	
	/**
	 * @brief Constructor
	 *
	 * @param DbInterface $db
	 * @param Integer $id
	 */
	public function __construct(DbInterface $db){

		$this->db = $db;

    }
    
    public function getUnavailableDates($id, $format = 'Y-m-d')
    {

        $data = $this->getData($id);

        $output = [];

        foreach ($data as $dates) {

            $unavailableDates = $this->getDatesFromRange($dates['fecha_llegada'], $dates['fecha_salida'], $format);
            $output = array_merge($output, $unavailableDates);

        }

        return $output;

    }

    public function getHalfDays($id, $format = 'Y-m-d')
    {

        $data = $this->getData($id);

        $output = [];
        $output['entries'] = [];
        $output['departures'] = [];

        foreach ($data as $range) {
            $output['entries'][] = date($format, strtotime($range['fecha_llegada']));
            $output['departures'][] = date($format, strtotime($range['fecha_salida']));

        }

        return $output;

    }


    protected function getData($id)
    {

        if ($this->data) return $this->data;

        $today = date('Y-m-d');

        $query = "
                    SELECT fecha_llegada, fecha_salida 
                    FROM ".XNAME."_{$this->table} 
                    WHERE vivienda_id = {$id}
                    AND fecha_salida >= '{$today}'
                    AND confirmado = 1
                    ";

        $this->data = $this->db->dataset($query);

        return $this->data;

    }

    /**
     * @brief Gets array of dates from start & end dates
     *
     * @param String $startDate
     * @param String $endDate
     * @return Array
     */
	protected function getDatesFromRange($startDate, $endDate, $format) {

        $dates = [];
        $current = strtotime($startDate);
        $last = strtotime($endDate);
    
        while( $current <= $last ) {
    
            $dates[] = date($format, $current);
            $current = strtotime('+ 1 day', $current);
        }
    
        return $dates;
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