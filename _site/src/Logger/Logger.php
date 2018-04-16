<?php
/**
 * lib.logger.php
 * Copyright (C)2014  Jose Perez Martinez <jose@brunel-encantado.com> 
 * 
 * Clase para escribir logs a un fichero de texto
 * 
 */
 
namespace Brunelencantado\Logger;
 
class Logger 
{
    const STDOUT    = 'php://stdout';	
    const ERROR	    = 'error';
    const WARNING   = 'warn';
    const INFO	    = 'info'; 
    const SUCCESS   = 'success';
    const SQL	    = 'sql';
	
    protected $config	= array(
		self::ERROR		=> TRUE, 
		self::WARNING	=> TRUE, 
		self::INFO		=> TRUE, 
		self::SUCCESS	=> TRUE,
		self::SQL		=> TRUE
    );
    
    protected $logTypeName    = array(
		self::ERROR     => '[ERROR]  ', 
		self::WARNING	=> '[WARNING]', 
		self::INFO		=> '[INFO]   ', 
		self::SUCCESS	=> '[SUCCESS]',
		self::SQL		=> '[SQL]    '
    );
	
	protected $logfile;
	protected $log;
	
    public function __construct($logfile) 
    {
		
		if ($logfile){
			$this->logfile = fopen ($logfile, 'w');
		}
	
    }

    public function __destruct() 
	
    {			
		if ($this->logfile) {			    
			fclose ($this->logfile);			
		}
    }

    public function config($key, $value)
    {			if (key_exists($key, $this->config)) {				$this->config[$key] = $value;			}
    }

    public function write($type, $msg)
    {			
		$now = date(DATE_W3C);			$fmt = "%s %s (% 8d Kb): %s\n";
		if ($this->config[$type]) { fprintf ($this->logfile, $fmt, $now, $this->logTypeName[$type], memory_get_usage(TRUE)/1024, $msg);	}
    }
}

/* end-of-file */