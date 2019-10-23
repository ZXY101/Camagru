<?php
	$msg = '';
	$msgClass = '';
	require('config/database.php');

	if (filter_has_var(INPUT_POST, 'submit')){
		$userLogin = strtolower(trim(htmlspecialchars($_POST['user_login'])));
		$password = htmlspecialchars($_POST['password']);
		
		if (!empty($userLogin) && !empty($password)){
			try{
				$pdo = connectDB($DB_DSN, $DB_USER, $DB_PASSWORD);

				$sql = 'SELECT * FROM users WHERE user_name = :user_login OR email = :user_login';
				$stmt = $pdo->prepare($sql);
				$stmt->execute(['user_login'=>$userLogin]);
				$user_found = $stmt->rowCount();
				
				if ($user_found){
					$user = $stmt->fetch();

					if (password_verify($password, $user->password)){
						session_start();
						$_SESSION['user'] = $user;
						header('Location: index.php');
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

<?php $page_title = 'Camagru - Welcome!';require('inc/header.php')?>
<div class="w3-container w3-padding signup w3-display-middle w3-half w3-border w3-border-red">
	<?php if($msg != ''): ?>
		<div class="<?php echo $msgClass; ?>">
			<?php echo $msg?>
		</div>
	<?php endif?>
	<form class="w3-container w3-card-4" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post">
		<h2 class="w3-text-red">Log In(Temp)</h2>
		<p>
			<label class="w3-text-red"><b>User Name/Email</b></label>
			<input class="w3-input w3-border w3-grey" name="user_login" type="text" placeholder="User Name/Email" required value="<?php echo isset($_POST['user_login']) ? $userLogin : ''; ?>">
		</p>
		<p>
			<label class="w3-text-red"><b>Password</b></label>
			<input class="w3-input w3-border w3-grey" name="password" type="password" required placeholder="Password">
		</p>

		
		<p><input type="submit" name="submit" value="Log In" class="w3-btn w3-red"></p>
	</form>
</div>
<?php require('inc/footer.php')?>
