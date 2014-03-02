<?php

namespace FkFramework\Util;

class ArrayCollection {

	protected $collection = array();

	public function get($key) {
		return ($this->exists($key)) ? 
			$this->collection[$key]:
			false;
	}

	public function set($key, $value) {
		$this->collection[$key] = $value;
	}

	public function remove($key) {
		unset($this->collection[$key]);
	}

	public function exists($key) {
		return array_key_exists($key, $this->collection);
	}

}