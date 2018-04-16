<?php
/**
 * Reservation statistics class
 *
 * @author Daniel Beard / BE Creativos <daniel@creativos.be>
 */

namespace Brunelencantado\Calendar;

use Brunelencantado\Database\DbInterface;


class Stats
{

    protected $db;
    protected $data;
    
    protected $table = 'reservas';

    /**
     * @brief Constructor
     *
     * @param DbInterface $db
     * @param Integer $id
     */
    public function __construct(DbInterface $db, $id)
    {

        $this->db = $db;
        $this->data = $this->getData($id);

    }

    public function getMonthlyReservations()
    {

        $output =  [];

        $today = new \DateTime();
        $dateTime = new \DateTime();
        $dateTime->modify('first day of this month last year');
        $lastYear = $dateTime->format('Y-m-d');

        $output[] = ['Month', 'Occupancy', 'Blocked'];

        $difference = date_diff($dateTime, $today)->format('%R%a');
        $occupancyByMonth = [];
        for ($i = 0; $i <= $difference; $i++){
            
            $thisDay = $dateTime->modify('+1 day');
            $thisMonth = $thisDay->format('M \'y');
            $thisDate = $thisDay->format('Y-m-d');

            $occupancyByMonth[$thisMonth]['occupied'] = (!isset($occupancyByMonth[$thisMonth]['occupied'])) ? 0 : $occupancyByMonth[$thisMonth]['occupied'];
            $occupancyByMonth[$thisMonth]['blocked'] = (!isset($occupancyByMonth[$thisMonth]['blocked'])) ? 0 : $occupancyByMonth[$thisMonth]['blocked'];
            
            if ($this->isReserved($thisDate, 'occupied')) $occupancyByMonth[$thisMonth]['occupied'] += 1;
            if ($this->isReserved($thisDate, 'blocked')) $occupancyByMonth[$thisMonth]['blocked'] += 1;
                
        }

        foreach ($occupancyByMonth as $k => $v){

            $output[] = [$k, $v['occupied'], $v['blocked']];

        }
            
        return $output;

    }

    public function getOccupancyPercentages()
    {


    }

    protected function getData($id)
    {

        $query = "SELECT fecha_llegada, fecha_salida, propietario FROM ".XNAME."_{$this->table} WHERE vivienda_id = {$id} AND confirmado = 1";
        $sql = $this->db->dataset($query);

        return $sql;

    }

    protected function isReserved($date, $type)
    {

        $dates = $this->data;

        if (!$dates) return;

        foreach ($dates as $k => $v){

            if ($date >= $v['fecha_llegada'] && $date < $v['fecha_salida']){

                if ($v['propietario'] == 1 && $type == 'blocked') return true;

                if ($v['propietario'] == 0 && $type == 'occupied') return true;

            }

        }

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