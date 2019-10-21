<?php

if (filter_has_var(INPUT_POST, 'submit')){
	$firstName = htmlspecialchars($_POST['first_name']);
	$lastName = htmlspecialchars($_POST['last_name']);
	$userName = htmlspecialchars($_POST['user_name']);
	$email = htmlspecialchars($_POST['email']);
	$password = htmlspecialchars($_POST['password']);
	$password2 = htmlspecialchars($_POST['password_2']);
	
	require('config/database.php');
	$pdo = connectDB($DB_DSN, $DB_USER, $DB_PASSWORD);
	$sql = 'INSERT INTO users(user_name, first_name, last_name, email, password)
			VALUES(:user_name, :first_name, :last_name, :email, :password)';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(['user_name'=>$userName, 'first_name'=>$firstName, 'last_name'=>$lastName, 'email'=>$email, 'password'=>$password,]);
}


?>

<?php $page_title = 'Camagru - Welcome!';require('inc/header.php')?>
<div class="w3-container w3-padding">
	<form class="w3-container w3-card-4" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post">
		<h2 class="w3-text-blue">Sign Up(Temp)</h2>
		<p>
			<label class="w3-text-blue"><b>First Name</b></label>
			<input class="w3-input w3-border" name="first_name" type="text">
		</p>
		<p>
			<label class="w3-text-blue"><b>Last Name</b></label>
			<input class="w3-input w3-border" name="last_name" type="text">
		</p>
		<p>
		<p>
			<label class="w3-text-blue"><b>User Name</b></label>
			<input class="w3-input w3-border" name="user_name" type="text">
		</p>
		<p>
			<label class="w3-text-blue"><b>Email</b></label>
			<input class="w3-input w3-border" name="email" type="email">
		</p>
		<p>
			<label class="w3-text-blue"><b>Password</b></label>
			<input class="w3-input w3-border" name="password" type="text">
		</p>
		<p>
			<label class="w3-text-blue"><b>Re-enter Password</b></label>
			<input class="w3-input w3-border" name="password_2" type="text">
		</p>
		
		<p><input type="submit" name="submit" value="Register" class="w3-btn w3-blue"></p>
	</form>
</div>
<?php require('inc/footer.php')?>
