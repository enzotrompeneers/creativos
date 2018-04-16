<?php
/**
 * Login handler
 *
 * @author Daniel Beard <daniel@creativos.be>
 */

namespace Brunelencantado\Login;

use \Brunelencantado\Database\DbInterface;

class Login
{
	private $db;
	public $user;
	
	/**
	 * @brief Constructor
	 * 
	 * @param DbInterface
	 */
	public function __construct(DbInterface $db)
	{
		$this->db = $db;
		
		if ($this->isLoggedIn()){
			$this->user = $_SESSION['usuario'];
		}
		
	}
	
	/**
	 * @brief Validate credentials agains database
	 *  
	 * @param String $email 
	 * @param String $password 
	 * @return Boolean
	 */
	public function login($email, $password)
	{
		$email = $this->db->sanitize($email);
		$password = $this->db->sanitize($password);
		
		$query = "SELECT id, nombre, email, hash, rol, inactivado FROM " . XNAME . "_usuarios WHERE email = '{$email}'";
		$sql = $this->db->record($query);

		// Hydrate user object
		$this->user = new \stdClass();
		$this->user->id 		= $sql['id'];
		$this->user->nombre 	= $sql['nombre'];
		$this->user->email 		= $sql['email'];
		$this->user->rol 		= $sql['rol'];
		$this->user->inactivado = $sql['inactivado'];

		if (validate_password($password, $sql['hash'])){
			$_SESSION['usuario'] = $this->user;
			return true;
		}
		
	}
	
	/**
	 * @brief Tells if user is logged in or not
	 *  
	 * @return Boolean
	 */
	public function isLoggedIn()
	{
		if (!empty($_SESSION['usuario'])){
			return true;
		}
	}
}


// End of file