<?php

namespace FkFramework\Uri;

class Uri {

	protected $hostname;
	protected $path;

	public function __construct($uri = null) {
		(!empty($uri)) ? $this->parse($uri) : $this->createUri();
		return $this;
	}

	private function parse($uri) {
	}

	private function createUri() {
		$this->setHostname($_SERVER['HTTP_HOST']);
		if(strripos($_SERVER['REQUEST_URI'], '?'))
			$this->setPath(substr($_SERVER['REQUEST_URI'], 0, (strlen($_SERVER['REQUEST_URI'])-(-strripos($_SERVER['REQUEST_URI'], '?') + strlen($_SERVER['REQUEST_URI'])))));
		else
			$this->setPath($_SERVER['REQUEST_URI']);
	}

	public function setHostname($hostname) {
		$this->hostname = $hostname;
	}

	public function getHostName() {
		return $this->hostname;
	}

	public function setPath($path) {
		$this->path = $path;
	}

	public function getPath($key = null) {
		$path = array_values(array_filter(explode("/", $this->path)));
		return (empty($key)) ?
			$path:
			$path[$key];
	}

	public function getPathString() {
		return $this->path;
	}

}