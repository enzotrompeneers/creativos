<?php
/**
 * Property search form class
 *
 * @author Daniel Beard <daniel@creativos.be>
 */

namespace Brunelencantado\Viviendas;

use Brunelencantado\Database\DbInterface;

class Buscador
{
	
    protected $db;

    /**
     * @brief Constructor
     *
     * @param DbInterface $db
     */
	public function __construct(DbInterface $db)
	{
		$this->db = $db;
	}

    /**
     * @brief Get options for property type
     *
     * @param String $clave
     * @return String
     */
    public function getOptions($clave, $claveSingular = null, $campo = null)
    {

		$campo = ($campo) ? $campo : 'nombre_'.LANGUAGE;
		
        $usedItems = ($claveSingular) ? "WHERE c.id IN (SELECT DISTINCT {$claveSingular}_id FROM ".XNAME."_viviendas WHERE visible = 1)" : "";

        $query = "
                    SELECT id, {$campo} AS nombre 
                    FROM ".XNAME."_{$clave}  c
                    {$usedItems}
                    ORDER BY nombre";

        $sql = dataset($query);

        return $this->renderOptions($sql, $clave);

    }

    /**
     * @brief Gets range of <option>s
     *
     * @param Integer $max
     * @param Integer $step
     * @param String $clave
     * @return String
     */
    public function getRange($max = 10, $step = 1, $clave = null)
    {

        $output = [];

        for($n = 1; $n <= $max; $n += $step){

            $output[$n]['id'] = $n;
            $output[$n]['nombre'] = $n;
            
        }

        return $this->renderOptions($output, $clave);

    }

    /**
     * @brief Gets price range options based on web config data
     *
     * @param String $clave
     * @return String List of options
     */
    public function getPriceRange($clave)
    {

        $range = explode(',', webConfig('rango_precio'));

        $output = [];

        foreach ($range as $k => $v){

            $output[$k]['id'] = $v * 1000;
            $output[$k]['nombre'] = precio($v * 1000);

        }

        return $this->renderOptions($output, $clave);

    }

    /**
     * @brief Gets a list of <option>s with locations, grouped by costa
     *
     * @param String $clave
     * @return String HTML <option>s
     */
    public function getLocationsByCosta($clave)
    {

        $usedItems = "WHERE loc.id IN (SELECT DISTINCT localidad_id FROM ".XNAME."_viviendas WHERE visible = 1)";

        $query = "  SELECT cos.nombre_".LANGUAGE." AS costa,
                    loc.nombre AS localidad, 
                    loc.id AS localidad_id, loc.costa_id
                    FROM ".XNAME."_localidades loc
                    LEFT JOIN ".XNAME."_costas cos
                        ON loc.costa_id = cos.id
                    {$usedItems}
                    ORDER BY loc.costa_id, loc.nombre
        ";
        $sql = $this->db->dataset($query);

        $output = [];
        foreach ($sql as $k => $v){
          
            $costa = $v['costa_id'];

            $output[$costa]['parent'] = ['id' => $costa, 'nombre' => $v['costa']];
            $output[$costa]['children'][] = ['id' => $v['localidad_id'], 'nombre' => $v['localidad']];
 
        }

        return $this->renderNestedOptions($output, $clave);

    }

    /**
     * @brief Tells if checkbox is checked
     *
     * @param [type] $clave
     * @return boolean
     */
    public function isChecked($clave)
    {

        return (isset($_GET[$clave]) && $_GET[$clave] !== '') ? 'checked' : '';

    }

    /**
     * @brief Renders array to list of <option>s
     *
     * @param Array $data [ 'id' => $id, 'nombre' => $nombre ]
     * @param Array $clave
     * @return String HTML <option>s
     */
    protected function renderOptions(array $data, $clave = null, $noFirstOption = false)
    {

        $options = ($clave && !$noFirstOption) ? '<option value="">' . trad($clave) . '</option>' . "\n" : '';

        foreach ($data as $option) {

            $selected = (isset($_GET[$clave]) && (string) $_GET[$clave] === (string) $option['id']) ? 'selected' : '';

            $options .= '<option value="' . $option['id'] . '" ' . $selected . '>' . $option['nombre'] . '</option>' . "\n";

        }

        return $options;

    }

    /**
     * @brief Renders nested select options
     *
     * @param Array $data
     * @param String $clave
     * @return String
     */
    protected function renderNestedOptions(array $data, $clave)
    {

        $options = ($clave) ? '<option value="">' . trad($clave) . '</option>' . "\n" : '';

        foreach ($data as $option) {
            
            $selected = (isset($_GET[$clave]) && (string) $_GET[$clave] == 'parent-' . $option['parent']['id']) ? "selected" : "";

            $options .= '<option value="parent-' . $option['parent']['id'] . '" ' . $selected . '>' . $option['parent']['nombre'] . '</option>' . "\n" ;
            $options .= '<optgroup>' . "\n";
            $options .= $this->renderOptions($option['children'], $clave, true) . "\n" ;
            $options .= '</optgroup>' . "\n";

        }
        
        return $options;

    }

	
}



// End of file