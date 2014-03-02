<?php

namespace Fk\Event;

class EventManager {

	private $_events = array(array());

	public function attach($event, $callback) {
		$this->_events[$event]["listeners"][] = $callback;
	}

	public function trigger($event, $data) {
		foreach ($this->_event[$event]["listeners"] as $callback) {
			$return[$callback[0]][$callback[1]] = call_user_func_array($callback, $data);
		}
		return $return;
	}

}