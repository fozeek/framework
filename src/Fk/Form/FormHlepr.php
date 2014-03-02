<?php

namespace Core\Helper;

use Core\Resource\Helper;

class Form extends Helper {

	private $_form;

	public function start(array $options = null, $action = "#", $method = "POST", $enctype = "multipart/form-data") {
		$html =  '<form action="'.$action.'" method="'.$method.'" enctype="'.$enctype.'"';
		if(isset($options["id"]))
			$html .= 'id="'.$options["id"].'" ';
		if(isset($options["class"]))
			$html .= 'class="'.$options["class"].'" ';
		if(isset($options["style"]))
			$html .= 'style="'.$options["style"].'" ';
		if(isset($options["name"]))
			$html .= 'name="'.$options["name"].'" ';
		$html .= '>';
		$html .= $this->input("token_csrf", array("value" => $this->getView()->Session->read("Form"), "type" => "hidden")); 
		return $html;
	}

	public function end() {
		return '</form>';
	}

	public function model($model) {
		$shema = $model->getShema();
		return false;

		// A FINIR
	}

	public static function getInstance() {
		return new FormHelper();
	}

	public function __construct($view = null, $params = null) {
		parent::__construct($view, $params);
		if(array_key_exists("form", $params))
			$this->_form = $params["form"];
	}

	public function input($name, array $options = array()) {
		$html = '<input name="'.$name.'" ';
		if(isset($options["type"]))
			$html .= 'type="'.$options["type"].'" ';
		else
			$html .= 'type="text" ';
		if(isset($options["value"]))
			$html .= 'value="'.$options["value"].'" ';
		if(isset($options["size"]))
			$html .= 'size="'.$options["size"].'" ';
		if(isset($options["maxlength"]))
			$html .= 'maxlength="'.$options["maxlength"].'" ';
		if(isset($options["placeholder"]))
			$html .= 'placeholder="'.$options["placeholder"].'" ';
		if(isset($options["id"]))
			$html .= 'id="'.$options["id"].'" ';
		if(isset($options["class"]))
			$html .= 'class="'.$options["class"].'" ';
		if(isset($options["checked"]) && isset($options["type"]) && ($options["type"] == "radio" || $options["type"] == "checkbox") && $options["checked"])
			$html .= 'checked ';
		if(isset($options["style"]))
			$html .= 'style="'.$options["style"].'" ';
		$html .= '/>';
		return $html;
	}

	public function textarea($name, array $options = array()) {
		$html = '<textarea name="'.$name.'" ';
		if(isset($options["placeholder"]))
			$html .= 'placeholder="'.$options["placeholder"].'" ';
		if(isset($options["rows"]))
			$html .= 'rows="'.$options["rows"].'" ';
		if(isset($options["cols"]))
			$html .= 'cols="'.$options["cols"].'" ';
		if(isset($options["id"]))
			$html .= 'id="'.$options["id"].'" ';
		if(isset($options["class"]))
			$html .= 'class="'.$options["class"].'" ';
		if(isset($options["style"]))
			$html .= 'style="'.$options["style"].'" ';
		if(isset($options["readonly"]) && $options["readonly"])
			$html .= 'readonly ';
		$html .= '>';
		if(isset($options["value"]))
			$html .= $options["value"];
		$html .= '</textarea>';
		return $html;
	}

	public function checkbox($name, $value, $options = array()) {
		$options["type"] = "checkbox";
		$options["value"] = $value;
		return $this->input($name, $options);
	}

	public function radio($name, $value, array $options = array()) {
		$options["type"] = "radio";
		$options["value"] = $value;
		return $this->input($name, $options);
	}

	public function select($name, $collection, array $options = array()) {
		$html = '<select name="'.$name.'" ';
		if(isset($options["size"]))
			$html .= 'size="'.$options["size"].'" ';
		if(isset($options["multiple"]) && $options["multiple"])
			$html .= 'multiple ';
		if(isset($options["id"]))
			$html .= 'id="'.$options["id"].'" ';
		if(isset($options["class"]))
			$html .= 'class="'.$options["class"].'" ';
		if(isset($options["style"]))
			$html .= 'style="'.$options["style"].'" ';
		$html .= '>';
		foreach ($collection as $key => $value) {
			$selected = (isset($options["selected"]) && $options["selected"] == $value["key"]) ? "selected": null ;
			$html .= '<option value="'.$value["key"].'" '.$selected.'>'.$value["value"].'</option>';
		}
		$html .= '</select>';
		return $html;
	}

	public function file($name, array $options = array()) {
		$html = '<input type="file" name="'.$name.'" ';
		if(isset($options["id"]))
			$html .= 'id="'.$options["id"].'" ';
		$html .= 'class="input-file ';
		if(isset($options["class"]))
			$html .= $options["class"];
		$html .= '" ';
		if(isset($options["style"]))
			$html .= 'style="'.$options["style"].'" ';
		$html .= '/>';
		return $html;
	}

	public function submit($value = "Envoyer", array $options = array()) {
		$html = '<input type="submit" value="'.$value.'" ';
		if(isset($options["id"]))
			$html .= 'id="'.$options["id"].'" ';
		if(isset($options["class"]))
			$html .= 'class="'.$options["class"].'" ';
		if(isset($options["style"]))
			$html .= 'style="'.$options["style"].'" ';
		$html .= '/>';
		return $html;
	}

