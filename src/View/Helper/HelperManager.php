<?php

namespace FkFramework\View\Helper;

use FkFramework\Manager\AbstractManager;

class HelperManager extends AbstractManager {

	public function __construct() {
		foreach (Config::read('helpers') as $key => $className) {
			$this->load($key, new $className());
		}
	}

}