<?php
	$msg = '';
	$msgClass = '';
	session_start();

	if (!isset($_SESSION['logged_in']))
	{
		header('Location: /Camagru/index.php');
	}

 		require('config/database.php');
		if (filter_has_var(INPUT_POST, 'submit')){
			//Store and validate inputs
			$password = htmlspecialchars($_POST['password']);
			$password2 = htmlspecialchars($_POST['password2']);
			
			if (!empty($password) && !empty($password2)){
				$passMsg = 'Password must be atleast 8 characters long, contain 1 uppercase letter, 1 lowercase letter, 1 number and 1 special character';
				if (strlen($password) < 8){
					$msg = 'Too few characters in password<br>'.$passMsg;
					$msgClass = 'w3-panel w3-pale-red w3-border';
				}else if (!preg_match("@[A-Z]@", $password)){
					$msg = 'No uppercase letter in password<br>'.$passMsg;
					$msgClass = 'w3-panel w3-pale-red w3-border';
				}else if (!preg_match("@[a-z]@", $password)){
					$msg = 'No Lowercase letter in password<br>'.$passMsg;
					$msgClass = 'w3-panel w3-pale-red w3-border';
				}else if (!preg_match("@[0-9]@", $password)){
					$msg = 'No number in password<br>'.$passMsg;
					$msgClass = 'w3-panel w3-pale-red w3-border';
				}else if (!preg_match("@[^\w]@", $password)){
					$msg = 'No special character in password<br>'.$passMsg;
					$msgClass = 'w3-panel w3-pale-red w3-border';
				}else if ($password != $password2){
					$msg = 'Make sure you retyped your password correctly';
					$msgClass = 'w3-panel w3-pale-red w3-border';
				}else{//Execute if all of the input is valid//
					try{
						$pdo = connectDB($DB_DSN, $DB_USER, $DB_PASSWORD);
						$sql = 'UPDATE users SET password = :password WHERE id=:id';
						$stmt = $pdo->prepare($sql);
						$stmt->execute(['password'=>password_hash($password, PASSWORD_DEFAULT), 'id'=>$_SESSION['user']->id]);
						$msg = 'Password Successfully Changed';
						$msgClass = 'w3-panel w3-pale-green w3-border';
					}
					catch(PDOException $e){
						echo $e.getMessage();
					}
					}
			}
			else{
				$msg = 'Please fill in all fields';
				$msgClass = 'w3-panel w3-pale-red w3-border';
			} 
		}
		$pdo = null;
		$stmt = null;
?>

<?php $page_title = 'Camagru - Change Password!';require('header.inc.php')?>
<div class="w3-container w3-padding w3-display-middle w3-half w3-border w3-border-red">
	<?php if($msg != ''): ?>
		<div class="<?php echo $msgClass; ?>">
			<?php echo $msg?>
		</div>
	<?php endif?>
	<form class="w3-container w3-card-4" action="" method="post">
		<h2 class="w3-text-red">Change Password</h2>
		<p>
			<label class="w3-text-red"><b>New Password</b></label>
			<input class="w3-input w3-border w3-black" name="password" type="password" placeholder="New Password" required>
		</p>
		<p>
			<label class="w3-text-red"><b>Re-enter Password</b></label>
			<input class="w3-input w3-border w3-black" name="password2" type="password" required placeholder="Re-enter Password">
		</p>
		<p><input type="submit" name="submit" value="Change" class="w3-button w3-hover-red w3-padding-medium w3-black w3-border"></p>
	</form>
</div>
<?php require('footer.inc.php')?>
