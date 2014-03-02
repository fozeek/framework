<?php

namespace Fk\Resource\Log;

use Fk\Resource\Kernel;

class Logger {

    private $logFile;

    public static function log($level, $message, array $context = array()) {
        $logger = new Logger();
        $logger->logFile = Kernel::path("log")."logs";
        return $logger->$level($message, $context);
    }

    public function debug($message, array $context = array()) {
        return (file_put_contents($this->logFile, "DEBUG : ".$this->interpolate($message, $context)."\n", FILE_APPEND)) ?
            true:
            false;
    }   

    public function info($message, array $context = array()) {
        return (file_put_contents($this->logFile, "INFO : ".$this->interpolate($message, $context)."\n", FILE_APPEND)) ?
            true:
            false;
    }   

    public function notice($message, array $context = array()) {
        return (file_put_contents($this->logFile, "NOTICE : ".$this->interpolate($message, $context)."\n", FILE_APPEND)) ?
            true:
            false;
    }   

    public function warning($message, array $context = array()) {
        return (file_put_contents($this->logFile, "WARNING : ".$this->interpolate($message, $context)."\n", FILE_APPEND)) ?
            true:
            false;
    }   

    public function error($message, array $context = array()) {
        return (file_put_contents($this->logFile, "ERROR : ".$this->interpolate($message, $context)."\n", FILE_APPEND)) ?
            true:
            false;
    }   

    public function critical($message, array $context = array()) {
        return (file_put_contents($this->logFile, "CRITICAL : ".$this->interpolate($message, $context)."\n", FILE_APPEND)) ?
            true:
            false;
    }   

    public function alert($message, array $context = array()) {
        return (file_put_contents($this->logFile, "ALERT : ".$this->interpolate($message, $context)."\n", FILE_APPEND)) ?
            true:
            false;
    }   

    public function emergency($message, array $context = array()) {
        return (file_put_contents($this->logFile, "EMERGENCY : ".$this->interpolate($message, $context)."\n", FILE_APPEND)) ?
            true:
            false;
    }

    function interpolate($message, array $context = array())
    {
        // build a replacement array with braces around the context keys
        $replace = array();
        foreach ($context as $key => $val) {
            $replace['{' . $key . '}'] = $val;
        }

        // interpolate replacement values into the message and return
        return strtr($message, $replace);
    }

}