<?php
/**
 * Client hadler class
 *
 * @author Daniel Beard / BE Creativos <daniel@creativos.be>
 */

namespace Brunelencantado\Calendar;

use Brunelencantado\Database\DbInterface;

class Client
{

    protected $db;
    protected $table = 'clientes';

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
     * @brief Saves client by id // If no client record exists with that id or no id is supplied, it is created
     *
     * @param Integer $id
     * @param Array $data ['nombre', 'apellido', 'telefono', 'email', 'identificacion', 'direccion', 'codigo_postal', 'localidad', 'pais', 'fecha_nacimiento']
     * @return Integer Id of saved entity
     */
    public function save(array $data, $id = null)
    {

        $data = filter_var_array($data, FILTER_SANITIZE_STRING);

        $client = [];
        $client['nombre'] = $data['nombre'];
        $client['apellido'] = $data['apellido'];
        $client['telefono'] = $data['telefono'];
        // $client['movil'] = $data['movil'];
        $client['email'] = $data['email'];
        // $client['identificacion'] = $data['identificacion'];
        $client['direccion'] = $data['direccion'];
        // $client['codigo_postal'] = $data['codigo_postal'];
        // $client['localidad'] = $data['localidad'];
        $client['pais'] = $data['pais'];
        // $client['fecha_nacimiento'] = $data['fecha_nacimiento'];

        // If in system, update, else insert
        if ($id && is_numeric($id)) {

            $this->db->updateQuery($client, XNAME."_{$this->table}", [ 'id' => $id ]);

            return $id;

        }

        $newId = $this->db->insertQuery($client, XNAME."_{$this->table}");

        return $newId;

    }

    /**
     * @brief Saves client by email // If no client record exists with that email, it is created
     *
     * @param String $email
     * @param Array $data ['nombre', 'apellido', 'telefono', 'email', 'identificacion', 'direccion', 'codigo_postal', 'localidad', 'pais', 'fecha_nacimiento']
     * @return Integer $id
     */
    public function saveByEmail(array $data, $email = null)
    {

        $id = ($email) ? $this->getIdByEmail($email) : null;

        return $this->save($data, $id);

    }

    /**
     * @brief Gets all client details by id
     *
     * @param Integer $id
     * @return Array
     */
    public function getDetails($id)
    {

        $data = $this->getData($id);
        $output = $data;

        return $output;

    }

    /**
     * @brief Gets all client details by email
     *
     * @param String $email
     * @return Array
     */
    public function getDetailsByEmail($email)
    {

        $id = $this->getIdByEmail($email);
        $output = $this->getDetails($id);
        
        return $output;

    }

    /**
     * @brief List of clients
     *
     * @return Array
     */
    public function getList()
    {

        $data = $this->getListData();

        return $data;

    }

    /**
     * @brief Getsuser data from db
     *
     * @param Integer $id
     * @return Array
     */
    protected function getData($id)
    {

        $id = filter_var(trim($id), FILTER_SANITIZE_STRING);

        $query = "SELECT * FROM ".XNAME."_clientes WHERE id = {$id}";
        $sql = $this->db->record($query);

        return $sql;

    }

    /**
     * @brief Gets id based on email
     *
     * @param String $email
     * @return Integer
     */
    protected function getIdByEmail($email)
    {

        $query = "SELECT id FROM ".XNAME."_{$this->table} WHERE email = '{$email}'";
        $sql = $this->db->record($query);

        return $sql['id'];

    }

    /**
     * @brief Gets data for client list
     *
     * @return Array
     */
    protected function getListData()
    {

        $query = "SELECT * FROM ".XNAME."_{$this->table}";
        $sql =$this->db->dataset($query);

        return $sql;


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