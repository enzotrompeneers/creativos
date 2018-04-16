<?php
/**
 * Page rendering class
 *
 * First set up for Miralbó Urbana
 *
 * @author Daniel Beard / BE Creativos <daniel@creativos.be>
 */

namespace Brunelencantado\Content;

use Brunelencantado\Database\DbInterface;

class Page
{

	protected $db;
	protected $clave;
	protected $data = array();
	protected $children = array();

	const META_DESCR_CHARS = 160;
	
	/**
	 * @brief Constructor
	 *  
	 * @param String $slug 
	 * @param DbInterface $db 
	 * @return Void
	 */	
	public function __construct($slug, DbInterface $db)
	{
		$this->db = $db;
		$this->clave = $this->getDataFromSlug($slug);

		if (!$this->clave && $slug != '404') header('location:' . BASE_SITE . '404/');
	}
	
	/**
	 * @brief Gets data from Data
	 *  
	 * @param Integer $property 
	 * @return String
	 */
	public function getProperty($property)
	{
		return $this->data[$property];
	}
	
	/**
	 * @brief Gets page title 
	 *  
	 * @param String $clave Optional
	 * @return String
	 */
	public function title($clave = null)
	{
		
		if (!$clave) {
			
			return $this->data['titulo'];
			
		} 
			
		return $this->getFieldByClave('titulo', $clave);
			
	}

	/**
	 * @brief Gets page body text 
	 *  
	 * @param String $clave Optional
	 * @return String
	 */	
	public function text($clave = null)
	{
		
		if (!$clave) {
			
			$helper = $this->getHelper($this->clave);
			return $helper . $this->data['text'];
			
		}
			
		$helper = $this->getHelper($clave);
		return $helper . $this->getFieldByClave('art', $clave);
			
	}
	
	/**
	 * @brief Gets page link text 
	 *  
	 * @param String $clave Optional
	 * @return String
	 */	
	public function link($clave = null)
	{
		
		if (!$clave) {
			
			return ($this->data['link']) ? $this->data['link'] : '!' . $this->clave;
			
		}
		
		return $this->getFieldByClave('link', $clave);
		
	}
	
	/**
	 * @brief Gets page slug
	 *  
	 * @param String $clave Optional
	 * @return String
	 */	
	public function slug($clave = null)
	{
		
		if (!$clave) {
			
			return $this->data['slug'];
			
		}
		
		return $this->getFieldByClave('slug', $clave);
		
	}
	
	/**
	 * @brief Gets page meta keywords 
	 *  
	 * @param String $clave Optional
	 * @return String
	 */
	public function meta_key($clave = null)
	{
		if (!$clave) {
			
			return ($this->data['meta_key']) ? $this->data['meta_key'] : implode(', ', explode(' ' , $this->data['titulo']));
			
		}
		
		return $this->getFieldByClave('meta_key', $clave);
		
	}
	
	/**
	 * @brief Gets page meta description 
	 *  
	 * @param String $clave Optional
	 * @return String
	 */
	public function meta_descr($clave = null)
	{
		if (!$clave) {
			
			$metaDescr = $this->data['meta_descr'];
			if ($metaDescr != '') return $metaDescr;

			// No meta descr in db
			return $this->shorten($this->data['text']);
		
		}
			
		$metaDescr =  $this->getFieldByClave('meta_descr', $clave);

		if ($metaDescr != '') return $metaDescr;

		// No meta descr in db
		return $this->shorten($this->getFieldByClave('art', $clave));	
		
		
	}
	
	/**
	 * @brief Gets page id
	 *  
	 * @return String
	 */
	public function id()
	{
		return $this->data['id'];
	}

	/**
	 * @brief Gets page key
	 *  
	 * @return String
	 */
	public function key()
	{
		return $this->clave;
	}
	
