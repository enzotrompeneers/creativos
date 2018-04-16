<?php
/**
 * Basic temporary router
 *
 * To organize the webadmin a bit
 *
 * @author Daniel Beard <daniel@creativos.be>
 */

namespace Brunelencantado\Webadmin\Router;

use \Brunelencantado\Webadmin\Router\Request;

class Router
{
	protected $controllerRoute = 'controllers/';	
	protected $viewRoute = 'views/';	
	protected $registry;
	
	public $request;
	
	public function __construct(Request $request)
	{
		$this->request = $request;
	}
	
	/**
	 *  @brief Gets controller from request object
	 *  
	 *  @param [in] $request request object
	 *  @return Return_Description
	 *  
	 *  @details Details
	 */
	public function getController($registry)
	{	
		$this->registry = $registry;
		$controllerFile = $this->controllerRoute . $this->request->controller . '.php';
		if (file_exists($controllerFile)){
			
			require $controllerFile;
			return;

		}
		require $this->controllerRoute . '404.php';
	}
	
	/**
	 *  @brief Requires view
	 *  
	 *  @param [in] $view View to render
	 *  @param [in] $data Data for the view
	 *  @return Returns rendered view
	 *  
	 */
	public function render($view = '404', $data = [])
	{
		$viewFile = $this->viewRoute . $view . '.template.php';
		$registry = $this->registry;
		ob_start();
			if (file_exists($viewFile)){

				$language = $this->request->language;

				require $viewFile;
			} else {
				require $this->viewRoute . '404.template.php';
			}	
		return ob_get_clean();
	}
	
	/**
	 *  Getters & setters
	 */
	public function setRoute($route, $type = 'view')
	{
		if ($type=='view')
		{
			$this->viewRoute = $route;
		} else {
			$this->controllerRoute = $route;
		}
		
	}
}




// End file