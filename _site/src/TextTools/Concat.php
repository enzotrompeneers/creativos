<?php
/**
 * JSTools concatenete class
 *
 * Receives js link strings and concatenates them into just one script
 *
 * @author Daniel Beard <daniel@brunel-encantado.com>
 */

namespace Brunelencantado\TextTools;

class Concat
{
	protected $output;
	public $links = array();
	
    /**
     * Create a new Instance
     */
    public function __construct($output = 'js/concat.js')
    {
		$this->output = $output;
    }
	
	public function __toString() 
	{
		foreach ($this->links as $l){
			$return .= $l.'<br />'."\n";
		}
		return $return;
	}	

    /**
     * Adds a link to the object
     */	
	public function add($link)
	{
		array_push($this->links,$link);
	}
	
	 /**
     *  @brief Concatenates js files and creates file if it does not exist. In debug mode it always saves the file
     */	
	public function concat($debug = false)
	{
		$filepath = $this->output;
		
		

		if (!file_exists($filepath) || $debug == true){
			$out = fopen($filepath, "w");
			foreach($this->links as $file){
				
				fwrite($out, '//******----- ' . $file . ' -----******//' . "\n");
				$in = fopen($file, "r");
				while ($line = fgets($in)){
				   fwrite($out, $line . "\n\r");
				}
			  fclose($in);
		  }
			//Then clean up
			fclose($out);
		}
		
		return $this->output;
	}
	
	/**		

	 *  @brief Adds all the default js
	 *  
	 *  @return void
	 */
	public function addJavascriptDefaults()
	{
		// Core plugins
		$this->add('plugins/jquery.min.js');
		$this->add('plugins/jquery-migrate.min.js');
		$this->add('plugins/jquery-ui/jquery-ui-1.10.3.custom.min.js');
		$this->add('plugins/bootstrap/js/bootstrap.min.js');
		$this->add('plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js');
		$this->add('plugins/uniform/jquery.uniform.min.js');
		$this->add('plugins/bootstrap-switch/js/bootstrap-switch.min.js');
		$this->add('plugins/ajaxform/jquery.form.js');
		$this->add('js/metronic.js');
		$this->add('js/layout.js');
		$this->add('js/quick-sidebar.js');
		$this->add('plugins/jquery-slimscroll/jquery.slimscroll.min.js');
	}
	
	/**
	 *  @brief Adds all the default css
	 *  
	 *  @return void
	 */
	public function addCssDefaults()
	{
		// Core css
		$this->add('http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all');
		$this->add('plugins/bootstrap/css/bootstrap.min.css');
		$this->add('plugins/uniform/css/uniform.default.css');
		$this->add('plugins/select2/select2.css');
		$this->add('plugins/bootstrap-switch/css/bootstrap-switch.min.css');
		$this->add('css/components-rounded.css');
		$this->add('css/plugins.css');
		$this->add('css/layout.css');
		$this->add('css/themes/darkblue.css');
		$this->add('css/custom.css');
	}
	
	private function minify($code)
	{
		$output = str_replace(array("\n","\r"), '', $code);
		$output = preg_replace('!\s+!', ' ', $output);
		$output = str_replace(array(' {',' }', '{ ','; '), array('{','}','{',';'), $output);
		return $output;
		
	}

}


// End of file