	public function getFormHiddenElements($object) {
		$html = FormHelper::getInstance()->input("_object_id", array("value" => $object->get("id"), "type" => "hidden"));
		$html .= FormHelper::getInstance()->input("_object_name", array("value" => $object->getName(), "type" => "hidden"));
		return $html;
	}

	public function getFormElements($object, array $options = array()) {
		$form = array();
		foreach ($object->getShema() as $key => $shema) {
			if(!in_array($key, array("id", "deleted", "slug", "date")) && (!is_array($shema["Link"]) || (is_array($shema["Link"]) && !array_key_exists("editable", $shema["Link"])))) {
				if(!is_array($shema["Link"]) || !array_key_exists("link", $shema["Link"])) {
					$params = array("value" => $object->get($key));
					if($key=="password")
						$form[$key] = FormHelper::getInstance()->input($key, array("type" => "password"));
					elseif($key=="text")
						$form[$key] = FormHelper::getInstance()->textarea($key, $params);
					else {
						$formType = ($shema["Link"]["size"]=="small") ? "input" : "textarea";
						$form[$key] = FormHelper::getInstance()->$formType($key, $params);
					}
				}
				elseif(array_key_exists("link", $shema["Link"])) {
					if($shema["Link"]["link"]=="OneToOne" || $shema["Link"]["link"]=="ManyToOne") {
						$classOfKey = ucfirst($shema["Link"]["reference"])."Object";
						if(class_exists($classOfKey) && property_exists($classOfKey, "isType")) {
							if(is_object($object->get($key))) {
								if(is_array($inputs = $object->get($key)->__printForm($shema))) {
									$form[$key] = "";
									foreach ($inputs as $input)
										$form[$key] .= $input;
								}
								else
									$form[$key] = $inputs;
							}
							else
								$form[$key] = $classOfKey::__printFormNew($shema);
						} else {
							$collection = array();
							foreach ($this->_form[$key] as $value)
								array_push($collection, array("key" => $value->get("id"), "value" => $value->get("title")));
							$form[$key] = FormHelper::getInstance()->select($key, $collection, array("selected" => $object->get($key)->get("id")));
						}
					}
					elseif($shema["Link"]["link"]=="OneToMany" || $shema["Link"]["link"]=="ManyToMany") {
						$have = array();
						foreach ($object->get($key) as $doHave)
							array_push($have, $doHave->get("id"));
						$form[$key] = "";
						foreach ($this->_form[$key] as $value) {
							if(in_array($value->get("id"), $have))
								$form[$key] .= FormHelper::getInstance()->checkBox($key."[]", $value->get("id"), array("checked" => true))." ".$value->get("title")."<br />";
							else
								$form[$key] .= FormHelper::getInstance()->checkBox($key."[]", $value->get("id"))." ".$value->get("title")."<br />";
							
						}
					}
				}
			}
		}
		return $form;
	}

	public function getFormNewHiddenElements($table) {
		$html = FormHelper::getInstance()->input("_object_name", array("value" => $table->getName(), "type" => "hidden"));
		return $html;
	}

	public function getFormNewElements($table, array $options = array()) {
		$form = array();
		foreach ($table->getShema() as $key => $shema) {
			if(!in_array($key, array("id", "deleted", "slug", "date")) && (!is_array($shema["Link"]) || (is_array($shema["Link"]) && !array_key_exists("editable", $shema["Link"])))) {
				if(!is_array($shema["Link"]) || !array_key_exists("link", $shema["Link"])) {
					$params = array("value" => "");
					if($key=="password")
						$form[$key] = FormHelper::getInstance()->input($key, array("type" => "password"));
					elseif($key=="text")
						$form[$key] = FormHelper::getInstance()->textarea($key, $params);
					else {
						$formType = ($shema["Link"]["size"]=="small") ? "input" : "textarea";
						$form[$key] = FormHelper::getInstance()->$formType($key, $params);
					}
				}
				elseif(array_key_exists("link", $shema["Link"]) && ($shema["Link"]["link"]=="OneToOne" || $shema["Link"]["link"]=="ManyToOne")) {
					$classOfKey = ucfirst($shema["Link"]["reference"])."Object";
					if(class_exists($classOfKey) && property_exists($classOfKey, "isType")) {
						if(is_array($inputs = $classOfKey::__printFormNew($shema))) {
							$form[$key] = "";
							foreach ($inputs as $input)
								$form[$key] .= $input;
						}
						else
							$form[$key] = $inputs;
					} else {
						$collection = array();
						foreach ($this->_form[$key] as $value)
							array_push($collection, array("key" => $value->get("id"), "value" => $value->get("title")));
						$form[$key] = FormHelper::getInstance()->select($key, $collection);
					}
				}
				/*elseif($shema["Link"]["link"]=="OneToMany" || $shema["Link"]["link"]=="ManyToMany") {
					$collection = array();
					foreach ($this->_form[$key] as $value)
							array_push($collection, array("key" => $value->get("id"), "value" => $value->get("title")));
					$form[$key] = FormHelper::getInstance()->select($key."[]", $collection, array("style" => "width: 100%;height: 150px;", "multiple" => true, "class" => "select-multiple"));		
				}*/
			}
		}
		return $form;
	}
}