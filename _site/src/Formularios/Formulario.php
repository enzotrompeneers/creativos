<?php
/**
 * Form processing tool
 *
 * @author Daniel Beard <daniel@brunel-encantado.com>
 */

namespace Brunelencantado\Formularios;

use \Brunelencantado\Database\DbInterface;

class Formulario
{
	protected $campos = array();
	protected $requiredFields = array();
	protected $errores = array();
	protected $db;
	protected $archivos_permitidos = array('png', 'jpg', 'jpeg', 'doc', 'docx', 'pdf');

	protected $ignores = array('submit','condiciones', 'id', 'code', 'g-recaptcha-response');
	protected $claveRecaptcha = '6LfyvjQUAAAAACS3sAsKlt2PA55xT1kSvGMBl5gM';

	/**
	 * @brief Constructor
	 *
	 * @param Array $campos All data to be processed
	 * @param Array $requiredFields Obligatory fields
	 * @param DbInterface $db
	 */
    public function __construct(array $campos, array $requiredFields, DbInterface $db)
    {
		$this->campos = $campos;
		$this->requiredFields = $requiredFields;
		$this->db = $db;
    }
	
	/**
	 *  @brief Validates all required fields
	 *  
	 *  @return Boolean, validates or not
	 */
	public function hasErrors()
	{
		
		// Check all required fields
		foreach ($this->requiredFields as $k=>$v){
			
			$valor = (!empty($this->campos[$k]))?$this->campos[$k]:'';
			$tipo = $v;
			$error = $this->fieldHasError($k, $valor, $tipo);
			
			if ($error){
				$this->errores[$k] = trad($error);
			}
			
		}
		
		if ($this->errores){
		
			return $this->errores;
			
		}
		
		
	}
	
	/**
	 *  @brief saves form to database
	 * 
	 *  @param Array Values to ignore
	 *  
	 *  @return Boolean, true if successful, false if not
	 *  
	 */
	public function save($ignores = [])
	{

		// Get full POST
		$cleanStrings = $this->sanitize($this->campos);
		$fullEmail = $this->data2Table($cleanStrings, $ignores);
		
		// Prepare data for saving
		$insert = array();
		$insert['nombre'] 			= $cleanStrings['nombre'];
		$insert['telefono'] 		= $cleanStrings['telefono'];
		$insert['email'] 			= $cleanStrings['email'];
		$insert['mensaje'] 			= (!empty($cleanStrings['mensaje'])) ? $cleanStrings['mensaje'] : '';
		$insert['email_completo'] 	= $fullEmail;
		$insert['fecha']	 		= date('Y-m-d H:i:s');
		$insert['ip']	 			= $_SERVER['REMOTE_ADDR'];
		
		// Insert into database
		$this->db->insertQuery($insert, XNAME . '_contactos');

	}

	/**
	 * @brief Sanitizes array for database input
	 *
	 * @param Array $array Data to be sanitized
	 * @return Array Sanitized data
	 */
	public function sanitize(array $array)
	{

		$cleanStrings = [];

		foreach ($array as $k => $v) {

			$cleanStrings[$k] = filter_var($v, FILTER_SANITIZE_STRING);

		}

		return $cleanStrings;

	}

	/**
	 * @brief Converts data to HTML table, minus $ignores fields
	 *
	 * @param Array $data
	 * @param Array $ignores
	 * @return String
	 */
	public function data2Table(array $data, array $ignores = [])
	{

		$fullEmail = '<table>';

		foreach ($data as $k=>$v){
			if (in_array($k, $ignores)) continue;
			if ($v=='') continue;
			$fullEmail .= ($v!='') ? '<tr><td><strong>'.trad($k).':</strong>&nbsp;</td><td>'.$v.'</td></tr>'."\n":'';
		}

		$fullEmail .= '</table>';

		return $fullEmail;

	}

	/**
	 * @brief Recaptcha key setter
	 *
	 * @param String $clave
	 * @return Void
	 */
	public function setRecaptchaClave($clave)
	{

		$this->claveRecaptcha = $clave;

	}

	/**
	 * @brief Recaptcha key getter
	 *
	 * @return String
	 */
	public function getRecaptchaClave()
	{

		return $this->claveRecaptcha;

	}

	/**
	 *  @brief Checks file extensions to see if valid
	 *  
	 *  @param Array $archivos array of file names
	 *  @return String Error message if not accepted extension
	 *  
	 */
	protected function checkFiles(array $archivos)
	{
		foreach ($archivos as $archivo){

			$extension = pathinfo($archivo, PATHINFO_EXTENSION);

			if (!in_array($extension, $this->archivos_permitidos)){

				return 'error_archivo';

			}
		}
	}
	
	/**
	 * Says if value is valid, depending on type
	 *  
	 *  @param String $key 
	 *  @param String $value 
	 *  @param String $type 'texto' default (must not be empty) 
	 *  @return String Error message if not valid
	 *  
	 */
	protected function fieldHasError($key, $value, $type = 'texto')
	{
		
		// Text
		if ($type == 'texto') {
			if ($value == '') {
				return $key;
			}
		}

		// URL
		if ($type == 'url') {
			if (filter_var($value, FILTER_VALIDATE_URL == false)){
				return 'error_'.$key;
			}
		}
		
		// Email
		if ($type == 'email') {
			if (!valid_email($value)){
				return 'error_'.$key;
			}
		}
		
		// Captcha
		if ($type == 'captcha') {
			if (!chk_crypt($value)){
				return 'error_'.$key;
			}
		}
		
		// Clave
		if ($type == 'clave') {
			if (strlen($value) < 6){
				return 'error_'.$key;
			}
		}
		
		// Checkbox
		if ($type == 'checkbox') {
			if (strtolower($value!='on')) {
				
				return 'error_'.$key;
			}
		}

		// Recaptcha
		if ($type == 'recaptcha') {

			if (!$this->testRecaptcha($value)) {

				return 'error_' . $key;

			}
		}

		return false;
	}

	/**
	 * @brief Tests Recaptcha 
	 *
	 * @param String $value
	 * @return Boolean
	 */
	protected function testRecaptcha($value)
	{

		$url = 'https://www.google.com/recaptcha/api/siteverify';
		$data = array(
			'secret' => $this->claveRecaptcha,
			'response' => $value
		);
		
		$options = array(
			'http' => array (
				'header' => 'Content-Type: application/x-www-form-urlencoded',
				'method' => 'POST',
				'content' => http_build_query($data)
			)
		);

		$context  = stream_context_create($options);
		$verify = file_get_contents($url, false, $context);
		$captchaSuccess = json_decode($verify);

		return $captchaSuccess->success;

	}

}


// End of file