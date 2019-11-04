<?php
	session_start();
	if (isset($_POST['user_id']) && isset($_POST['post_id'])){
		require('../config/database.php');
		if($_SESSION['user']->id == $_POST['user_id'] || $_SESSION['user']->is_admin == 1)
		{
			try{
				$pdo = connectDB($DB_DSN, $DB_USER, $DB_PASSWORD);

				$sql = 'DELETE FROM comments WHERE post_id = ?';
				$stmt = $pdo->prepare($sql);
				$stmt->execute([$_POST['post_id']]);

				$sql = 'DELETE FROM likes WHERE post_id = ?';
				$stmt = $pdo->prepare($sql);
				$stmt->execute([$_POST['post_id']]);

				$sql = 'DELETE FROM posts WHERE post_id = ?';
				$stmt = $pdo->prepare($sql);
				$stmt->execute([$_POST['post_id']]);

			}catch(PDOException $e){
				echo $e->getMessage();
			}
		}
	}else{
		require('404.php');
	}
?>