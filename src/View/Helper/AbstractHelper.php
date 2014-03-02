<?php

namespace FkFramework\View\Helper;

abstract class AbstractHelper {
	
	protected $_view;

	public function __construct($view, $params = null) {
		$this->setView($view);
	}

	public function setView($view) {
		$this->_view = $view;
	}

	public function getView() {
		return $this->_view;
	}

}

?>