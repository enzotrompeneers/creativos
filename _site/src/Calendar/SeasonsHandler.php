<?php
/**
 * Reservation statistics class
 *
 * @author Daniel Beard / BE Creativos <daniel@creativos.be>
 */

namespace Brunelencantado\Calendar;

use Brunelencantado\Database\DbInterface;

class SeasonsHandler
{

    protected $db;
    protected $id;
    protected $data;

    /**
     * @brief Constructor
     *
     * @param DbInterface $db
     * @param Integer $id
     * @return Void
     */
    public function __construct(DbInterface $db, $id)
    {
        $this->db = $db;
        $this->id = $id;
		$this->data = $this->getData();
    }
  

	/**
	 * Gets all rental prices, depending on season
	 *
	 * @return Array
	 */
	public function getRentalPricesBySeason()
	{

		$seasons = $this->getSeasons();	

		$aSeasons = [];
		foreach ($seasons as $k => $v) {

			switch($v['clave']) {

				// Convert seasons key
				case 'autumn_season':
					$clavePrecio = 'precio_temp_media';
					break;
				case 'spring_season':
					$clavePrecio = 'precio_temp_media';
					break;
				case 'low_season':
					$clavePrecio = 'precio_temp_baja';
					break;
				case 'high_season':
					$clavePrecio = 'precio_temp_alta';
					break;

			}
			$aSeasons[$k]['clave'] = $v['clave'];
			$aSeasons[$k]['precio'] = $this->data[$clavePrecio];
			$aSeasons[$k]['precio_dia'] = round($this->data[$clavePrecio] / 7);
			$aSeasons[$k]['fecha_comienzo'] = $this->convertDateFromISO($v['fecha_comienzo']);
			$aSeasons[$k]['fecha_fin'] = $this->convertDateFromISO($v['fecha_fin']);

		}

		return $aSeasons;

	}

    /**
     * @brief gets necessary data from db
     *
     * @return Array
     */
    protected function getData()
    {

        $query = "  SELECT precio_temp_baja, precio_temp_media, precio_temp_alta, temporadas_json
                    FROM ".XNAME."_viviendas
                    WHERE id = {$this->id}
                    ";
        $sql = $this->db->record($query);
        
        return $sql;

    }
    
	/**
	 * @brief Get all seasons
	 *
	 * @return Array
	 */
	protected function getSeasons()
	{

		$today = date('Y-m-d');
		$nextYear = date('Y-m-d', strtotime('+1 year'));
		$query = "		SELECT * 
						FROM ".XNAME."_temporadas 
						WHERE fecha_fin > '$today' 
						AND fecha_comienzo < '$nextYear'
						ORDER BY fecha_comienzo
						";
		$sql = $this->db->dataset($query);

		return $sql;

    }
    
	/**
	 * @brief Gets extra seasons for given property
	 *
	 * @return Array
	 */
	protected function getExtraSeasons()
	{

		$temporadasExtraJson = json_decode($this->data['temporadas_json']);

		if (!empty($temporadasExtraJson)) {

			$temporadas = array_map('get_object_vars', $temporadasExtraJson);
			
			foreach ($temporadas as $k => $v){

				$temporadas[$k]['fechaComienzo']	= $this->convertDateFromISO($v['fechaComienzo']);
				$temporadas[$k]['fechaFin']			= $this->convertDateFromISO($v['fechaFin']);

			}

			return $temporadas;

		}

    }
    
	/**
	 * @brief converts dat from ISO format (2017-12-10) to other format. Default: 10/12/2017
	 *
	 * @param String $date
	 * @param String $format
	 * @return String
	 */
	protected function convertDateFromISO($date, $format = 'd/m/Y')
	{

		return date_format(date_create($date), $format);

	}

}