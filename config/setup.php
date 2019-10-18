<?php
	require('database.php');

	try {
		$pdo = new PDO($DB_DSN_NONAME, $DB_USER, $DB_PASSWORD);
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$sql = 'DROP DATABASE IF EXISTS '.$DB_NAME;
		if ($pdo->exec($sql))
			echo 'Database Successfully Deleted!<br>';

		$sql = 'CREATE DATABASE IF NOT EXISTS '.$DB_NAME;
		if ($pdo->exec($sql))
			echo 'Database Successfully Created!<br>';

		$pdo = null;
	}catch(PDOException $e){
		echo ('Failed: '.$e->getMessage());
	}

	try {
		$pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		echo ('Successfully Connected!<br>');

		$sql = 'CREATE TABLE IF NOT EXISTS users(
			id INT AUTO_INCREMENT,
			first_name VARCHAR(100),
			last_name VARCHAR(100),
			email VARCHAR(255),
			password VARCHAR(255),
			registered_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
			is_admin TINYINT(1),
			PRIMARY KEY(id)
		)';
		$pdo->exec($sql);
		echo ('Users Table Created!<br>');

		$sql = 'INSERT INTO users(first_name, last_name, email, password, is_admin)
				VALUES("admin", "admin", "admin@camagru.com", "admin", 1),
				("Shaun", "Tenner", "stenner@student.wethinkcode.co.za", "123four56", 0),
				("Pete", "Peterson", "peterpete11@emailer.com", "pete11pete", 0)';
		$pdo->exec($sql);
		echo ('Base Users Created!<br>');

	}catch(PDOException $e){
		echo ('Failed: '.$e->getMessage());
	}
?>