<?php

namespace FkFramework\ORM;

use FkFramework\ORM\Shema\Type\TypeManager;

class TableManager {
	
	private $tables = [];
	private $entityManager;
	private $typeManager;

	public function __construct() {
		$this->typeManager = new typeManager();
		$this->entityManager = new EntityManager($this, $this->typeManager);
	}

	public function getTypeManager() {
		return $this->typeManager;
	}

	public function getEntityManager() {
		return $this->entityManager;
	}

	public function get($tableName) {
		if(!array_key_exists($tableName, $this->tables)) {
			$namespace = 'App\\Model\\Table\\';
			$class = $namespace.ucfirst($tableName);
			if(!class_exists($class)) {
				$class = 'FkFramework\ORM\Table';
			}
			$this->tables[$tableName] = new $class($tableName, $this);
		}
		return $this->tables[$tableName];
	}

	public function set($tableName, $table) {
		$this->tables[$tableName] = $table;
	}

	public function create($tableName, array $options = array()) {

	}

}