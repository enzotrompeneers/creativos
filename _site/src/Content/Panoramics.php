<?php
/**
 * Panoramic listing class
 *
 * @author Daniel Beard / BE Creativos <daniel@creativos.be>
 */

namespace Brunelencantado\Content;

use Brunelencantado\Database\DbInterface;

class Panoramics
{

    protected $db;
    protected $table = XNAME . '_panoramicas';

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
     * @brief Gets listof panoramics
     *
     * @param Integer $limit Optional
     * @return Array
     */
    public function getList($limit = null)
    {

        $sql = $this->getData($limit);

        $output = [];
        foreach ($sql as $k => $v){

            $output[$k] = $v;
            $output[$k]['img'] = 'images/panoramicas/' . $v['id'] . '/p_' . $v['file_name'];
            $output[$k]['med'] = 'images/panoramicas/' . $v['id'] . '/m_' . $v['file_name'];

        }
        
        return $output;

    }

    /**
     * @brief Gets pano raw data from database
     *
     * @param Integer $limit Optional
     * @return Array
     */
    protected function getData($limit = null)
    {

        $query = "SELECT *, titulo_".LANGUAGE." AS titulo FROM {$this->table} ORDER BY orden";
        $sql = $this->db->dataset($query);

        return $sql;

    }

    /**
     * @brief Deletes a pano by id
     *
     * @param Integer $id
     * @return Void
     */
    public function delete($id)
    {

        // Delete from db
        $query = "DELETE FROM {$this->table} WHERE id = {$id}";
        $this->db->query($query);

        // Delete files & folder
        $dirname = dirname(__FILE__) . '/../../images/panoramicas/' . $id . '/';
        delete_directory($dirname);

    }

    /**
     * @brief Sorts panos in the order the ids arrive
     *
     * @param Array $idArray Ids in order required
     * @return Void
     */
    public function sort(array $idArray)
    {

        foreach ($idArray as $k => $v){
            
            $id = explode('_', $v)[1];
            $orden = $k + 1;

            $query = "UPDATE {$this->table} SET orden = {$orden} WHERE id = {$id}";
            printout($query);
            $this->db->query($query);

        }

    }

    /**
     * @brief Gets texts from a pano
     *
     * @param Integer $id
     * @param Array $languages
     * @return Array Texts in format: [ 'es' => [ 'text' => Text, 'link' => Link ] ]
     */
    public function getTexts($id, array $languages)
    {

        $query = "SELECT * FROM {$this->table} WHERE id = {$id}";
        $sql = $this->db->record($query);

        $output = [];
        foreach ($languages as $language) {

           $output[$language]['text'] = $sql['titulo_' . $language];
           $output[$language]['link'] = $sql['link_' . $language];

        }
        
        return $output;

    }

    /**
     * @brief Saves pano texts
     *
     * @param Array $texts
     * @param Integer $id
     * @return Void
     */
    public function saveText(array $texts, $id)
    {

        if (empty($texts)) return;

        foreach ($texts as $k => $v) {

            $query = "UPDATE {$this->table} SET {$k} = '{$v}' WHERE id = {$id}";
            $this->db->query($query);

        }

    }

}