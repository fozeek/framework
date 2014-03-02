<?php

namespace Fk\Manager;

use Fk\Util\ArrayCollection;

abstract class AbstractManager extends ArrayCollection {

	public function load($key, $class) {
		$this->set($key, array(
			'active' => true,
			'class_name' => $key,
			'value' => $class,
		));
	}

	public function get($key) {
		if(parent::get($key) && parent::get($key)['active']) {
			return parent::get($key)['value'];
		}
	}

	public function unload($key) {
		$this->remove($key);
	}

	public function enable($key) {
		$this->get($key)['active'] = true;
	}

	public function disable($key) {
		$this->get($key)['active'] = false;
	}

	public function isLoaded($key) {
		return $this->exists($key);
	}

	public function isEnable($key) {
		return ($this->exists($key)) ? 
			$this->get($key)['active']:
			false;
	}

	private function getClassName($class) {
		$explode = explode('\\', get_class($class));
		return end($explode);
	}

}