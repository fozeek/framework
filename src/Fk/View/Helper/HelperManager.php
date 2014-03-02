<?php

namespace Fk\View\Helper;

use Fk\Manager\AbstractManager;

class HelperManager extends AbstractManager {

	public function __construct() {
		foreach (Config::read('helpers') as $key => $className) {
			$this->load($key, new $className());
		}
	}

}