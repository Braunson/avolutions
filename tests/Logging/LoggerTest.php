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

use PHPUnit\Framework\TestCase;

use Avolutions\Config\Config;
use Avolutions\Di\Container;
use Avolutions\Logging\Logger;
use Avolutions\Logging\LogLevel;

class LoggerTest extends TestCase
{
    private $logFile = '';
    private $logMessage = 'This is a log message with log level ';
    private $Logger;

    protected function setUp(): void
    {
        $Container = Container::getInstance();
        $Config = $Container->get("Avolutions\Config\Config");
        $this->Logger = $Container->get("Avolutions\Logging\Logger");

        $this->logFile = $Config->get("logger/logpath").$Config->get("logger/logfile");
    }

    public function testLoggerWithLogLevelEmergency()
    {
        $message = $this->logMessage.LogLevel::EMERGENCY;

        $this->Logger->emergency($message);

        $logfileContent = file_get_contents($this->logFile);

        $this->assertStringContainsString($message, $logfileContent);
    }

    public function testLoggerWithLogLevelAlert()
    {
        $message = $this->logMessage.LogLevel::ALERT;

        $this->Logger->alert($message);

        $logfileContent = file_get_contents($this->logFile);

        $this->assertStringContainsString($message, $logfileContent);
    }

    public function testLoggerWithLogLevelCritical()
    {
        $message = $this->logMessage.LogLevel::CRITICAL;

        $this->Logger->critical($message);

        $logfileContent = file_get_contents($this->logFile);

        $this->assertStringContainsString($message, $logfileContent);
    }

    public function testLoggerWithLogLevelError()
    {
        $message = $this->logMessage.LogLevel::ERROR;

        $this->Logger->error($message);

        $logfileContent = file_get_contents($this->logFile);

        $this->assertStringContainsString($message, $logfileContent);
    }

    public function testLoggerWithLogLevelWarning()
    {
        $message = $this->logMessage.LogLevel::WARNING;

        $this->Logger->warning($message);

        $logfileContent = file_get_contents($this->logFile);

        $this->assertStringContainsString($message, $logfileContent);
    }

    public function testLoggerWithLogLevelNotice()
    {
        $message = $this->logMessage.LogLevel::NOTICE;

        $this->Logger->notice($message);

        $logfileContent = file_get_contents($this->logFile);

        $this->assertStringContainsString($message, $logfileContent);
    }

    public function testLoggerWithLogLevelInfo()
    {
        $message = $this->logMessage.LogLevel::INFO;

        $this->Logger->info($message);

        $logfileContent = file_get_contents($this->logFile);

        $this->assertStringContainsString($message, $logfileContent);
    }

    public function testLoggerWithLogLevelDebug()
    {
        $message = $this->logMessage.LogLevel::DEBUG;

        $this->Logger->debug($message);

        $logfileContent = file_get_contents($this->logFile);

        $this->assertStringContainsString($message, $logfileContent);
    }
}