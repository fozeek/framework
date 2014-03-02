<?php

namespace Core\Component\Model;

use Core\Resource\Cache;
use Core\Resource\Component;
use Core\Component\Model\StdTable;

class Model extends Component {

	private $_tables = array();
	private $_range = 1;
	private $_cache;
	
	public function __construct($controller, $params) {
		parent::__construct($controller, $params);
		$this->_cache = new Cache("bdd", 60);
		$this->setRange($params["range"]); 
	}

	private function _getTable($table) {
		if($this->_existsTable($table)) {
			if(!$this->_isLoadedTable($table))
				return $this->_loadTable($table);
			else
				return $this->_tables[$table];
		}
		else
			return false;
	}

	public function __get($table) {
		return ($table[0]!="_") ? $this->_getTable(strtolower($table)): $this->$table;
	}

	private function _isLoadedTable($table) {
		return (array_key_exists($table, $this->_getTables())) ? true: false;
	}

	private function _loadTable($table) {
		if(class_exists($name = "App\\Model\\Mapper\\".ucfirst($table))) {
			$this->_tables[$table] = new $name($this);
			return $this->_tables[$table];
		}
		else {
			$this->_tables[$table] = new StdTable($table, $this);
			return $this->_tables[$table];
		}
	}

	private function _getTables() {
		return $this->_tables;
	}

	private function _existsTable($table) {
		return true;
	}

	public function setRange($range) {
		$this->_range = $range;
	}

	public function getRange() {
		return $this->_range;
	}

	public function getCache() {
		return $this->_cache;
	}


	################################################
	################################################
	################################################

