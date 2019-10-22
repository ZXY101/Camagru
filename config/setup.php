<?php
	require('database.php');

	//Delete the DB is it exists then create/recreate it
	try {
		$pdo = connectDB($DB_DSN_NONAME, $DB_USER, $DB_PASSWORD);

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
		//Connect to DB
		$pdo = connectDB($DB_DSN, $DB_USER, $DB_PASSWORD);
		echo ('Successfully Connected!<br>');

		//Create the 'users' table
		$sql = 'CREATE TABLE IF NOT EXISTS users(
			id INT AUTO_INCREMENT,
			user_name VARCHAR(50) NOT NULL,
			first_name VARCHAR(100) NOT NULL,
			last_name VARCHAR(100) NOT NULL,
			email VARCHAR(255) NOT NULL,
			password VARCHAR(255) NOT NULL,
			display_picture VARCHAR(255),
			registered_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
			is_admin TINYINT(1) DEFAULT 0,
			PRIMARY KEY(id),
			UNIQUE(id, user_name)
		)';
		$pdo->exec($sql);
		echo ('Users Table Created!<br>');

		//Insert some default users into the 'users' table
		$sql = 'INSERT INTO users(user_name, first_name, last_name, email, password, is_admin)
				VALUES("admin", "admin", "admin", "admin@camagru.com", "'.password_hash("admin", PASSWORD_DEFAULT).'", 1),
				("stenner", "Shaun", "Tenner", "stenner@student.wethinkcode.co.za","'.password_hash("123four56", PASSWORD_DEFAULT).'", 0),
				("petepete", "Pete", "Peterson", "peterpete11@emailer.com", "'.password_hash("pete11pete", PASSWORD_DEFAULT).'", 0)';
		$pdo->exec($sql);
		echo ('Base Users Created!<br>');

		//Create the 'posts' table
		$sql = 'CREATE TABLE posts(
			id INT AUTO_INCREMENT,
			user_id INT NOT NULL,
			title VARCHAR(255) NOT NULL,
			body TEXT NOT NULL,
			published_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY (id),
			FOREIGN KEY (user_id) REFERENCES users(id)
		)';
		$pdo->exec($sql);
		echo ('Posts Table Created!(temp)<br>');

		//Create the 'friends' table
		$sql = 'CREATE TABLE friends(
			friends_id INT AUTO_INCREMENT,
			user_1 INT NOT NULL,
			user_2 INT NOT NULL,
			friends_from TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY (friends_id),
			FOREIGN KEY (user_1) REFERENCES users(id),
			FOREIGN KEY (user_2) REFERENCES users(id)
		)';
		$pdo->exec($sql);
		echo ('Friends Table Created!(temp)<br>');

		$pdo = null;
	}catch(PDOException $e){
		echo ('Failed: '.$e->getMessage());
	}
?>
