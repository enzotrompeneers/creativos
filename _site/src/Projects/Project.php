<?php

namespace Brunelencantado\Projects;

class Project
{
     protected $db;
     protected $data = [];
     protected $table = 'proyectos';

    public function __construct($db,$clave) {
          $this->db = $db;
          $this->id = $this->getId($clave);
    }
    
     public function getId($clave) {

          $query = "SELECT id FROM ".XNAME."_{$this->table} WHERE clave LIKE '%{$clave}%';";

          $sql = $this->db->dataset($query);

          $output = $this->processData($sql);

          return $output;
    }

    protected function getData($clave) {

        $query = "
                SELECT pro.*, pro.nombre_".LANGUAGE." AS nombre,
                pro.descr_".LANGUAGE." AS descripcion,
                cat.nombre_".LANGUAGE." AS categoria
                
                FROM ".XNAME."_proyectos pro
                LEFT JOIN ".XNAME."_categorias cat
                ON pro.categoria_id = cat.id
                WHERE pro.clave LIKE '%{$clave}%';
                ";

        $sql = $this->db->dataset($query);
		
        $output = $this->processData($sql);

        return $output;

    }

    protected function processData($data)
    {

        $output = [];

        $n = 0;
        foreach ($data as $item) {
       
            $output[$n] = $item;
            $n++;

        }

        return $output;

    }

    public function getDetails($clave) {
        
     $data = $this->getData($clave);

     $output = array();

     foreach($data as $k=>$v) {            
          $output['id'] = $v['id'];
          $output['clave'] = $v['clave'];
          $output['nombre'] = $v['nombre'];
          $output['descripcion'] = $v['descripcion'];
          $output['categoria'] = $v['categoria'];
          $output['url'] = $v['url'];
          $output['thumbnail'] = $v['thumbnail'];
          $output['imagenes'] = $this->getImages();
          $output['colores'] = $this->getColours();
     }

     return $output;
     }

     private function getImages()
	{
		$query = "SELECT parent_id, file_name FROM ".XNAME."_images_{$this->table} WHERE parent_id = {$this->id[0]['id']} ORDER BY orden";
		$sql = $this->db->dataset($query);
		
		$output = array();
		$n = 0;
		foreach ($sql as $k => $v) {
	
			//$output[$n]['g'] = (mb_substr($v['file_name'], 0, 4) == 'http') ? $v['file_name'] : 'images/proyectos/' . $v['parent_id'] . '/g_' . $v['file_name'];
			$output[$n]['g'] = (mb_substr($v['file_name'], 0, 4) == 'http') ? $v['file_name'] : 'images/proyectos/' . $v['parent_id'] . '/' . $v['file_name'];
			//$output[$n]['l'] = (mb_substr($v['file_name'], 0, 4) == 'http') ? $v['file_name'] : 'images/proyectos/' . $v['parent_id'] . '/l_' . $v['file_name'];
			$output[$n]['m'] = (mb_substr($v['file_name'], 0, 4) == 'http') ? $v['file_name'] : 'images/proyectos/' . $v['parent_id'] . '/m_' . $v['file_name'];
			//$output[$n]['s'] = (mb_substr($v['file_name'], 0, 4) == 'http') ? $v['file_name'] : 'images/proyectos/' . $v['parent_id'] . '/s_' . $v['file_name'];
			
			$n++;
		}
		
		return $output;
     }
     
     private function getColours() {
		$query = "SELECT parent_id, hex FROM ".XNAME."_colores WHERE parent_id = {$this->id[0]['id']} ORDER BY orden";
		$sql = $this->db->dataset($query);
		
		$output = array();
		$n = 0;
		foreach ($sql as $k => $v) {
			
			$output[$n] = $v['hex'];
			
			$n++;
		}
		
		return $output;
	}

}