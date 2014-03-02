<?php

namespace FkFramework\Route;

use FkFramework\Uri\Uri;

class Route {

	protected $lang;
	protected $moduleName;
	protected $controllerName;
	protected $actionName;
	protected $params;

	public function getLang() {
		return $this->controllerName;
	}

	public function setLang($lang) {
		$this->lang = $lang;
	}

	public function getModuleName() {
		return $this->moduleName;
	}

	public function setModuleName($moduleName) {
		$this->moduleName = $moduleName;
	}

	public function getControllerName() {
		return $this->controllerName;
	}

	public function setControllerName($controllerName) {
		$this->controllerName = $controllerName;
	}

	public function getActionName() {
		return $this->actionName;
	}

	public function setActionName($actionName) {
		$this->actionName = $actionName;
	}

	public function getParams() {
		return $this->params;
	}

	public function setParams(array $params) {
		$this->params = $params;
	}

}