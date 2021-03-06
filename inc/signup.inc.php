<?php
	$msg = '';
	$msgClass = '';
	session_start();
	
	if (isset($_SESSION['logged_in']))
	{
		header('Location: /Camagru/index.php');
	}
	
	if (filter_has_var(INPUT_POST, 'submit')){
		require('config/database.php');
		//Store all of the fields in variables and clean up the input
		$firstName = ucwords(strtolower(trim(htmlspecialchars($_POST['first_name']))));
		$lastName = ucwords(strtolower(trim(htmlspecialchars($_POST['last_name']))));
		$userName = strtolower(trim(htmlspecialchars($_POST['user_name'])));
		$email = strtolower(trim(htmlspecialchars($_POST['email'])));
		$password = htmlspecialchars($_POST['password']);
		$password2 = htmlspecialchars($_POST['password_2']);
		
		//Verify all of the user input
		if (!empty($firstName) && !empty($lastName) && !empty($userName) && !empty($email) && !empty($password) && !empty($password2))	
		{
			try{
				$pdo = connectDB($DB_DSN, $DB_USER, $DB_PASSWORD);

				$sql = 'SELECT * FROM users WHERE user_name = ?';
				$stmt = $pdo->prepare($sql);
				$stmt->execute([$userName]);
				$username_found = $stmt->rowCount();
				
				$sql = 'SELECT * FROM users WHERE email = ?';
				$stmt = $pdo->prepare($sql);
				$stmt->execute([$email]);
				$email_found = $stmt->rowCount();
			}catch(PDOException $e){
				echo $e->getMessage();
			}
			$passMsg = 'Password must be atleast 8 characters long, contain 1 uppercase letter, 1 lowercase letter, 1 number and 1 special character';
			if (!ctype_alpha($firstName)){
				$msg = 'Please enter a valid first name';
				$msgClass = 'w3-panel w3-pale-red w3-border';
			}else if (!ctype_alpha($lastName)){
				$msg = 'Please enter a valid last name';
				$msgClass = 'w3-panel w3-pale-red w3-border';
			}else if (!ctype_alnum($userName) || !preg_match("@[a-z]@", $userName)){
				$msg = 'Please enter a valid username';
				$msgClass = 'w3-panel w3-pale-red w3-border';
			}else if ($username_found){
				$msg = 'Username already taken';
				$msgClass = 'w3-panel w3-pale-red w3-border';
			}else if (filter_var($email, FILTER_VALIDATE_EMAIL) === false){
				$msg = 'Please enter a valid email';
				$msgClass = 'w3-panel w3-pale-red w3-border';
			}else if ($email_found){
				$msg = 'Email already taken';
				$msgClass = 'w3-panel w3-pale-red w3-border';
			}else if (strlen($password) < 8){
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
				$msg = 'User Added';
				$msgClass = 'w3-panel w3-pale-green w3-border';

				//Generate Verification Key
				$vkey = $userName . $email . date('mY');
				$vkey = md5($vkey);
				
				try{
				//Add the user to the DB
				$sql = 'INSERT INTO users(user_name, first_name, last_name, email, password)
					VALUES(:user_name, :first_name, :last_name, :email, :password)';
				$stmt = $pdo->prepare($sql);
				$stmt->execute(['user_name'=>$userName, 'first_name'=>$firstName, 'last_name'=>$lastName, 'email'=>$email, 'password'=>password_hash($password, PASSWORD_DEFAULT)]);

				//Add the vkey and userid to the vkey table
				$sql = 'INSERT INTO vkey(user_id, vkey) VALUES(:user_id, :vkey)';
				$stmt = $pdo->prepare($sql);
				$stmt->execute(['user_id'=>$pdo->lastInsertId(), 'vkey'=>$vkey]);
				}catch(PDOException $e){
					echo $e->getMessage();
				}

				//Send the verification email
				$toEmail = $email;
				$subject = 'Camagru Email Verification';
				$body = '<h2>Verify your email</h2>
				<p>Thank you for registering to Camagru, to verify your email adress please follow this link:</p>
				<a href="http://localhost:8080/Camagru/inc/verify.php?vkey='.$vkey.'">Verify Email</a>';
				$headers = "MIME-Version: 1.0" . "\r\n";
				$headers .= "Content-Type:text/html;charset=UTF-8"."\r\n";
				$headers .= "From: <no-reply@camagru.com>"."\r\n";
				//$headers .= "From: <xzxy101x@gmail.com>"."\r\n";


				if (mail($toEmail, $subject, $body, $headers)){
					$msg = 'Success';
					$msgClass = 'w3-panel w3-pale-green w3-border';
					header('Location: /Camagru/index.php?page=success.inc.php');
				}else{
					$msg = 'Email Failed To Send';
					$msgClass = 'w3-panel w3-pale-red w3-border';
				}

			}
		}else
		{
			$msg = 'Please fill in all fields';
			$msgClass = 'w3-panel w3-pale-red w3-border';
		}

		$pdo = null;
		$stmt = null;
	}

?>

<?php $page_title = 'Camagru - Sign Up!';require('inc/header.inc.php')?>
<div class="w3-container w3-padding w3-display-middle w3-half w3-border w3-border-red mobile">
	<?php if($msg != ''): ?>
		<div class="<?php echo $msgClass; ?>">
			<?php echo $msg?>
		</div>
	<?php endif?>
	<form class="w3-container w3-card-4" action="" method="post">
		<h2 class="w3-text-red">Sign Up</h2>
		<p>
			<label class="w3-text-red bold">First Name</label>
			<input class="w3-input w3-border w3-black" name="first_name" type="text" placeholder="First Name" required value="<?php echo isset($_POST['first_name']) ? $firstName : ''; ?>">
		</p>
		<p>
			<label class="w3-text-red bold">Last Name</label>
			<input class="w3-input w3-border w3-black" name="last_name" type="text" placeholder="Last Name" required value="<?php echo isset($_POST['last_name']) ? $lastName : ''; ?>">
		</p>
		<p>
		<p>
			<label class="w3-text-red bold">User Name</label>
			<input class="w3-input w3-border w3-black" name="user_name" type="text" placeholder="User Name" required value="<?php echo isset($_POST['user_name']) ? $userName : ''; ?>">
		</p>
		<p>
			<label class="w3-text-red bold">Email</label>
			<input class="w3-input w3-border w3-black" name="email" type="email" placeholder="Email" required value="<?php echo isset($_POST['email']) ? $email : ''; ?>">
		</p>
		<p>
			<label class="w3-text-red bold">Password</label>
			<input class="w3-input w3-border w3-black" name="password" type="password" placeholder="Password" required >
		</p>
		<p>
			<label class="w3-text-red bold">Re-enter Password</label>
			<input class="w3-input w3-border w3-black" name="password_2" type="password" placeholder="Re-enter Password" required>
		</p>
		
		<p><input type="submit" name="submit" value="Register" class="w3-button w3-hover-red w3-padding-medium w3-black w3-border"></p>
	</form>
</div>
<?php require('inc/footer.inc.php')?>
