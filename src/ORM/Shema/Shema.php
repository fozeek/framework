<?php

namespace FkFramework\ORM\Shema;

use FkFramework\Util\Storage;

class Shema {

	$config;

	public function __construct(array $config) {
		$this->config = New Storage($config);
	}

	public function addLink($type, $attribut, array $referedObjectAndColumn) {
		$this->config->write($attribut.'.'.$type, $referedObjectAndColumn, true);
	}

}