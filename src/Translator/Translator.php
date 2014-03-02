<?php

namespace Core\Helper;

use Core\Resource\Helper;

class Translator extends Helper {

	private $dicos = array();

	public function __construct($view, $params = null) {
		parent::__construct($view, $params);
		$this->initializeDicos();
	}

	public function translate($text, $context, $lang = null) {
        foreach ($context as $key => $val)
            $replace['{' . $key . '}'] = $val;
        return strtr($this->dicos[($lang === null) ? Kernel::getCurrentLang() : $lang][$text], $replace);
	}

	private function initializeDicos() {
		$this->dicos = array();
	}

}