<?php

namespace FkFramework\Mvc;

use FkFramework\Manager\AbstractManager;
use FkFramework\Mvc\Application;
use FkFramework\Config\Config;

abstract class AbstractController extends AbstractManager {

	protected $application;

	public function __construct(Application $application) {
		$this->application = $application;

		foreach (Config::read('components') as $name => $callback) {
			if(is_string($callback)) {
				$this->load($name, new $callback($this->application));
			}
			else {
				$this->load($name, $callback($this->application));
			}
		}

		$this->onBoostrap();
	}

	public function onBoostrap() {
		// A surcharger
	}

	public function __get($attribut) {
		return (parent::isLoaded($attribut)) ?
			parent::get($attribut):
			false;
	}

	// public function render($vars = null) {
	// 	if(parent::isLoaded('Response'))
	// 		parent::get('Response')->send($vars);
	// }

	// public function redirect($url) {
	// 	if(parent::isLoaded('Response'))
	// 		parent::get('Response')->redirect($url);
	// }

	public function getApplication() {
		return $this->application;
	}

	public function getModuleConfig() {
        return $this->application->getModuleManager()->get($this->application->getRequest()->getRoute()->getModuleName())->getModuleConfig();
	}

}