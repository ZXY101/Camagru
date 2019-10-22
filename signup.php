<?php
	$msg = '';
	$msgClass = '';
	require('config/database.php');

	if (filter_has_var(INPUT_POST, 'submit')){
		$firstName = ucwords(strtolower(trim(htmlspecialchars($_POST['first_name']))));
		$lastName = ucwords(strtolower(trim(htmlspecialchars($_POST['last_name']))));
		$userName = strtolower(trim(htmlspecialchars($_POST['user_name'])));
		$email = strtolower(trim(htmlspecialchars($_POST['email'])));
		$password = htmlspecialchars($_POST['password']);
		$password2 = htmlspecialchars($_POST['password_2']);
		

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
				$msg = 'Password must be atleast 8 characters long';
				$msgClass = 'w3-panel w3-pale-red w3-border';
			}else if (!preg_match("@[A-Z]@", $password)){
				$msg = 'Password must contain atleast one uppercase letter';
				$msgClass = 'w3-panel w3-pale-red w3-border';
			}else if (!preg_match("@[a-z]@", $password)){
				$msg = 'Password must contain atleast one lowercase letter';
				$msgClass = 'w3-panel w3-pale-red w3-border';
			}else if (!preg_match("@[0-9]@", $password)){
				$msg = 'Password must contain atleast one number';
				$msgClass = 'w3-panel w3-pale-red w3-border';
			}else if (!preg_match("@[^\w]@", $password)){
				$msg = 'Password must contain atleast one special character';
				$msgClass = 'w3-panel w3-pale-red w3-border';
			}else if ($password != $password2){
				$msg = 'Make sure you retyped your password correctly';
				$msgClass = 'w3-panel w3-pale-red w3-border';
			}else{
				$msg = 'User Added';
				$msgClass = 'w3-panel w3-pale-green w3-border';

				
				try{
				$sql = 'INSERT INTO users(user_name, first_name, last_name, email, password)
						VALUES(:user_name, :first_name, :last_name, :email, :password)';
				$stmt = $pdo->prepare($sql);
				$stmt->execute(['user_name'=>$userName, 'first_name'=>$firstName, 'last_name'=>$lastName, 'email'=>$email, 'password'=>password_hash($password, PASSWORD_DEFAULT)]);
				}catch(PDOException $e){
					echo $e->getMessage();
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

<?php $page_title = 'Camagru - Welcome!';require('inc/header.php')?>
<div class="w3-container w3-padding signup w3-display-middle w3-half">
	<?php if($msg != ''): ?>
		<div class="<?php echo $msgClass; ?>">
			<?php echo $msg?>
		</div>
	<?php endif?>
	<form class="w3-container w3-card-4" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post">
		<h2 class="w3-text-blue">Sign Up(Temp)</h2>
		<p>
			<label class="w3-text-blue"><b>First Name</b></label>
			<input class="w3-input w3-border" name="first_name" type="text" placeholder="First Name" required value="<?php echo isset($_POST['first_name']) ? $firstName : ''; ?>">
		</p>
		<p>
			<label class="w3-text-blue"><b>Last Name</b></label>
			<input class="w3-input w3-border" name="last_name" type="text" placeholder="Last Name" required value="<?php echo isset($_POST['last_name']) ? $lastName : ''; ?>">
		</p>
		<p>
		<p>
			<label class="w3-text-blue"><b>User Name</b></label>
			<input class="w3-input w3-border" name="user_name" type="text" placeholder="User Name" required value="<?php echo isset($_POST['user_name']) ? $userName : ''; ?>">
		</p>
		<p>
			<label class="w3-text-blue"><b>Email</b></label>
			<input class="w3-input w3-border" name="email" type="email" placeholder="Email" required value="<?php echo isset($_POST['email']) ? $email : ''; ?>">
		</p>
		<p>
			<label class="w3-text-blue"><b>Password</b></label>
			<input class="w3-input w3-border" name="password" type="password" placeholder="Password" required value="<?php echo isset($_POST['password']) ? $password : ''; ?>">
		</p>
		<p>
			<label class="w3-text-blue"><b>Re-enter Password</b></label>
			<input class="w3-input w3-border" name="password_2" type="password" placeholder="Re-enter Password" required value="<?php echo isset($_POST['password_2']) ? $password2 : ''; ?>">
		</p>
		
		<p><input type="submit" name="submit" value="Register" class="w3-btn w3-blue"></p>
	</form>
</div>
<?php require('inc/footer.php')?>
