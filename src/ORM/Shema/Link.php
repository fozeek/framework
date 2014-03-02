<?php

namespace FkFramework\ORM\Shema;

class Link {

	protected $column;
	protected $type;
	protected $linkedTable;
	protected $linkedColumn;
	protected $linkTable;

	public function __construct(Column $column) {
		$this->column = $column;
	}

	public function is($type) {
		return $type === $this->type;
	}

	public function getType() {
		return $this->type;
	}

	public function getLinkedTable() {
		return $this->linkedTable;
	}

	public function getLinkedColumn() {
		return $this->linkedColumn;
	}

	public function getLinkTable() {
		if(!empty($this->linkTable)) {
			return $this->linkTable;
		}
		return $this->column->getShema()->getEntityName().'_'.$this->linkedTable;
	}

	public function setType($type) {
		$this->type = $type;
	}

	public function setLinkedTable($linkedTable) {
		$this->linkedTable = $linkedTable;
	}

	public function setLinkedColumn($linkedColumn) {
		$this->linkedColumn = $linkedColumn;
	}

	public function setLinkTable($linkTable) {
		$this->linkTable = $linkTable;
	}

}