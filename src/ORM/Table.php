<?php

namespace Fk\ORM;

use Fk\Db\QueryBuilder;
use Fk\ORM\TableManager;
use Fk\Util\Collection;

class Table {

	protected $name;			//	string
	protected $alias;			//	string
	protected $tableManager;	//	Fk\ORM\TableManager
	protected $validator;		//	Fk\Validation\Validator
	protected $shema;			//	Fk\ORM\Shema\Shema

	/*
		Constructeur
	*/
	public function __construct($name, TableManager $tableManager) {
		$this->name = $name;
		$this->tableManager = $tableManager;
		$this->initialize();
	}

	public function initialize() {
		// Définir si surcharge
	}

	public function getName() {
		return $this->name;
	}

	/*
		Ajouter un élément à la table
	*/
	public function save(array $attributs) {
		if(!$this->validate($attributs))
			return false;
		if($returnId = QueryBuilder::create()
				->insert($this->name)
				->columnsValues($attributs)
				->execute()
			) {
			return $returnId;
		}
		else
			return false;
	}

	/*
		Modifier un élément de la table
		À faire : 
			Pourvoir mettre un array d'IDs
	*/
	public function update($id, array $attributs) {
		if(!$this->validate($attributs))
			return false;
		return (QueryBuilder::create()
				->update($this->getName())
				->columnsValues($attributs)
				->where("id", "=", $id)
				->execute()
			) ?
			true : false;
	}

	/*
		Vérifie les données selon les regles définies dans $this->_rules
	*/
	public function validate($data) {
		return $this->validator->check($data);
	}

	/*
		Supprime un élément de la table
		Met l'attribut "deleted" à true
	*/
	public function remove($id) {
		return ($this->update($id, array("deleted" => true))) ?
			true : false ;
	}

	/*
		Fonctions auto-générées
	*/
	public function __call($name, array $params) {
		// Traitement du nom de la fonction
		$function = array();
		$tmp = "";
		foreach (str_split($name) as $key => $value) {
			if(ord($value)>=65 && ord($value)<=90) {
				array_push($function, strtolower($tmp));
				$tmp = "";
			}
			$tmp .= $value;
		}
		array_push($function, strtolower($tmp));

		if($function[0] == "find" && ($function[1] == "by" || $function[1] == "all")) {
			$cptWhere = 0;
			$requete = QueryBuilder::create()->from($this->getName());
			if($function[1] == "by") {
				$options = (isset($params[1])) ? $params[1]: null;
				$requete->where($function[2], "IN", (is_array($params[0])) ? $params[0] : array($params[0]));
				$cptWhere++;
			}
			else
				$options = (isset($params[0])) ? $params[0]: null;
			$return = array();
			if(isset($options["orderBy"]))
				$requete->orderBy($options["orderBy"]);
			if(isset($options["limit"])) {
				$start = (is_array($options["limit"])) ? $options["limit"][0] : 0 ;
				$stop = (is_array($options["limit"])) ? $options["limit"][1] : $options["limit"] ;
				$requete->limit($start, $stop);
			}
			if(isset($options["where"])) {
				foreach ($options["where"] as $key => $value) {
					$nameFunctionWhere = ($cptWhere==0) ? "where" : "andWhere";
					$requete->$nameFunctionWhere($value[0], $value[1], $value[2]);
					$cptWhere++;
				}
			}
			$data = $this->afterFind($requete->fetch(), $options); // Traitement par la classe fille
			foreach ($data as $key => $value) {
				$object = $this->tableManager->getEntityManager()->get($this->getName());
				array_push($return, $object->populate($value));
			}
			return new Collection($return);
			// if(count($return) > 0)
			// 	return (count($return) > 1 || $function[1] == "all") ? $return : $return[0] ;
			// else
			// 	return ($function[1] == "all") ? array() : false ;
		}
		else
			return false;
	}

	// Fonctions à définir dans la classe fille
	protected function beforeFind($params) { return $params; }
	protected function afterFind($data, $params) { return $data; }
	protected function beforeSave($data) { return $data; }
	protected function afterSave() {}

}