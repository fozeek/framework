<?php

namespace Fk\View\Type;

use Fk\View\TypeAbstract;

class View extends TypeAbstract {

	private $_helpers = array();
	private $_defaultTheme ;
	private $_params;
	private $_controller;


	public function __construct($controller) {
		$this->_controller = $controller;
		$this->_defaultTheme = "default";
		$this->_params = array(
				"helpers" => array(
					"Auth",
					"Html",
					"Image",
					"Form",
					"Session",
					"Params",
				)
			);
	}

	public function render($vars = null) {
		// Autoloads
		// juste avant l'affichage pour que les components soit tous déjà chargés pour les transmitions aux helpers
		foreach ($this->_params["helpers"] as $key => $value) {
			if(is_numeric($key))
				$this->load($value);
			else
				$this->load($key, $value);
		}

		if($vars != null)
			extract($vars);
		if(file_exists("app/frontend/views/themes/".$this->_defaultTheme.'/index.php'))
			include("app/frontend/views/themes/".$this->_defaultTheme.'/index.php');
		else
			Error::render(3, $this->_defaultTheme);
	}

	public function getControllerName() {
		return $this->_controller->getControllerName();
	}

	public function getActionName() {
		return $this->_controller->getActionName();
	}

	public function __get($attribut) {
		if(isset($this->$attribut))
			return $this->$attribut;
		else
			return $this->_helpers[strtolower($attribut)];
	}

	public function load($helper, $params = null) {
		$short_name = $helper;
		if(!isset($this->_helpers[strtolower($short_name)])) {
			$helperName = "\\Core\\Helper\\".$helper;
			$component = ucfirst($helper);
			$transmit = array();
			if($this->_controller->isLoaded($component) == true)
				$transmit = $this->_controller->$component->__transmit($params);

			($params!==null) ?
				$helper = new $helperName($this, array_merge($params, $transmit)) :
				$helper = new $helperName($this, $transmit);
			$this->_helpers[strtolower($short_name)] = $helper;
			return $helper;
		}
		else
			return $this->_helpers[strtolower($short_name)];
	}
}