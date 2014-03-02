<?php

namespace FkFramework\Mvc\Component;

use FkFramework\Manager\AbstractManager;
use FkFramework\Config\Config;

class ComponentManager extends AbstractManager {

	public function __construct() {
		foreach (Config::read('components') as $key => $className) {
			$this->load($key, new $className());
		}
	}
	
}