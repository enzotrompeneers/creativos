<?php
/**
 * Property detail class
 *
 * @author Daniel Beard <daniel@brunel-encantado.com>
 */

namespace Brunelencantado\Viviendas;

use Brunelencantado\Database\DbInterface;

class ViviendasDetail
{

	protected $db;
	protected $id;
	protected $table = 'viviendas';	
	protected $data = array();	

	const OPCIONESCOLUMNS = 2;
	
	public $rows;
	
	/**
	 *  @brief Contructor
	 *  
	 *  @param DbInterface $db Database object
	 *  @param Integer $id Property id
	 */
	public function __construct(DbInterface $db, $id)
	{
		$this->db = $db;
		$this->id = $id;
	}
	
	/**
	 *  @brief Gets propery details from the database and formats
	 *  
	 *  @return Array
	 */
	public function getDetails() 
	{
		
		$data = $this->getData();
		
		if (!$data) return;
		
		// Is it a rental?
		$data['alquiler'] = $this->isRental();

		// General data
		$data['titulo'] = ($data['titulo'] != '') ? $data['titulo'] : ViviendasHelpers::frase($data['dormitorios'], $data['tipo'], $data['localidad']) . ' - '  . $data['clase'];
		$data['link'] = curPageURL();

		// Price
		$thousandSeperator = (LANGUAGE == 'en') ? ',' : '.';
		$data['precio'] = number_format($data['precio_de_venta'], 0, false, $thousandSeperator) . '&euro;';
		$data['precio'] = ($data['alquiler']) ? $this->getLowestRentPrice() : $data['precio'];
		
		// Images
		$data['ruta'] = BASE_SITE . 'images/viviendas/' . $this->id . '/';
		$data['images'] = $this->getImages();
		
		// Opciones
		$data['opciones'] = $this->getOpciones();
		$data['opcionesColumn'] = ceil(count($data['opciones']) / self::OPCIONESCOLUMNS);

		// Energy certificate
		$data['cert'] = $data['certificado_energia'];
		$data['certificado_energia'] = ($data['certificado_energia'] == 'X') ? trad('pendiente') : $data['certificado_energia'];
		
		// Short descriptions
		$data['meta_descripcion'] = $this->shortDescription(400);
		$data['short_descripcion'] = $this->shortDescription(1400);
		$data['meta_keywords'] = $this->keywords($data['titulo']);
		
		// Current url
		$data['link'] = curPageURL();

		if ($this->isRental()) {
		
			// Extras
			$extras = $this->getExtras();
			$data['extras'] = $extras['extras'];
			$data['suplementos'] = $extras['suplementos'];
			
			// Seasons
			$data['seasons'] = $this->getRentalPricesBySeason();
			$data['seasonsExtra'] = $this->getExtraSeasons();
		}
		
		$this->data = $data;

		return $data;
		
	}
	
	/**
	 *  @brief Creates title from data
	 *  
	 *  @param Array $data Entity data [ clase, localidad ]
	 *  @return String Basic title, for example: 'Villa in Alicante'.
	 */
	public static function createTitle($data)
	{
		return $data['clase'] . ': ' . $data['clase'] . ' ' . trad('en') . ' ' . $data['localidad'];
	}
	
	/**
	 * @brief Gets last bit of link, translated
	 *
	 * @param String $language
	 * @return String Link to detail page in specified language
	 */
	public function getTranslatedLink($language)
	{
		$costa = $this->getTranslatedName('costas', $language, $this->data['costa_id']);
		$tipo = $this->getTranslatedName('tipos', $language, $this->data['tipo_id']);

		$titulo = $this->data['titulo_' .$language];
		$titulo = ($titulo == '') ? ViviendasHelpers::frase($this->data['dormitorios'], $tipo, $this->data['localidad'], $language) : $titulo;
		return slug(trad('espana', $language)) . '/' . slug($costa) . '/' . slug($titulo) . '-' . $this->data['id'] . '.html';

	}

