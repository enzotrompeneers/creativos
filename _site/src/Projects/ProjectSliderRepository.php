<?php

namespace Brunelencantado\Projects;

use Brunelencantado\Database\DbInterface;

class ProjectSliderRepository
{

    protected $db;
    protected $data = [];
	protected $imagePath = 'images/proyectos/';

    public function __construct(DbInterface $db)
    {

        $this->db = $db;
        $this->data = $this->getData();

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



    protected function getData()
    {

        $query = "
                SELECT pro.*, pro.nombre_".LANGUAGE." AS nombre,
                pro.descr_".LANGUAGE." AS descripcion,
                cat.nombre_".LANGUAGE." AS categoria               
                FROM ".XNAME."_proyectos_slider pro
                LEFT JOIN ".XNAME."_categorias cat
                ON pro.categoria_id = cat.id
                ORDER BY pro.orden
                ";

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

    public function getList() {
        
        $data = $this->getData();

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
            $output[$n]['colores'] = $this->getColours($v['id']);
            $n++;
        }

        return $output;
    }

    private function getImages() {

         $query = "SELECT parent_id, file_name FROM ".XNAME."_images_proyectos_slider WHERE parent_id = {$this->id[0]['id']} ORDER BY orden";
         $sql = $this->db->dataset($query);
         
         $output = array();
         $n = 0;
         foreach ($sql as $k => $v) {
              
              $output[$n]['g'] = (mb_substr($v['file_name'], 0, 4) == 'http') ? $v['file_name'] : 'images/viviendas/' . $v['parent_id'] . '/g_' . $v['file_name'];
              $output[$n]['l'] = (mb_substr($v['file_name'], 0, 4) == 'http') ? $v['file_name'] : 'images/viviendas/' . $v['parent_id'] . '/l_' . $v['file_name'];
              $output[$n]['m'] = (mb_substr($v['file_name'], 0, 4) == 'http') ? $v['file_name'] : 'images/viviendas/' . $v['parent_id'] . '/m_' . $v['file_name'];
              $output[$n]['s'] = (mb_substr($v['file_name'], 0, 4) == 'http') ? $v['file_name'] : 'images/viviendas/' . $v['parent_id'] . '/s_' . $v['file_name'];
              
              $n++;
         }
         
         return $output;
    }
    
}