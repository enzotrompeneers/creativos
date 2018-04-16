<?php
/**
 * Property list class
 *
 * @author Daniel Beard <daniel@creativos.be>
 */

namespace Brunelencantado\Calendar;

use Brunelencantado\Database\DbInterface;

class ReservationsList
{
	
	protected $table = 'reservas';
	protected $db;


	public function __construct(DbInterface $db)
	{
        
		$this->db = $db;

	}
	
	/**
	 *  Gets list of viviendas
	 *  
	 *  @return Array
	 */
	public function getList($propertyId) 
	{
		
        $sql = $this->getData($propertyId);

        $output = [];

        foreach ($sql as $k => $v) {
        
            $output[$k] = $v;
            $output[$k]['id'] = $v['res_id'];
            $output[$k]['fecha_llegada'] = $this->convertDateFromISO($v['fecha_llegada']);
            $output[$k]['fecha_salida'] = $this->convertDateFromISO($v['fecha_salida']);

            $today = date('Y-m-d');
            
            $output[$k]['pasado'] = ($v['fecha_salida'] < $today) ? 'pasado' : '';
            $output[$k]['actual'] = ($v['fecha_llegada'] < $today && $v['fecha_salida'] > $today) ? 'actual' : '';

        }
        
        return ($output);
		
	}
	
	/**
	 *  @brief Get results form database
	 *  
	 *  @param [in] $limit Limit
	 *  @return Array
	 *  
	 */
	protected function getData($propertyId)
	{
		
	
		// Main query
		$query = "  SELECT *, res.id AS res_id
					FROM ".XNAME."_{$this->table} res
					LEFT JOIN ".XNAME."_clientes cli
						ON res.cliente_id = cli.id
                    WHERE vivienda_id = {$propertyId}
                    ORDER BY fecha_salida DESC
                    ";	
		
		return $this->db->dataset($query);
		
	}
	
	/**
	 *  Changes the product table
	 *  
	 *  @return void
	 */
	public function setTable($tableName)
	{
		$this->table = $tableName;
	}
	
    protected function convertDateFromISO($date)
	{

		$format = 'd/m/Y';
		return date_format(date_create($date), $format);

	}
	
}



// End of file