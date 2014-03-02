<?php

namespace Core\Component\Form;

use Core\Resource\Component;

class Form extends Component {

	private $_form = null;

	public function __construct($controller, $params = null) {
		parent::__construct($controller, $params);
		// token csrf
/*
		$formToken = $this->getController()->Request->getData("token_csrf");
		$sessionToken = $this->getController()->Session->read("Form");
		if(!empty($formToken) && $formToken!=$sessionToken)
			Error::render(6, "Fail CSRF detected !");

		$token = md5(Config::read("key").uniqid('csrf_'));
		$this->getController()->Session->write("Form", $token);
		*/
	}

	public function __transmit($params) {
		return array("form" => $this->_form);
	}

	public function setForm($object) {
		$form = array();
		foreach ($object->getShema() as $key => $shema) {
			if(!in_array($key, array("id", "deleted")) && is_array($shema["Link"]) && array_key_exists("link", $shema["Link"]) && !array_key_exists("editable", $shema["Link"])) {
				$classOfKey = ucfirst($shema["Link"]["reference"])."Object";
				$ref = $shema["Link"]["reference"];
				//$this->_controller->Model->$ref; // pour l'import des classes de la table et de l'objet 
				if($shema["Link"]!="" && ($shema["Link"]["link"]=="OneToOne" || $shema["Link"]["link"]=="ManyToOne")) {
					if((class_exists($classOfKey) && !property_exists($classOfKey, "isType")) || !class_exists($classOfKey))
						$form[$key] = $this->_controller->Model->$ref->getAll();
				}
				elseif($shema["Link"]!="" && ($shema["Link"]["link"]=="OneToMany" || $shema["Link"]["link"]=="ManyToMany"))
					$form[$key] = $this->_controller->Model->$ref->getAll();
			}
		}
		$this->_form = $form;
	}

}