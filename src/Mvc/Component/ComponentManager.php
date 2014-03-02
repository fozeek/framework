<?php

namespace Fk\Mvc\Component;

use Fk\Manager\AbstractManager;
use Fk\Config\Config;

class ComponentManager extends AbstractManager {

	public function __construct() {
		foreach (Config::read('components') as $key => $className) {
			$this->load($key, new $className());
		}
	}
	
}