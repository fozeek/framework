<?php

namespace Core\Component\Response\Type;

use Core\Resource\Component;
use Core\Component\Response\TypeAbstract;

class Json extends TypeAbstract {

	public function render(array $vars) {
		header('Content-type: application/json');
		echo json_encode($vars);
	}

}