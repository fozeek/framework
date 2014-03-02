<?php

namespace Fk\View\Type;

use Fk\View\TypeAbstract;

class Json extends TypeAbstract {

	public function render(array $vars) {
		header('Content-type: application/json');
		echo json_encode($vars);
	}

}