	/*
	public function save(array $paramData = array()) {
		$objectName = ucfirst($this->_controller->Request->getData("_object_name"));
		return $this->$objectName->getById(($this->_controller->Request->getData("_object_id")) ?
			$this->update($paramData):
			$this->insert($paramData));
	}

	private function insert(array $paramData = array()) {
		$data = $this->_controller->Request->getData();
		$data = array_merge($data, $paramData);
		$objectName = ucfirst($data["_object_name"]);
		$hasSlug = false;
		$hasDate = false;
		$hasPassword = false;
		foreach ($this->$objectName->getShema() as $key => $shema) {
			if($key == "slug")
				$hasSlug = true;
			if($key == "date")
				$hasDate = true;
			if($key == "password")
				$hasPassword = true;
			if(array_key_exists($key, $data) && !in_array($key, array("id", "deleted", "slug", "date")) && (!is_array($shema["Link"]) || (is_array($shema["Link"]) && !array_key_exists("editable", $shema["Link"])))) {
				if(!is_array($shema["Link"]) || !array_key_exists("link", $shema["Link"])) {
					$attrToUpdate[$key] = $data[$key];
				}
				elseif(array_key_exists("link", $shema["Link"])) {
					if($shema["Link"]["link"]=="OneToOne" || $shema["Link"]["link"]=="ManyToOne") {
						$classOfKey = ucfirst($shema["Link"]["reference"])."Object";
						$ref = $shema["Link"]["reference"];
						$this->_controller->Model->$ref;
						
						if(class_exists($classOfKey) && property_exists($classOfKey, "isType"))
							$attrToUpdate[$key] = $classOfKey::__executeFormNew($this, $data[$key]);
						else
							$attrToUpdate[$key] = $data[$key];
					}
					elseif($shema["Link"]["link"]=="OneToMany" || $shema["Link"]["link"]=="ManyToMany") {
					//	foreach ($data[$key] as $id_ref)
					//		Sql::create()
					//			->insert("_links")
					//			->columnsValues(array("link_root" => $object->get("id"), "link_code" => $shema["Link"]["code"], "link_link" => $id_ref))
					//			->execute();
								
					}
				}
			}
		}
		if($hasSlug && array_key_exists("title", $data)) {
			foreach (Kernel::getLangs() as $lang)
				$slug[$lang] = $this->_controller->String->sanitize($data["title"][$lang]);
			$attrToUpdate["slug"] = LangObject::__executeFormNew($this, $slug);
		}
		if($hasDate) {
			$attrToUpdate["date"] = date("Y-m-d H:i:s");
		}
		if($hasPassword) {
			$attrToUpdate["password"] = md5($attrToUpdate["password"]);
		}
		return $this->$objectName->save($attrToUpdate);
	}

	private function update(array $paramData = array()) {
		$data = $this->_controller->Request->getData();
		$data = array_merge($data, $paramData);
		$objectName = $data["_object_name"];
		$attrToUpdate = array();
		$hasSlug = false;
		$hasPassword = false;
		$object = $this->_controller->Model->$objectName->getById($data["_object_id"]);
		foreach ($object->getShema() as $key => $shema) {
			if($key == "slug")
				$hasSlug = true;
			if($key == "password")
				$hasPassword = true;
			if(array_key_exists($key, $data) && !in_array($key, array("id", "deleted", "slug", "date")) && (!is_array($shema["Link"]) || (is_array($shema["Link"]) && !array_key_exists("editable", $shema["Link"])))) {
				if(!is_array($shema["Link"]) || !array_key_exists("link", $shema["Link"]))
					$attrToUpdate[$key] = $data[$key];
				elseif(array_key_exists("link", $shema["Link"])) {
					if($shema["Link"]["link"]=="OneToOne" || $shema["Link"]["link"]=="ManyToOne") {
						$classOfKey = ucfirst($shema["Link"]["reference"])."Object";
						$ref = $shema["Link"]["reference"];
						$this->_controller->Model->$ref;
						if(class_exists($classOfKey) && property_exists($classOfKey, "isType")) {
							if(is_object($object->get($key))) {
								$retourneRes = $object->get($key)->__executeForm($this, $data[$key]);
								if($retourneRes != false)
									$attrToUpdate[$key] = $retourneRes;
							}
							else
								$attrToUpdate[$key] = $classOfKey::__executeFormNew($this, $data[$key]);
						}
						else
							$attrToUpdate[$key] = $data[$key];
					}
					elseif($shema["Link"]["link"]=="OneToMany" || $shema["Link"]["link"]=="ManyToMany") {
						Sql::create()
							->delete("_links")
							->where("link_root", "=", $object->get("id"), false)
							->andWhere("link_code", "=", $shema["Link"]["code"], false)
							->execute();
						foreach ($data[$key] as $id_ref)
							Sql::create()
								->insert("_links")
								->columnsValues(array("link_root" => $object->get("id"), "link_code" => $shema["Link"]["code"], "link_link" => $id_ref))
								->execute();
					}
				}
			}
		}
		if($hasSlug && array_key_exists("title", $data)) {
			foreach (Kernel::getLangs() as $lang)
				$slug[$lang] = $this->_controller->String->sanitize($data["title"][$lang]);
			$this->Lang->update($object->get("slug")->get("id"), $slug);
		}
		if($hasPassword) {
			$attrToUpdate["password"] = md5($attrToUpdate["password"]);
		}
		if(count($attrToUpdate)>0)
			$this->$objectName->update($object->get("id"), $attrToUpdate);
		return $object->get("id");
	}

	public function bundle($bundle, $options = null) {
		$bundle = Bundles::getBundle($bundle);
		$tables = $bundle["tables"];
		$fields = $bundle["fields"];
		import("model", strtolower($tables[0])."table");
		import("model", strtolower($tables[0])."table");
		$requete = Sql::create()->select(array_merge($fields, array("'".$tables[0]."' AS _object")))->from($tables[0]);
		foreach (array_slice($tables, 1, null, false) as $table) {
			$requete->union($table, array_merge($fields, array("'".$table."' AS _object")));
			import("model", strtolower($table)."table");
			import("model", strtolower($table)."table");
		}
		if($options !== null) {
			if(isset($options["orderBy"]))
					$requete->orderBy($options["orderBy"]);
			if(isset($options["limit"])) {
				$start = (is_array($options["limit"])) ? $options["limit"][0] : 0 ;
				$stop = (is_array($options["limit"])) ? $options["limit"][1] : $options["limit"] ;
				$requete->limit($start, $stop);
			}
			$cptWhere = 0;
			if(isset($options["where"])) {
				foreach ($options["where"] as $key => $value) {
					$nameFunctionWhere = ($cptWhere==0) ? "where" : "andWhere";
					$requete->$nameFunctionWhere($value[0], $value[1], $value[2]);
					$cptWhere++;
				}
			}
		}
		$return = array();
		foreach ($requete->fetch() as $object) {
			$objectName = $object["_object"]."Object";
			$name = $object["_object"];
			unset($object["_object"]);
			if(!class_exists($objectName))
				$objectName = "StdObject";
			array_push($return, new $objectName($object, $name));
		}
		return $return;
	}

	public function addLink($code, $root, $link) {
		Sql::create()->exec("INSERT INTO _links VALUES (".$code.", ".$root.", ".$link.")");
	}


	*/
}