	/**
	 * @brief Gets all images related to an article
	 *  
	 * @return Array
	 */
	public function getImages($clave = null, $size = 'l')
	{
		$id = ($clave) ? $this->getIdByClave($clave) : $this->data['id'];
		$sql = $this->getImagesData($id);
		
		
		$output = array();
		foreach ($sql as $k => $v){
			$output[] = 'images/articulos/' . $id . '/' . $size . '_' . $v['file_name'];
		}
		
		return $output;
	}
     

     /*
     * @brief Gets all images related to an article without size
     * 
	 * @param String $clave Optional
     * @return Array
     */
     public function getResponsiveImages($clave = null)
	{
		$id = ($clave) ? $this->getIdByClave($clave) : $this->data['id'];
		$sql = $this->getImagesData($id);
		
		$output = array();
		$n = 0;
		foreach ($sql as $k => $v){
			$output[$n]['url']			= 'images/articulos/' . $id . '/';
			$output[$n]['file_name'] 	= $v['file_name'];
			$n++;
		}
		
		return $output;
	}
	
	/**
	 *  @brief Gets all images related to an article
	 *  
	 *  @param String $size Optional, default 'l'
	 *  @return Array
	 */
	public function getImagesDescr($size = 'l')
	{
		$id = $this->data['id'];
		$sql = $this->getImagesData($id);
		
		$output = array();
		$n = 0;
		foreach ($sql as $k => $v){
			
			$output[$n]['file_name'] = 'images/articulos/' . $id . '/' . $size . '_' . $v['file_name'];
			$output[$n]['descr'] = $v['descr'];
			
			$n++;
		}
		
		return $output;
	}
	
	
    /**
     * @brief Gets first image of page
	 * 
     * @param String $size Optional, default 'l'
	 * @param String $clave Optional
     */
	public function getFirstImage($size = 'l', $clave = null)
	{
		
		$imageClave = ($clave) ? $clave : $this->clave;
		
		$query = "	SELECT file_name, art.id AS aid
					FROM ".XNAME."_images_articulos img
					JOIN ".XNAME."_articulos art
						ON img.parent_id = art.id
					WHERE art.clave = '{$imageClave}'";
		$sql = $this->db->record($query);
		
		if (!$sql) return false;

		$imageUrl = BASE_SITE . 'images/articulos/' . $sql['aid'] . '/' . $size . '_' . $sql['file_name'];
		
		return $imageUrl;
		
	}
	
	/**
	 * @brief Gets all files related to an article
	 *  
	 * @return Array
	 */
	public function getFiles()
	{
		$id = $this->data['id'];
		$sql = $this->getFilesData($id);
		
		$output = array();
		foreach ($sql as $k => $v){
			$output[] = $v['file_name'];
		}
		
		return $output;
	}
	
	/**
	 * @brief Gets children data
	 *  
	 * @return Void
	 */
	public function getChildren()
	{
		$query = "	SELECT id, clave, 
					titulo_".LANGUAGE." AS title,
					link_".LANGUAGE." AS link,
					slug_".LANGUAGE." AS slug,
					art_".LANGUAGE." AS text
					FROM ".XNAME."_articulos
					WHERE parent_id = {$this->data['id']}
					ORDER BY orden
					";
		$sql = $this->db->dataset($query);
		
		$data = array();
		foreach ($sql as $k => $v){
			
			
			$data[$v['clave']] = $v;
			
			$helper = $this->getHelper($this->clave . ' -> ' . $v['clave']);
			$data[$v['clave']]['text'] = $helper . $v['text'];
			
			
		}
		$this->children = $data;
		return $this->children;
	}
	
	/**
	 * @brief Gets the images of a child
	 *  
	 * @param String $clave
	 * @param String $size Optional, default 'l'
	 * @return Array 
	 */
	public function getChildImages($clave, $size = 'l')
	{
		$childId = $this->children[$clave]['id'];	
		
		$sql = $this->getImagesData($childId);
		
		$output = array();
		foreach ($sql as $k => $v){
			$output[] = 'images/articulos/' . $childId . '/' . $size . '_' . $v['file_name'];
		}
		
		return $output;
	}
	
