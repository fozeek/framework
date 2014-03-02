<?php

namespace FkFramework\Config;

use FkFramework\Util\Storage;

class Config {

	protected static $storage = null;

	public static function write($key, $value) {
		if(self::$storage==null)
			self::$storage = new Storage();
		self::$storage->write($key, $value);
	}

	public static function read($key) {
		return self::$storage->read($key);
	}

}