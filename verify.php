<?php
	//Verify the user
	if (isset($_GET['vkey'])){
		$vkey = $_GET['vkey'];
		require('config/database.php');
		try{
			$pdo = connectDB($DB_DSN, $DB_USER, $DB_PASSWORD);

			//Get the id of the user with the matching vkey
			$sql = 'SELECT user_id FROM vkey WHERE vkey = ?';
			$stmt = $pdo->prepare($sql);
			$stmt->execute([$vkey]);

			//If the user is found, verify them
			$user = $stmt->fetch();
			if ($stmt->rowCount() > 0)
			{
				$sql = 'UPDATE users SET is_verified = 1 WHERE id = ?';
				$stmt = $pdo->prepare($sql);
				$stmt->execute([$user->user_id]);
				header('Location: login.php');
			}
			$pdo = null;
			$stmt = null;
		}catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}else{
		die('Oops, Something went wrong.');
	}
?>