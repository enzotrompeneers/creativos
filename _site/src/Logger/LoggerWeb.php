<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Brunelencantado\Logger;

class LoggerWeb extends Logger {
    
    public function __construct($logfile = null) 
    {
		
		parent::__construct($logfile);
		$this->log = file_get_contents(__DIR__ . '/styles.php');
		$this->log .= '<table width="100%" border="0" id="web-logger">' . PHP_EOL;
		$this->log .= '<tr>' . PHP_EOL;
		$this->log .= '<th width="20%"></th>' . PHP_EOL;
		$this->log .= '<th width="1%"></th>' . PHP_EOL;
		$this->log .= '<th width="9%"></th>' . PHP_EOL;
		$this->log .= '<th width="70%"></th>' . PHP_EOL;
		$this->log .= '</tr>' . PHP_EOL;
		
    }

    public function __destruct() 
    {
		$this->log .= '</table>' . PHP_EOL;
		
		
		parent::__destruct();
    }
    
    public function write($type, $msg)
    {
		$now = date(DATE_W3C);
		$fmt = "%s %s (% 8d Kb): %s\n";
		
		$this->log .= '<tr class="' . $type . '"><td valign="top">' . $now . '</td><td valign="top">' . $type . '</td><td valign="top">' . (memory_get_usage(TRUE)/1024) . ' Kb</td><td valign="top">' . $msg . '</td></tr>' . PHP_EOL;

    }
	
	public function showLog()
	{
		return $this->log;
	}
}

/* end-of-file */