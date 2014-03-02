<?php

namespace FkFramework\Mvc\Component;

abstract class AbstractComponent {
	
	protected $params;

	public function __construct($params = null) {
		$this->_controller = $controller;
	}

	public function getParams() {
		return $this->params;
	}

	public function setParams($params) {
		$this->params = $params;
	}

	public function __transmit($params) {
		return null;
	}

}

?>