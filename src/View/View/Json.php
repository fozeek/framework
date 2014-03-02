<?php

namespace FkFramework\View\Type;

use FkFramework\View\TypeAbstract;

class Json extends TypeAbstract {

	public function render(array $vars) {
		header('Content-type: application/json');
		echo json_encode($vars);
	}

}