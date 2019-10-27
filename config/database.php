<?php
	$DB_HOST = 'localhost';
	$DB_USER = 'root';
	$DB_PASSWORD = '123four';
	$DB_NAME = 'camagru';
	$DB_DSN_NONAME = "mysql:host=$DB_HOST;";
	$DB_DSN = "$DB_DSN_NONAME dbname=$DB_NAME";

	function connectDB($DB_DSN, $DB_USER, $DB_PASSWORD){
		$pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
		$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

		return $pdo;
	}
?>