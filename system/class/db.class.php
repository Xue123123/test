<?php
/**
 *   a factory  abut  db connet
 */
class db {
	static $driver = 'mysql';
	static $db;
	function connect() {
		if (self::$db) {
			return self::$db;
		}
		global $_DBCF;
		router::load_class(self::$driver);
		$driver = self::$driver . '_db';
		self::$db = new $driver($_DBCF);
		if (CHARSET) {
			self::$db->query("set names " . CHARSET);
		}
		return self::$db;
	}
}