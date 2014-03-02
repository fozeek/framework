<?php

namespace FkFramework\Module;

use FkFramework\Manager\AbstractManager;
use FkFramework\Mvc\Application;
use FkFramework\Config\Config;
use FkFramework\Route\Router;

class ModuleManager extends AbstractManager {

	protected $autoloadManager;
	protected $eventManager;

	public function __construct($autoloadManager, $eventManager) {
		$this->autoloadManager = $autoloadManager;
		$this->eventManager = $eventManager;
		foreach (Config::read('modules') as $name => $dir) {
            $this->loadModule($name, $dir);
        }
	}

	private function loadModule($name, $dir) {
		require $dir.'Module.php';
		$moduleName = '\\'.ucfirst($name).'\Module';
		$config = new $moduleName();
		
		if(in_array(__NAMESPACE__.'\ModuleInterface', array_keys(class_implements($config)))) {
			// Gestion des routes
			Router::addModuleRouteConfig($config->getRouteConfig());

			// Gestion de l'autoload
			$this->autoloadManager->addRegister($name, $config->getAutoloadConfig());

			// Gestion des triggers d'events
			foreach ($config->getListenerConfig() as $key => $data) {
				$this->eventManager()->attach($key, $data);
			}
			$this->load($name, $config);

			// Gestion des configs intra-module
			// $config->getModuleConfig()
		}
	}

}