<?php

namespace Fk\ORM\Shema\Type;

class TypeManager {

	protected $_types;

	public function getTypes() {
		return $this->_types;
	}

	public function typeExists($type) {
		return array_key_exists($type, $this->_types);
	}

	public function getType($type) {
		return ($this->typeExists($type)) ? $this->_types[$type] : false ;
	}

	public function addType($name, $className) {
		$this->_types[$name] = $className;
	}

	public function factor($typeName, $value = null, $params = array()) {
		if($className = $this->getType($type)) {
			return new $className($value, $params);
		}
		return false; 
	}
	
}