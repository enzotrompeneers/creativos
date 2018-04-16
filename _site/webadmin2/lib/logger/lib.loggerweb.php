<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once 'lib.logger.php';

class LoggerWeb extends Logger {
    
    public function __construct($logfile) 
    {
	parent::__construct($logfile);
	
	if (!pTEXT) {
		echo '<table width="100%" border="0">', PHP_EOL;
		echo '<tr>', PHP_EOL;
		echo '<th width="20%"></th>', PHP_EOL;
		echo '<th width="1%"></th>', PHP_EOL;
		echo '<th width="9%"></th>', PHP_EOL;
		echo '<th width="70%"></th>', PHP_EOL;
		echo '</tr>', PHP_EOL;
	}
    }

    public function __destruct() 
    {
	parent::__destruct();
	if (!pTEXT) {
		echo '</table>', PHP_EOL;
	}
    }
    
    public function write($type, $msg)
    {
	$now = date(DATE_W3C);
	$fmt = "%s %s (% 8d Kb): %s\n";
	if (!pTEXT) {
	    echo '<tr class="', $type, '"><td valign="top">', $now, '</td><td valign="top">', $type, '</td><td valign="top">' , ( memory_get_usage(TRUE)/1024), ' Kb</td><td valign="top">', $msg, '</td></tr>', PHP_EOL;
	} else {
		parent::write($type, $msg);
		
	}
    }
}

/* end-of-file */