	/**
	 * @brief Gets translation for a given table and id
	 *
	 * @param String $table
	 * @param String $language
	 * @param Integer $id
	 * @return String
	 */
	protected function getTranslatedName($table, $language, $id)
	{

		$query = "SELECT nombre_{$language} AS nombre FROM ".XNAME."_{$table} WHERE id = {$id}";
		$sql = $this->db->record($query);

		return $sql['nombre'];

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

			$aSeasons[$k]['clave'] = $clavePrecio;
			$aSeasons[$k]['precio'] = $this->data[$clavePrecio];
			$aSeasons[$k]['precio_dia'] = round($this->data[$clavePrecio] / 7);
			$aSeasons[$k]['fecha_comienzo'] = $this->convertDateFromISO($v['fecha_comienzo']);
			$aSeasons[$k]['fecha_fin'] = $this->convertDateFromISO($v['fecha_fin']);

		}

		return $aSeasons;

	}

	/**
	 * @brief Get all seasons
	 *
	 * @return Array
	 */
	protected function getSeasons()
	{

		$today = date('Y-m-d');
		$query = "SELECT * FROM ".XNAME."_temporadas WHERE fecha_fin > '$today'";
		$sql = $this->db->dataset($query);

		return $sql;

	}

	/**
	 *  @brief Gets short description
	 *  
	 *  @param Integer $characters Nº of characters
	 *  @return String
	 */
	protected function shortDescription($characters)
	{
		$text = strip_tags($this->data['descripcion']);
		
		$elipsis = (strlen($text) > $characters) ? '&hellip;' : '';
		
		$text = substr($text, 0, $characters);
		$text = $text . $elipsis;
		
		return $text;
	}

	/**
	 * @brief Converts phrase into comma separated
	 *
	 * @param String $string
	 * @return Void
	 */
	protected function keywords($string)
	{

		$array = explode(' ', $string);
		$output = implode(', ', $array);

		return $output;

	}
	
	/**
	 *  @brief Changes the product table
	 *  
	 *  @param String $tableName
	 *  @return Void
	 */
	public function setTable($tableName)
	{
		$this->table = $tableName;
	}	

	/**
	 *  @brief Queries DB for raw data
	 *  
	 *  @return Array
	 */
	protected function getData()
	{
		$query = "	SELECT main.*, titulo_".LANGUAGE." AS titulo,
					descripcion_".LANGUAGE." AS descripcion,
					loc.nombre as localidad, cos.provincia,
					-- loc.descr_".LANGUAGE." AS descripcion_localidad,
					cla.nombre_".LANGUAGE." as clase,
					tip.nombre_".LANGUAGE." as tipo, 
					cos.nombre_".LANGUAGE." as costa,
					pis.nombre_".LANGUAGE." as piscina,
					jar.nombre_".LANGUAGE." as jardin,
					ori.nombre_".LANGUAGE." as orientacion,
					par.nombre_".LANGUAGE." as parking,
					vis.nombre_".LANGUAGE." as vistas
					FROM ".XNAME."_{$this->table} main
					LEFT JOIN ".XNAME."_localidades loc
						ON main.localidad_id = loc.id
					LEFT JOIN ".XNAME."_costas cos
						ON main.costa_id = cos.id
					LEFT JOIN ".XNAME."_clases cla
						ON main.clase_id = cla.id
					LEFT JOIN ".XNAME."_tipos tip
						ON main.tipo_id = tip.id
					LEFT JOIN ".XNAME."_piscinas pis
						ON main.piscina_id = pis.id
					LEFT JOIN ".XNAME."_jardines jar
						ON main.jardin_id = jar.id
					LEFT JOIN ".XNAME."_orientaciones ori
						ON main.orientacion_id = ori.id
					LEFT JOIN ".XNAME."_parkings par
						ON main.parking_id = par.id
					LEFT JOIN ".XNAME."_vistas vis
						ON main.vista_id = vis.id
						
					WHERE main.id = {$this->id}";
		$sql = $this->db->record($query);

		$this->data = $sql;
		
		return $sql;
	}
	
	
	/**
	 *  @brief Gets array of images
	 *  
	 *  @return Array
	 */
	protected function getImages()
	{
		$query = "SELECT parent_id, file_name FROM ".XNAME."_images_{$this->table} WHERE parent_id = {$this->id} ORDER BY orden";
		$sql = $this->db->dataset($query);
		
		$output = array();
		$n = 0;
		$path = BASE_SITE . 'images/viviendas/' . $this->id;
		
		foreach ($sql as $k => $v) {

			$isLocal = (mb_substr($v['file_name'], 0, 4) == 'http');
			
			$output[$n]['g'] = ($isLocal) ? $v['file_name'] : $path . '/g_' . $v['file_name'];
			$output[$n]['l'] = ($isLocal) ? $v['file_name'] : $path . '/l_' . $v['file_name'];
			$output[$n]['m'] = ($isLocal) ? $v['file_name'] : $path . '/m_' . $v['file_name'];
			$output[$n]['s'] = ($isLocal) ? $v['file_name'] : $path . '/s_' . $v['file_name'];
			
			$n++;
		}
		
		return $output;
	}

	
	/**
	 *  @brief Gets Opciones from table
	 *  
	 *  @return Array
	 */
	protected function getOpciones()
	{
		$query = "SELECT id, nombre_".LANGUAGE." as nombre FROM ".XNAME."_opciones ORDER BY orden";
		$sql = $this->db->dataset($query);
		
		
		$viviendaOpciones = json_decode($this->data['opciones_json']);
		$aOpciones = [];
		foreach ($sql as $k => $v){
			
			if (in_array($v['id'], $viviendaOpciones)) $aOpciones[]	= $v['nombre'];
			
		}
		
		return $aOpciones;
	}
	
	/**
	 *  @brief Gets Extras
	 *  
	 *  @return Array
	 */
	protected function getExtras()
	{
		$query = "
					SELECT *, nombre_".LANGUAGE." as nombre 
					FROM ".XNAME."_extras 
					ORDER BY obligatorio DESC, orden";
		$sql = $this->db->dataset($query);

		$sqlExtras = [];
		foreach ($sql as $item) {

			$sqlExtras[$item['id']] = $item;

		}
		
		$viviendaExtras = json_decode($this->data['extras_json']);

		$aSuplementos = [];
		$aExtras = [];
		
		foreach ($viviendaExtras as $k => $v){
			
			$id = $v->id;

			if ($id == 6) continue; // Deposit now does not exist

			$data = [];

			$data['id'] = $id;
			$data['valor'] = $v->value;
			$data['nombre'] = $sqlExtras[$id]['nombre'];

			if ($sqlExtras[$id]['obligatorio'] == 1) { 
				$aSuplementos[$id] = $data;
			 } else {
				 $aExtras[$id] = $data;
			 }

		}
		
		return ['extras' => $aExtras, 'suplementos' => $aSuplementos];
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
	 *  @brief Formats price depending on language
	 *  
	 *  @param Integer $precio Number to format
	 *  @return String Formatted number
	 */
	protected function precio($precio){
		switch (LANGUAGE){
			case 'en':
				return number_format($precio, 0, '.', ',');
				break;
			case 'es':
				return number_format($precio, 0, ',', '.');
				break;
			default:
				return number_format($precio, 0, ',', '.'); 
				break;
		}
	}

	/**
	 * @brief Is this a rental property?
	 *
	 * @return Boolean
	 */
	protected function isRental()
	{

		return ($this->data['clase_id'] == 3 && ALQUILERES);

	}

	/**
	 * @brief Looks for the lowest rental price
	 *
	 * @return String price with €/week
	 */
	protected function getLowestRentPrice()
	{

		return ($this->data['precio_temp_baja'] > 0) ? $this->data['precio_temp_baja'] . '&euro; / ' . trad('semana') : trad('consultar');

	}

	/**
	 *  @brief Converts price to other currencies
	 *  
	 *  @param Integer $price Price to be converted
	 *  @param Integer $currency current currency
	 *  @return String converted price with currency
	 */
	protected function getConvertedPrice($price, $currency)
	{
		$data = $this->data;
		$USDtoEUR = webConfig('USDtoEUR');

		$aPrice = array();
		if ($currency == 'USD' && $price > 0) {
			$aPrice['EUR'] = $this->precio($price * $USDtoEUR) . ' EUR';
			$aPrice['USD'] = $this->precio($price) . ' USD';
		}
		if ($currency == 'EUR' && $price > 0){
			$aPrice['EUR'] = $this->precio($price) . ' EUR';
			$aPrice['USD'] = $this->precio($price / $USDtoEUR) . ' USD';
		}

		return $aPrice;
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

    /**
     * @brief Returns true or false for property details
     * 
     * @param String $clave
     * @return Boolean
     */
	public function hasAttribute($clave){
		
		if (array_key_exists ($clave, $this->data) ) {
			
			return true;
			
		} 
		
	}	
}


// End of file