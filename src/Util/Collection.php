<?php

namespace Fk\Util;

use IteratorIterator;
use JsonSerializable;
use ArrayIterator;

class Collection extends IteratorIterator implements JsonSerializable{

	public function __construct($items) {
		if (is_array($items)) {
			$items = new ArrayIterator($items);
		}

		if (!($items instanceof \Traversable)) {
			$msg = 'Only array or \Traversable are allowed for Collection';
			throw new InvalidArgumentException($msg);
		}

		parent::__construct($items);
	}

	public function jsonSerialize () {

	}

	//	applique une function sur tous les elements
	// dans options on peut definir un callable pour selectionner seulement les elements que l'on desire :)	
	public function each($callable, array $options) {

	}

	// Retire tous les element qui renvoie faux par callable
	public function filter($callable) {

	}

	// Retourne un Collection des elements qui renvoie vrai
	public function extract($callable) {

	}

	public function max($callable) {

	}

	public function sortBy() {
		
	}

	public function groupBy() {

	}

	public function shuffle() {

	}

	public function take($limit, $from = 0) {

	}

	public function first() {

	}

	public function last() {

	}

	public function append() {

	}
	

	public function prepend() {

	}

	public function insert($key, $value) {

	}
}