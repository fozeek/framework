<?php

namespace FkFramework\ORM;

use FkFramework\ORM\Shema\Shema;
use FkFramework\ORM\TableManager;
use FkFramework\ORM\Shema\Type\TypeManager;
use FkFramework\DB\QueryBuilder;

class Entity {

	protected $_name;			// string										Nom de l'entité
	protected $_attributs;		// array										Attributs de l'entité
	protected $_shema;			// FkFramework\ORM\Shema\Shema					Shéma de l'entité
	protected $_tableManager;	// FkFramework\ORM\TableManager					TableManager de l'application

	public function __construct($name, TableManager $tableManager, array $attributs = array()) {
		$this->_tableManager = $tableManager;
		//$this->_constructShema();
		$this->_attributs = $attributs;
		$this->_name = $name;
	}

	public function populate(array $attributs = array()) {
		$this->attributs = $attributs;
		return $this;
	}

	public function getName() {
		return $this->_name;
	}

	protected function _constructShema() {
		$this->_shema = $this->constructShema($this->_shema);
	}

	private function constructShema(Shema $shema) {
		// Surcharge it !
	}

	public function getShema() {
		return $this->shema;
	}

	public function __setWithParams($params) {
		return null;
	}

	public function __tostring() {
		return "EntityModel#".$this->getName().":".$this->get("id");
	}

	public function getAttributs() {
		return $this->_attributs;
	}

	public function get($attributName, $params = array()) {
		if($this->exists($attributName)) {
			if($this->_shema->hasColumn($attributName) && is_string($this->_attributs[$attributName])) { // Si c'est un string, alors il n'a pas été traité
				// Lien vers d'autre objet
				if($this->_shema->get($attributName)->hasLink()) {
					$this->_attributs[$attributName] = $this->_initializeLink($attributName, $params);
				}
				// Format spécial
				else {
					$nameFormat = $this->_shema->get($attributName)->getNameFormat();
					$this->_attributs[$attributName] = $this->_tableManager->getTypeManager()->factor(
						$nameFormat, 
						$this->_attributs[$attributName],
						$params
					);
				}
			}
			// Un attribut peut ne pas être définit dans le shéma, la valeur est donc simplement retournée
			return $this->_attributs[$attributName];
		}
		else
			return false;
	}	

	protected function _initializeLink($attributName, $params = array()) {
		$attributLinkShema = $this->_shema->get($attributName)->getLink();

		$typeLink = $attributLinkShema->getTypeLink();
		$linkedTable = $this->_tableManager->get($attributLinkShema->getLinkedTable());
		$linkedColumn = $attributLinkShema->getLinkedColumn();

		if($typeLink->is('OneToMany') || $typeLink->is('ManyToMany')) {
			// recherche de la table des liens
			$tableLink = $attributLinkShema->getLinkTable(); // Si aucune définit, une table TableOrigin_LinkedTable est retournée
			$valueLinked = QueryBuilder::create()->select()->from($tableLink)->where('id_origin', '==', $this->_attributs['id']);
			$entities = $linkedTable->find(array($linkedColumn, $valueLinked), $params);
		}
		elseif($typeLink->is('OneToOne') || $typeLink->is('ManyToOne')) {
			$entities = $linkedTable->find(array($linkedColumn, $this->_attributs['attributName']), $params);
		}
		return $entities;
	}

	public function exists($column) {
		return array_key_exists($column, $this->_attributs);
	}

}