	/**
	 * @brief Gets page clave
	 *
	 * @return String Clave
	 */
	public function getClave()
	{

		return $this->clave;

	}

	
	/**
	 * @brief Gets data for page
	 *  
	 * @return Void
	 */
	protected function getData()
	{

		$query = "	SELECT id, clave, parent_id,
					titulo_".LANGUAGE." AS titulo,
					link_".LANGUAGE." AS link,
					slug_".LANGUAGE." AS slug,
					meta_key_".LANGUAGE." AS meta_key,
					meta_descr_".LANGUAGE." AS meta_descr,
					art_".LANGUAGE." AS text
					FROM ".XNAME."_articulos 
					WHERE clave = '{$this->clave}'
					";
		$this->data = $this->db->record($query);

	}
	
	/**
	 * @brief Gets data for page
	 *  
	 * @return Array
	 */
	protected function getDataFromSlug($slug)
	{

		$source = ($slug) ? " WHERE slug_".LANGUAGE." = '{$slug}' " : "WHERE clave = 'inicio'";

		$query = "	SELECT id, clave, parent_id,
					titulo_".LANGUAGE." AS titulo,
					link_".LANGUAGE." AS link,
					slug_".LANGUAGE." AS slug,
					meta_key_".LANGUAGE." AS meta_key,
					meta_descr_".LANGUAGE." AS meta_descr,
					art_".LANGUAGE." AS text
					FROM ".XNAME."_articulos 
					{$source}
					";
		$this->data = $this->db->record($query);

		return $this->data['clave'];
	}
	
	/**
	 * @brief Gets array of images for a given id
	 *  
	 * @param Integer $id id of article
	 * @return Array
	 */
	protected function getImagesData($id)
	{
		$query = "SELECT file_name FROM ".XNAME."_images_articulos WHERE parent_id = {$id} ORDER BY orden";
		return $this->db->dataset($query);
	}
	
	/**
	 * @brief Gets array of files for a given id
	 *  
	 * @param Integer $id id of article
	 * @return Array
	 */
	protected function getFilesData($id)
	{
		return $this->db->dataset("SELECT file_name FROM ".XNAME."_files_articulos WHERE parent_id = {$id} ORDER BY orden");
	}
	
	protected function getHelper($clave)
	{
		$helper = (!empty($_SESSION['Admin']) && $_SESSION['Admin'] == true) ? '<strong style="font-size:.8rem;">(' . $clave . ')</strong> ' : '';
		return $helper;
	}
	
    /**
     * @brief Gets any field from any articulo
     * 
     * @param String $field 
     * @param String $clave 
     * @return String Field
     */
	protected function getFieldByClave($field, $clave)
	{
		
		$query = "SELECT {$field}_".LANGUAGE." AS field FROM ".XNAME."_articulos WHERE clave = '{$clave}'";
		$sql = $this->db->record($query);

		$helper = ($field == 'slug') ? '' : '!';
		
		$output = $sql['field'];
		
		return $output;
		
	}
	
    /**
     * @brief Gets id of articulo by clave
     * 
     * @param String $clave 
     * @return Integer Id
     */
	protected function getIdByClave($clave)
	{
		
		$query = "SELECT id FROM ".XNAME."_articulos WHERE clave = '{$clave}'";
		$sql = $this->db->record($query);
		
		return $sql['id'];
		
	}

	/**
	 * @brief Shortens text
	 *
	 * @param String $text
	 * @return String Shortened text
	 */
	protected function shorten($text)
	{

		$shortened = strip_tags($text);
		$shortened = str_replace('"', '', $shortened);
		$shortened = substr($shortened, 0, self::META_DESCR_CHARS);

		return $shortened;

	}
	
}


// End of file