<?php
	require('database.php');
	session_start();
	session_destroy();

	//Delete the DB if it exists then create/recreate it
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
			notifications TINYINT(1) DEFAULT 1,
			is_verified TINYINT(1) DEFAULT 0,
			is_admin TINYINT(1) DEFAULT 0,
			PRIMARY KEY(id),
			UNIQUE(id, user_name)
		)';
		$pdo->exec($sql);
		echo ('Users Table Created!<br>');

		//Insert some default users into the 'users' table
		$sql = 'INSERT INTO users(user_name, first_name, last_name, email, password, is_verified, is_admin, display_picture)
				VALUES("admin", "admin", "admin", "admin@camagru.com", "'.password_hash("admin", PASSWORD_DEFAULT).'", 1, 1, "/Camagru/images/display_pictures/EvergreenUnluckyBubblefish-small.gif"),
				("stenner", "Shaun", "Tenner", "stenner@student.wethinkcode.co.za","'.password_hash("123four56", PASSWORD_DEFAULT).'", 1, 0, "/Camagru/images/display_pictures/Anime--gif-kmnz-4830714.gif"),
				("petepete", "Pete", "Peterson", "peterpete11@emailer.com", "'.password_hash("pete11pete", PASSWORD_DEFAULT).'", 1, 0, "/Camagru/images/display_pictures/Flandre.Scarlet.full.2211184.gif")';
		$pdo->exec($sql);
		echo ('Base Users Created!<br>');

		//Create the 'posts' table
		$sql = 'CREATE TABLE posts(
			post_id INT AUTO_INCREMENT,
			user_id INT NOT NULL,
			title VARCHAR(255) NOT NULL,
			image VARCHAR(255),
			body TEXT NOT NULL,
			published_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY (post_id),
			FOREIGN KEY (user_id) REFERENCES users(id)
		)';
		$pdo->exec($sql);
		echo ('Posts Table Created!<br>');

		//Create the 'comments' table
		$sql = 'CREATE TABLE comments(
			comment_id INT AUTO_INCREMENT,
			user_id INT NOT NULL,
			post_id INT NOT NULL,
			comment TEXT NOT NULL,
			posted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY (comment_id),
			FOREIGN KEY (user_id) REFERENCES users(id),
			FOREIGN KEY (post_id) REFERENCES posts(post_id)
		)';
		$pdo->exec($sql);
		echo ('Comments Table Created!<br>');

		//Create the 'likes' table
		$sql = 'CREATE TABLE likes(
			like_id INT AUTO_INCREMENT,
			user_id INT NOT NULL,
			post_id INT NOT NULL,
			PRIMARY KEY (like_id),
			FOREIGN KEY (user_id) REFERENCES users(id),
			FOREIGN KEY (post_id) REFERENCES posts(post_id)
		)';
		$pdo->exec($sql);
		echo ('Likes Table Created!<br>');

		//Create the 'vkey' table
		$sql = 'CREATE TABLE vkey(
			id INT AUTO_INCREMENT,
			user_id INT NOT NULL,
			vkey VARCHAR(255) NOT NULL,
			PRIMARY KEY (id),
			FOREIGN KEY (user_id) REFERENCES users(id)
		)';
		$pdo->exec($sql);
		echo ('Vkey Table Created!<br>');

		$sql = 'CREATE TABLE rkey(
			id INT AUTO_INCREMENT,
			user_id INT NOT NULL,
			rkey VARCHAR(255) NOT NULL,
			PRIMARY KEY (id),
			FOREIGN KEY (user_id) REFERENCES users(id)
		)';
		$pdo->exec($sql);
		echo ('Rkey Table Created!<br>');

		$pdo = null;
	}catch(PDOException $e){
		echo ('Failed: '.$e->getMessage());
	}
?>
