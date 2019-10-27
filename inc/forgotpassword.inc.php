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
		
		if (!empty($userLogin)){
			try{
				$pdo = connectDB($DB_DSN, $DB_USER, $DB_PASSWORD);
				//Search the DB for the user
				$sql = 'SELECT * FROM users WHERE user_name = ? OR email = ?';
				$stmt = $pdo->prepare($sql);
				$stmt->execute([$userLogin, $userLogin]);
				$user_found = $stmt->rowCount();
				if ($user_found){
					$user = $stmt->fetch();
					if ($user->is_verified)
					{
						//Generate Reset Key
						$rkey = $user->user_name . $user->email . date('mY');
						$rkey = md5($rkey);

						try{
							//Add the rkey and userid to the rkey table
							$sql = 'INSERT INTO rkey(user_id, rkey) VALUES(:user_id, :rkey)';
							$stmt = $pdo->prepare($sql);
							$stmt->execute(['user_id'=>$user->id, 'rkey'=>$rkey]);
						}catch(PDOException $e){
							echo $e->getMessage();
						}

						//Send the verification email
						$toEmail = $user->email;
						$subject = 'Camagru Reset Password';
						$body = '<h2>Reset Password</h2>
						<p>Follow the link below to reset your password:</p>
						<a href="http://localhost/Camagru/inc/resetpassword.php?rkey='.$rkey.'">Reset Password</a>';
						$headers = "MIME-Version: 1.0" . "\r\n";
						$headers .= "Content-Type:text/html;charset=UTF-8"."\r\n";
						//$headers .= "From: <no-reply@camagru.com>"."\r\n";
						$headers .= "From: <".$user->email.">"."\r\n";

						if (mail($toEmail, $subject, $body, $headers)){
							$msg = 'An email to reset your password has been sent';
							$msgClass = 'w3-panel w3-pale-green w3-border';
							//header('Location: /Camagru/index.php?page=success.inc.php');
						}else{
							$msg = 'Email Failed To Send';
							$msgClass = 'w3-panel w3-pale-red w3-border';
						}
					}else{
						$msg = "Please verify your email adress first";
						$msgClass = 'w3-panel w3-pale-red w3-border';
					}
				}else{
					$msg = "Unknown username/email";
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
		<h2 class="w3-text-red">Forgot Password</h2>
		<p>
			<label class="w3-text-red"><b>User Name/Email</b></label>
			<input class="w3-input w3-border w3-black" name="user_login" type="text" placeholder="User Name/Email" required value="<?php echo isset($_POST['user_login']) ? $userLogin : ''; ?>">
		</p>
		<p><input type="submit" name="submit" value="Submit" class="w3-button w3-hover-red w3-padding-medium w3-black w3-border"></p>
	</form>
</div>
<?php require('inc/footer.inc.php')?>
