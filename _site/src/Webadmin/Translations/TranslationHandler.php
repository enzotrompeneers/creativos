<?php
/**
 * Translation handler
 *
 * For viewing and saving webadmin translations
 *
 * @author Daniel Beard <daniel@creativos.be>
 */

namespace Brunelencantado\Webadmin\Translations;


class TranslationHandler
{
	protected $registry = null;
	protected $db = null;
	
	/**
	 *  @brief Constructor, sets registry and db properties
	 *  
	 *  @param [in] $db Database class
	 *  
	 */
	 public function __construct (\Brunelencantado\Registry\Registry $registry)
	 {
		 $this->registry = $registry;
		 $this->db = $this->registry->getObject('db');
		
	 }
	 
	 /**
	  *  @brief Gets a list of translation
	  *  
	  *  @return List <li> of translations
	  *  
	  *  @details Details
	  */
	 public function getList()
	 {
		$query = "SELECT * FROM ".XNAME."_traducciones WHERE used = 1 AND clave != '' ORDER BY clave";
		$sql = $this->db->dataset($query);
		
		
		$aTraducciones = array();
		foreach ($sql as $k => $v) {
			$aTraducciones[$v['clave']] = '<strong>'. $v[LANGUAGE].'</strong><br/><span>('.$v['clave'].')</span>';
			foreach (\Brunelencantado\Config::getParameter('languages') as $l){
				$noTranslation = (! isset($v[$l]) || $v[$l] == '') ? 'no_translation no-' . $l : '';
				$aTraducciones[$v['clave']] .= ' <img src="images/flags/'.$l.'.png" alt="'.$l.'" class="flag '.$noTranslation.'"/>';
			}
		}
		return $aTraducciones;
	 }

	 public function getArray($language)
	 {

		$query = "SELECT clave, {$language} AS value FROM ".XNAME."_traducciones WHERE  clave != '' ORDER BY clave";
		$sql = $this->db->dataset($query);

		$output = [];
		foreach ($sql as $k => $v){

			$output[$v['clave']] = $v['value'];

		}

		return $output;

	 }
	 
	 /**
	  *  @brief Gets the translations for a determined clave
	  *  
	  *  @param [in] $clave 
	  *  @return array with tranlations
	  */
	 public function getTranslations($clave)
	 {
		 $aLanguages = \Brunelencantado\Config::getParameter('languages');
		 $language = implode(', ', $aLanguages);
		 $art = 'art_' . implode(', art_', $aLanguages);
		 $sql = array();
		 $tradQuery = "SELECT {$language} FROM ".XNAME."_traducciones WHERE clave = '$clave'";

		 $sql[0] = $this->db->record($tradQuery);
		 $artQuery = "SELECT {$art} FROM ".XNAME."_traducciones WHERE clave = '$clave'";
		 $sql[1] = $this->db->record($artQuery);
		 return $sql;
	 }
	 
	 /**
	  *  @brief saves translation data for a determined clave
	  *  
	  *  @param [in] $clave 
	  *  @param [in] $data 
	  *  
	  *  @return true or false
	  */
	 public function save($clave, $data)
	 {
		$table = XNAME . "_traducciones";
		$where = array('clave' => $clave);
		return $this->db->updateQuery($data, $table, $where);
	 }
	 

	 

}




// End file