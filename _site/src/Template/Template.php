<?php
/**
 * Templates class
 *
 * First set up for Simplistic Data Solutions
 *
 * @author Daniel Beard <daniel@brunel-encantado.com>
 */

 namespace Brunelencantado\Template;
 
 
 class Template
 {
	 protected $templatePath;
	 protected $extension = '.php';
	 
	 
	 public function __construct($path = 'lib/views/')
	 {
		 $this->templatePath = $path;
	 }
	 
	 /**
	  *  @brief Brief
	  *  
	  *  @param [in] $template What template to render
	  *  @param [in] $data Data for template
	  *  
	  *  @return Template content
	  *
	  */
	 public function render($template, $data)
	 {
		 ob_start();
		 if(file_exists($this->templatePath.$template.$this->extension)){
			include($this->templatePath.$template.$this->extension);
		} else {
			throw new \Exception('Template file not found:'. $this->templatePath.$template.$this->extension);
		}
		return ob_get_clean();
	 }
	 
	 /**
	  *  @brief Template path setter
	  *  
	  *  @param [in] $path 

	  */
	 public function setTemplatePath($path)
	 {
		 $this->templatePath = $path;
	 }
	 
	 /**
	  *  @brief Template path getter
	  *  
	  *  @param [in] $path 

	  */
	 public function getTemplatePath($path)
	 {
		 return $this->templatePath;
	 }
	 
 }
 
 
 
 
 
 
 
 
 
 
 // End of file