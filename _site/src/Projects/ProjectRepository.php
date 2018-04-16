<?php

namespace Brunelencantado\Projects;

use Brunelencantado\Database\DbInterface;

class ProjectRepository
{

    protected $db;
    protected $data = [];
	protected $imagePath = 'images/proyectos/';

    public function __construct(DbInterface $db, $clave = null)
    {

        $this->db = $db;
        $this->data = $this->getData($clave);

    }
    
    public function getItem($clave)
    {

        return $this->data[$clave];

    }

    public function getName($clave)
    {

        return $this->data[$clave]['nombre'];

    }

    public function getCategory($clave)
    {

        return $this->data[$clave]['categoria'];

    }

    public function getUrl($clave)
    {

        return $this->data[$clave]['url'];

    }

    public function getId($clave)
    {

        return $this->data[$clave]['id'];

    }
		
    public function getThumbnail($clave)
    {

        return $this->imagePath . $this->getId($clave) . '/' . $this->data[$clave]['thumbnail'];

    }
	
    public function getLink($clave)
    {

        return $this->imagePath . $this->getId($clave) . '/' . $this->data[$clave]['thumbnail'];

    }

    protected function getData($clave)
    {
         if ($clave == null) {
               $query = "
                    SELECT pro.*, pro.nombre_".LANGUAGE." AS nombre,
                    pro.descr_".LANGUAGE." AS descripcion,
					pro.color AS color,
                    cat.nombre_".LANGUAGE." AS categoria               
                    FROM ".XNAME."_proyectos pro
                    LEFT JOIN ".XNAME."_categorias cat
                    ON pro.categoria_id = cat.id
                    ORDER BY pro.orden
                    ";
          } else {

               $query = "
                    SELECT pro.*, pro.nombre_".LANGUAGE." AS nombre,
                    pro.descr_".LANGUAGE." AS descripcion,
					pro.color AS color,
                    cat.nombre_".LANGUAGE." AS categoria               
                    FROM ".XNAME."_proyectos pro
                    LEFT JOIN ".XNAME."_categorias cat
                    ON pro.categoria_id = cat.id
                    WHERE pro.clave NOT LIKE '%{$clave}%'
                    ORDER BY RAND() LIMIT 3;                   
               ";
          }
        $sql = $this->db->dataset($query);
		
        $output = $this->processData($sql);

        return $output;

    }

    protected function processData($data)
    {

        $output = [];

        foreach ($data as $item) {
       
            $output[$item['clave']] = $item;

        }

        return $output;

    }

    public function getList($clave = null) {
        
        $data = $this->getData($clave);

        $output = array();
        
          $n = 0;

        foreach($data as $k=>$v) {            
            $output[$n]['id'] = $v['id'];
            $output[$n]['clave'] = $v['clave'];
            $output[$n]['nombre'] = $v['nombre'];
            $output[$n]['descripcion'] = $v['descripcion'];
            $output[$n]['categoria'] = $v['categoria'];
            $output[$n]['url'] = $v['url'];
            $output[$n]['thumbnail'] = $v['thumbnail'];
            $output[$n]['color'] = $v['color'];
            $n++;
        }

        return $output;
    }

    private function getColours($id) {
          $query = "SELECT parent_id, hex FROM ".XNAME."_colores WHERE parent_id = {$id} ORDER BY orden";
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