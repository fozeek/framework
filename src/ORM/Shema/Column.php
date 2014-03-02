<?php

class Column {

	protected $shema;
	protected $link;

	public function __construct(Shema $shema) {
		$this->shema = $shema;
	}

	public function hasLink() {
		return !empty($this->link);
	}

	public function getLink() {
		return $this->link;
	}


}