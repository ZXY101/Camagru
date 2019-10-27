<?php
	//Verify the user
	$msg = 'Oops, Something went wrong';
	session_start();
	if (isset($_SESSION['logged_in'])){
		header('Location: /Camagru/index.php');
	}
	
	if (isset($_GET['vkey'])){
		$vkey = $_GET['vkey'];
		require('../config/database.php');
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
				$msg = 'Account successfully registered.<br>Redirecting...';
				$sql = 'DELETE FROM vkey WHERE user_id = ?';
				$stmt = $pdo->prepare($sql);
				$stmt->execute([$user->user_id]);
				$pdo = null;
				$stmt = null;
				header('Refresh: 5; URL=http://localhost/Camagru/index.php?page=login.inc.php');
			}
			$pdo = null;
			$stmt = null;
		}catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}else{
		$msg = 'Oops, Something went wrong';
	}
?>

<?php $page_title = 'Camagru - Verified!';require('header.inc.php')?>
<div class="w3-container w3-padding signup w3-display-middle w3-half w3-border w3-border-red">
	<p class="w3-text-white w3-center"><?php echo $msg?></p>
</div>
<?php require('footer.inc.php')?>