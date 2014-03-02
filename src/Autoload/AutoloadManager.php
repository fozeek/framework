<?php

namespace Fk\Autoload;

class AutoLoadManager {

    private $_registers = array();

    public function __construct() {
        spl_autoload_register(array($this, 'factory'));
    }

    public function addRegister($module, $register) {
        $this->_registers[$module] = $register;
    }

    public function getRegisters() {
        return $this->_registers;
    }

    public function getRegister($module) {
        return $this->_registers[$module];
    }

    public function factory($class) {
        $explode = explode("\\", $class);
        $file = end($explode);
        $result = $this->getRegister($explode[0]);
        $path = $result["root_path"];
        foreach ($folders = array_slice($explode, 1, -1) as $key => $folder) {
            if(is_array($result) && array_key_exists($folder, $result['childs'])) {
                $result = $result['childs'][$folder];
                unset($folders[$key]);
                if(is_string($result)) {
                    $path .= $result;
                    return $this->requireClass($path, $folders, $file);
                }
                else
                    $path .= $result["root_path"];
            }
            else
                return $this->requireClass($path, $folders, $file);
        }
        return $this->requireClass($path, $folders, $file);
    }

    public function requireClass($path, $folders, $file) {
        if(file_exists($path.strtolower($file).".php")) {
            require_once($path.strtolower($file).".php");
        }
        elseif(file_exists($path.strtolower(implode("/", $folders))."/".strtolower($file).".php")) {
            require_once($path.strtolower(implode("/", $folders))."/".strtolower($file).".php");
        }
        else
            return false;
        return true;
    }
}