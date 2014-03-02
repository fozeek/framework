<?php

namespace Fk\ORM\Shema;

use Fk\Util\Storage;

class Shema {

	$config;

	public function __construct(array $config) {
		$this->config = New Storage($config);
	}

	public function addLink($type, $attribut, array $referedObjectAndColumn) {
		$this->config->write($attribut.'.'.$type, $referedObjectAndColumn, true);
	}

}