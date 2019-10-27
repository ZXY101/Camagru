<?php
	$msg = '';
	$msgClass = '';
	session_start();
	require('config/database.php');

	if (isset($_SESSION['logged_in']))
	{
		header('Location: /Camagru/index.php');
	}

	if (filter_has_var(INPUT_POST, 'submit')){
		//Store and validate inputs
		$userLogin = strtolower(trim(htmlspecialchars($_POST['user_login'])));
		$password = htmlspecialchars($_POST['password']);
		
		if (!empty($userLogin) && !empty($password)){
			try{
				$pdo = connectDB($DB_DSN, $DB_USER, $DB_PASSWORD);

				//Search the DB for the user
				$sql = 'SELECT * FROM users WHERE user_name = ? OR email = ?';
				$stmt = $pdo->prepare($sql);
				$stmt->execute([$userLogin, $userLogin]);
				$user_found = $stmt->rowCount();

				if ($user_found){
					$user = $stmt->fetch();
					//Check if the password is correct if the user is found
					if (password_verify($password, $user->password)){
						if ($user->is_verified){
							$pdo = null;
							$stmt = null;
							$_SESSION['user'] = $user;
							$_SESSION['logged_in'] = 1;
							header('Location: /Camagru/index.php');
						}else{
							$msg = "Please verify your email adress";
							$msgClass = 'w3-panel w3-pale-red w3-border';
						}
					}else{
						$msg = "Incorrect password";
						$msgClass = 'w3-panel w3-pale-red w3-border';
					}
				}else{
					$msg = "Invalid username/email";
					$msgClass = 'w3-panel w3-pale-red w3-border';
				}
			}catch(PDOException $e){
				echo $e->getMessage();
			}
		}
		else{
			$msg = 'Please fill in all fields';
			$msgClass = 'w3-panel w3-pale-red w3-border';
		}
	}
?>

<?php $page_title = 'Camagru - Welcome!';require('inc/header.inc.php')?>
<div class="w3-container w3-padding w3-display-middle w3-half w3-border w3-border-red">
	<?php if($msg != ''): ?>
		<div class="<?php echo $msgClass; ?>">
			<?php echo $msg?>
		</div>
	<?php endif?>
	<form class="w3-container w3-card-4" action="" method="post">
		<h2 class="w3-text-red">Log In</h2>
		<p>
			<label class="w3-text-red"><b>User Name/Email</b></label>
			<input class="w3-input w3-border w3-black" name="user_login" type="text" placeholder="User Name/Email" required value="<?php echo isset($_POST['user_login']) ? $userLogin : ''; ?>">
		</p>
		<p>
			<label class="w3-text-red"><b>Password</b></label>
			<input class="w3-input w3-border w3-black" name="password" type="password" required placeholder="Password">
		</p>
		<a href="/Camagru/index.php?page=forgotpassword.inc.php" class="w3-text-red"><small>Forgot Password?</small></a>

		
		<p><input type="submit" name="submit" value="Log In" class="w3-button w3-hover-red w3-padding-medium w3-black w3-border"></p>
	</form>
</div>
<?php require('inc/footer.inc.php')?>
