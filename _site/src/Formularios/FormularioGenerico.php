<?php
/**
 * Form processing tool
 *
 * @author Daniel Beard <daniel@brunel-encantado.com>
 */

namespace Brunelencantado\Formularios;

class Formulario
{
	protected $campos = array();
	protected $campos_obligatorios = array();
	protected $errores = array();

    public function __construct(array $campos, array $campos_obligatorios)
    {
		$this->campos = $campos;
		$this->campos_obligatorios = $campos_obligatorios;
		
    }
	
	/**
	 *  @brief Validates all required fields
	 *  
	 *  @return boolean, validates or not
	 *  
	 */
	public function validate()
	{
		foreach ($this->campos_obligatorios as $k=>$v){
			$valor = $this->campos($k);
			$tipo = $v;
			if (fieldHasError($k, $valor, $tipo)){
				
			}
		}
		
	}
	
	/**
	 *  @brief saves form to database
	 *  
	 *  @return boolean, true if successful, false if not
	 *  
	 */
	public function save()
	{
		
	}
	
	/**
	 *  @brief Returns all errors from from
	 *  
	 *  @return JSON object with errors
	 *  
	 *  @details Details
	 */
	public function jsonErrors()
	{
		
	}
	
	/**
	 *  @brief Says if value is valid, depending on type
	 *  
	 *  @param [in] $key 
	 *  @param [in] $value 
	 *  @return Error if not valid
	 *  
	 */
	protected function fieldHasError($key, $value, $type='texto')
	{
		// Text
		if ($type=='texto'){
			if ($value==''){
				return 'error_'.$key;
			}
		}

		// Email
		if ($type=='email'){
			if (valid_email($value)){
				return 'error_'.$key
			}
		}
		return false;
	}

}


// End of file