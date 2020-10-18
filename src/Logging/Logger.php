<?php
/**
 * AVOLUTIONS
 * 
 * Just another open source PHP framework.
 * 
 * @copyright	Copyright (c) 2019 - 2020 AVOLUTIONS
 * @license		MIT License (http://avolutions.org/license)
 * @link		http://avolutions.org
 */
 
namespace Avolutions\Logging;

use Avolutions\Config\Config;
use Avolutions\Logging\LogLevel;

/**
 * Logger class
 *
 * The Logger class writes messages with a specific level to a logfile.
 *
 * @author	Alexander Vogt <alexander.vogt@avolutions.org>
 * @since	0.1.0
 */
class Logger
{	
    /**
     * TODO
     */
    private $Config;

    /**
     * TODO
     */
    private $datetimeFormat;

    /**
     * TODO
     */
    private $logpath;

    /**
     * TODO
     */
    private $logfile;

    /**
     * TODO
     */
    private $maxLogLevel;

    /**
	 * @var array $loglevels The loglevels in ascending order of priority.
	 */
    private $loglevels = [
        LogLevel::DEBUG,
        LogLevel::INFO,
        LogLevel::NOTICE,
        LogLevel::WARNING,
        LogLevel::ERROR,
        LogLevel::CRITICAL,
        LogLevel::ALERT,
        LogLevel::EMERGENCY
    ];

    /**
     * TODO
     */
    public function __construct(Config $Config)
    {
        $this->Config = $Config;

		$this->logpath = $this->Config->get('logger/logpath');		
        $this->logfile = $this->Config->get('logger/logfile');
        $this->maxLogLevel = $this->Config->get('logger/loglevel');	
		$this->datetimeFormat = $this->Config->get('logger/datetimeFormat');	
        
		if (!is_dir($this->logpath)){
			mkdir($this->logpath, 0755);
		}
    }

	/**
	 * log
	 *
	 * Opens the logfile and write the message and all other informations
	 * like date, time, level to the file.
	 *
	 * @param string $logLevel The log level
	 * @param string $message The log message
	 */
    private function log($logLevel, $message)
    {		     
        // only log message if $loglevel is greater or equal than the loglevel from config
		if (array_search($logLevel, $this->loglevels) < array_search($this->maxLogLevel, $this->loglevels)) {
            return;
        }
						
		$datetime = new \Datetime();
		$logText = '['.$logLevel.'] | '.$datetime->format($this->datetimeFormat).' | '.$message;
														
		$handle = fopen($this->logpath.$this->logfile, 'a');
		fwrite($handle, $logText);
		fwrite($handle, PHP_EOL);
		fclose($handle);
	}	
	
	/**
	 * emergency
	 *
	 * Writes the passed message with level "EMERGENCY" to the logfile.
	 * 
	 * @param string $message The message to log
	 */
    public function emergency($message)
    {
		$this->log(LogLevel::EMERGENCY, $message);
	}
	
	/**
	 * alert
	 *
	 * Writes the passed message with level "ALERT" to the logfile.
	 * 
	 * @param string $message The message to log
	 */
    public function alert($message)
    {
		$this->log(LogLevel::ALERT, $message);
	}
	
	/**
	 * critical
	 *
	 * Writes the passed message with level "CRITICAL" to the logfile.
	 * 
	 * @param string $message The message to log
	 */
    public function critical($message)
    {
		$this->log(LogLevel::CRITICAL, $message);
	}	
	
	/**
	 * error
	 *
	 * Writes the passed message with level "ERROR" to the logfile.
	 * 
	 * @param string $message The message to log
	 */
    public function error($message)
    {
		$this->log(LogLevel::ERROR, $message);
	}	
	
	/**
	 * warning
	 *
	 * Writes the passed message with level "WARNING" to the logfile.
	 *
	 * @param string $message The message to log
	 */
    public function warning($message)
    {
		$this->log(LogLevel::WARNING, $message);
	}
	
	/**
	 * notice
	 *
	 * Writes the passed message with level "NOTICE" to the logfile.
	 *
	 * @param string $message The message to log
	 */
    public function notice($message)
    {
		$this->log(LogLevel::NOTICE, $message);
	}
	
	/**
	 * info
	 *
	 * Writes the passed message with level "INFO" to the logfile.
	 *
	 * @param string $message The message to log
	 */
    public function info($message)
    {
		$this->log(LogLevel::INFO, $message);
	}	 
	
	/**
	 * debug
	 *
	 * Writes the passed message with level "DEBUG" to the logfile.
	 *
	 * @param string $message The message to log
	 */
    public function debug($message)
    {
        $this->log(LogLevel::DEBUG, $message);
	}
}