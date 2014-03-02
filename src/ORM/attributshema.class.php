<?php 

namespace Core\Component\Model;

class AttributShema {

	private $_shema;
	private $_options = array(
			"defaultValue" => null,
			"unique" => false,
			"autoIncrement" => false,
			"reference" => array(
					"id" => null,
					"table" => null,
					"type" => "OneToOne",
				),
			"type" => null,
			"rules" => array()
		);

	public function __construct($shema, $options) {
		$this->_shema = $shema;
		$this->_options = $options;
	}

	public function getShema() {
		return $this->_shema;
	}

	public function getOption($option) {
		return $this->_options[$option];
	}

	## Gestion des options

	public function setUnique($boolean) {
		return false;
	}

	public function isUnique() {
		return $this->_options["unique"];
	}

	public function setAutoIncrement($boolean) {
		return false;
	}

	public function setReference($table, $params) {
		return false;
	}

	public function setDefaultValue($defaultValue) {
		return false;
	}

	public function setType($type) {
		return false;
	}

	public function setRules(array $rules) {
		return false;
	}

}