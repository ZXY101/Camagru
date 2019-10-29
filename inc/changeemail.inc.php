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
			$email = htmlspecialchars($_POST['email']);
			$email2 = htmlspecialchars($_POST['email2']);
			
			if (!empty($email) && !empty($email2)){
				try{
					$pdo = connectDB($DB_DSN, $DB_USER, $DB_PASSWORD);
					
					$sql = 'SELECT * FROM users WHERE email = ?';
					$stmt = $pdo->prepare($sql);
					$stmt->execute([$email]);
					$email_found = $stmt->rowCount();
				}catch(PDOException $e){
					echo $e->getMessage();
				}
				if (filter_var($email, FILTER_VALIDATE_EMAIL) === false){
					$msg = 'Please enter a valid email';
					$msgClass = 'w3-panel w3-pale-red w3-border';
				}else if ($email != $email2){
					$msg = 'Make sure you retyped your email correctly';
					$msgClass = 'w3-panel w3-pale-red w3-border';
				}else if ($email_found){
					$msg = 'Email already taken';
					$msgClass = 'w3-panel w3-pale-red w3-border';
				}else{//Execute if all of the input is valid//
					try{
						$sql = 'UPDATE users SET email = :email, is_verified = 0 WHERE id=:id';
						$stmt = $pdo->prepare($sql);
						$stmt->execute(['email'=>$email, 'id'=>$_SESSION['user']->id]);

						//Generate Verification Key
						$vkey = $email . date('mY');
						$vkey = md5($vkey);

						try{	
							//Add the vkey and userid to the vkey table
							$sql = 'INSERT INTO vkey(user_id, vkey) VALUES(:user_id, :vkey)';
							$stmt = $pdo->prepare($sql);
							$stmt->execute(['user_id'=>$_SESSION['user']->id, 'vkey'=>$vkey]);
						}catch(PDOException $e){
							echo $e->getMessage();
						}

						//Send the verification email
						$toEmail = $email;
						$subject = 'Camagru Email Verification';
						$body = '<h2>Verify your email</h2>
						<p>To verify your new email adress please follow this link:</p>
						<a href="http://localhost:8080/Camagru/inc/verify.php?vkey='.$vkey.'">Verify Email</a>';
						$headers = "MIME-Version: 1.0" . "\r\n";
						$headers .= "Content-Type:text/html;charset=UTF-8"."\r\n";
						$headers .= "From: <no-reply@camagru.com>"."\r\n";
						//$headers .= "From: <".$email.">"."\r\n";

						if (mail($toEmail, $subject, $body, $headers)){
							$msg = 'Email successfully changed, please verify your new email adress<br>Logging Out...';
							$msgClass = 'w3-panel w3-pale-green w3-border';
							unset($_SESSION['user']);
							unset($_SESSION['logged_in']);
							header('Refresh: 5; URL=http://localhost:8080/Camagru/index.php?page=login.inc.php');
						}else{
							$msg = 'Email Failed To Send';
							$msgClass = 'w3-panel w3-pale-red w3-border';
						}
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

<?php $page_title = 'Camagru - Change Email!';require('header.inc.php')?>
<div class="w3-container w3-padding w3-display-middle w3-half w3-border w3-border-red">
	<?php if($msg != ''): ?>
		<div class="<?php echo $msgClass; ?>">
			<?php echo $msg?>
		</div>
	<?php endif?>
	<form class="w3-container w3-card-4" action="" method="post">
		<h2 class="w3-text-red">Change Email</h2>
		<p>
			<label class="w3-text-red"><b>Email</b></label>
			<input class="w3-input w3-border w3-black" name="email" type="email" placeholder="New Email" required>
		</p>
		<p>
			<label class="w3-text-red"><b>Re-enter Email</b></label>
			<input class="w3-input w3-border w3-black" name="email2" type="email" placeholder="Re-enter Email" required>
		</p>
		<p><input type="submit" name="submit" value="Change" class="w3-button w3-hover-red w3-padding-medium w3-black w3-border" onclick="return confirm('Are you sure?')></p>
	</form>
</div>
<?php require('footer.inc.php')?>
