<?php
	$msg = '';
	$msg2 = '';
	$msgClass = '';
	session_start();

	if (isset($_SESSION['logged_in']))
	{
		header('Location: ../Camagru/index.php');
	}

	if (isset($_GET['rkey'])){
		$rkey = $_GET['rkey'];
		require('../config/database.php');
		try{
			$pdo = connectDB($DB_DSN, $DB_USER, $DB_PASSWORD);

			//Get the id of the user with the matching rkey
			$sql = 'SELECT user_id FROM rkey WHERE rkey = ? LIMIT 1';
			$stmt = $pdo->prepare($sql);
			$stmt->execute([$rkey]);

			//If the rkey is valid allow the password to be reset
			$user = $stmt->fetch();
			if ($stmt->rowCount() > 0)
			{
				if (filter_has_var(INPUT_POST, 'submit')){
						//Store and validate inputs
						$password = htmlspecialchars($_POST['password']);
						$password2 = htmlspecialchars($_POST['password2']);
						
						if (!empty($password) && !empty($password2)){
							$passMsg = 'Password must be atleast 8 characters long, contain 1 uppercase letter, 1 lowercase letter, 1 number and 1 special character';
							if (strlen($password) < 8){
								$msg2 = 'Too few characters in password<br>'.$passMsg;
								$msgClass = 'w3-panel w3-pale-red w3-border';
							}else if (!preg_match("@[A-Z]@", $password)){
								$msg2 = 'No uppercase letter in password<br>'.$passMsg;
								$msgClass = 'w3-panel w3-pale-red w3-border';
							}else if (!preg_match("@[a-z]@", $password)){
								$msg2 = 'No Lowercase letter in password<br>'.$passMsg;
								$msgClass = 'w3-panel w3-pale-red w3-border';
							}else if (!preg_match("@[0-9]@", $password)){
								$msg2 = 'No number in password<br>'.$passMsg;
								$msgClass = 'w3-panel w3-pale-red w3-border';
							}else if (!preg_match("@[^\w]@", $password)){
								$msg2 = 'No special character in password<br>'.$passMsg;
								$msgClass = 'w3-panel w3-pale-red w3-border';
							}else if ($password != $password2){
								$msg2 = 'Make sure you retyped your password correctly';
								$msgClass = 'w3-panel w3-pale-red w3-border';
							}else{//Execute if all of the input is valid//
								$sql = 'UPDATE users SET password = :password WHERE id=:user_id';
								$stmt = $pdo->prepare($sql);
								$stmt->execute(['password'=>password_hash($password, PASSWORD_DEFAULT), 'user_id'=>$user->user_id]);
								$sql = 'DELETE FROM rkey WHERE user_id = ?';
								$stmt = $pdo->prepare($sql);
								$stmt->execute([$user->user_id]);
								$msg = 'Password Successfully Reset<br>Redirecting...';
								header('Refresh: 5; URL=http://localhost/Camagru/index.php?page=login.inc.php');


							}
						}
						else{
							$msg = 'Please fill in all fields';
							$msgClass = 'w3-panel w3-pale-red w3-border';
						}
				}
			}else{
				$msg = 'Oops, Something went wrong';
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

<?php $page_title = 'Camagru - Welcome!';require('header.inc.php')?>
<div class="w3-container w3-padding w3-display-middle w3-half w3-border w3-border-red">
	<?php if($msg != ''): ?>
		<div class="<?php echo $msgClass; ?>">
			<p class="w3-text-white w3-center"><?php echo $msg?></p>
		</div>
<?php else:?>
	<?php if($msg2 != ''): ?>
		<div class="<?php echo $msgClass; ?>">
			<?php echo $msg2?>
		</div>
	<?php endif?>
	<form class="w3-container w3-card-4" action="" method="post">
		<h2 class="w3-text-red">Reset Password</h2>
		<p>
			<label class="w3-text-red"><b>New Password</b></label>
			<input class="w3-input w3-border w3-black" name="password" type="password" placeholder="New Password" required>
		</p>
		<p>
			<label class="w3-text-red"><b>Re-enter Password</b></label>
			<input class="w3-input w3-border w3-black" name="password2" type="password" required placeholder="Re-enter Password">
		</p>
		<p><input type="submit" name="submit" value="Reset" class="w3-button w3-hover-red w3-padding-medium w3-black w3-border"></p>
	</form>
	<?php endif?>
</div>
<?php require('footer.inc.php')?>
