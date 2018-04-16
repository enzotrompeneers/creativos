<?php
/**
 * Helper class
 *
 * just bundling all helper webadmin functions as static methods here for the moment
 *
 * @author Daniel Beard <daniel@creativos.be>
 */

namespace Brunelencantado\Webadmin;


class Helpers
{
	
	/**
	 *  @brief Test to see if valid email
	 *  
	 *  @param [in] $email address to validate
	 *  @return boolean
	 *  
	 */
	public static function isValidEmail($email)
	{
		return filter_var ($email, FILTER_VALIDATE_EMAIL);
	}

	/**
	 *  @brief Returns translation or humanization depending on $translations variable
	 *  
	 *  @param [in] $clave
	 *  	 
	 *  @return translation or humanized version
	 *  
	 */
	public static function show_label($clave) {
		global $language,$xname,$translations;
		if ($translations==TRUE) {
			return trad($clave);
		} else {
			return humanize ($clave);
		}
	}	
	
	/**
	 *  @brief gives back link to controller
	 *  
	 *  @return link
	 *  
	 *  @details Can be ajax or not
	 */
	public static function getLink($controller, $clave = null, $id = null)
	{
		$clave 		= ($controller == 'tipos') ? XNAME . '_' . $clave : $clave;
		$claveLink 	= ($clave) ? $clave . '/' : '';
		$idLink 	= ($id) ? $id . '/' : '';
		$link 		= \Config::getParameter('base_site') . LANGUAGE . '/app/' . $controller . '/' . $claveLink . $idLink;
		
		return $link;
	}
	
	/**
	 *  @brief Getting the column names
	 *  
	 *  @param [in] $tbl_name name of the table to get columns from
	 *  @return Array
	 *  
	 */
	public static function column_names($tbl_name) {
		
		$query = "SELECT * FROM $tbl_name WHERE 1 = 0";
		if(!($result_id = mysql_query ($query))) return false;
		
		$names = array();
		for($i = 0; $i < mysql_num_fields($result_id); $i++){
			if($field = mysql_fetch_field ($result_id, $i)) $names[] = $field->name;
		}
		mysql_free_result($result_id);
		
		return $names;
	}	

}




// End file