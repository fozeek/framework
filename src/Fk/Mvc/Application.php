<?php

namespace Fk\Mvc;

use Fk\Event\EventManager;
use Fk\Module\ModuleManager;
use Fk\AutoLoad\AutoloadManager;
use Fk\Cache\Cache;
use Fk\Uri\Uri;
use Fk\Route\Router;
use Fk\Http\Request;
use Fk\Config\Config;

class Application {

    private static $application;
    
    // Resources
    protected $request;                  // Fk\Http\Request;
    protected $cache;                    // Fk\Cache\Cache
    protected $controller;               // Fk\Mvc\AbstractController

    // Managers
    protected $eventManager;             // Fk\Event\EventManager
    protected $moduleManager;            // Fk\Module\ModuleManager
    protected $autoLoadManager;          // Fk\Autoload\AutoloadManager

    static public function run($uri = null) {
        $application = new Application();
        $application->setCache(new Cache(Config::read('kernel.cache.path'), Config::read('kernel.cache.duration')))
            ->setEventManager(new EventManager())
            ->setAutoLoadManager(new AutoLoadManager())
            ->setModuleManager(new ModuleManager(
               $application->getAutoloadManager(), 
               $application->getEventManager())
            )
            ->setRequest(new Request(new Uri($uri)))
            ->dispatch();
    }

    public function _construct() {
        return $this;
    }

    public function getCache() {
        return $this->_cache;
    }

    public function setCache(Cache $cache) {
        $this->_cache = $cache;
        return $this;
    }
    
    public function getRequest(){
        return $this->request;
    }

    public function SetRequest(Request $request){
        $this->request = $request;
        return $this;
    }

    public function getController() {
        return $this->controller;
    }

    public function setController(AbstractController $controller) {
        $this->controller = $controller;
        return $this;
    }

    public function getEventManager() {
        return $this->eventManager;
    }

    public function setEventManager(EventManager $eventManager) {
        $this->eventManager = $eventManager;
        return $this;
    }

    public function getModuleManager() {
        return $this->moduleManager;
    }

    public function setModuleManager(ModuleManager $moduleManager) {
        $this->moduleManager = $moduleManager;
        return $this;
    }

    public function getAutoLoadManager() {
        return $this->autoLoadManager;
    }

    public function setAutoLoadManager(AutoLoadManager $autoLoadManager) {
        $this->autoLoadManager = $autoLoadManager;
        return $this;
    }

    private function dispatch() {
        $route = $this->getRequest()->getRoute();
        $controllerName = $route->getControllerName();
        $controller = new $controllerName($this);
        $this->setController($controller);
        $actionName = $route->getActionName();
        $controller->$actionName($route->getParams());
    }
}
