<?php
require_once('constant.php');

class Database{
	// Establishes connection with database and returns the handler
	public static function connect(){
		global $mysqli;
		$mysqli = (isset($mysqli)) ? $mysqli : mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME) or die ("Can't connect to database ");
		return $mysqli;
	}
}

?>