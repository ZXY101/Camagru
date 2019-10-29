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
			$userName = strtolower(trim(htmlspecialchars($_POST['username'])));
			
			if (!empty($userName)){
				try{
					$pdo = connectDB($DB_DSN, $DB_USER, $DB_PASSWORD);
					
					$sql = 'SELECT * FROM users WHERE user_name = ?';
					$stmt = $pdo->prepare($sql);
					$stmt->execute([$userName]);
					$username_found = $stmt->rowCount();
				}catch(PDOException $e){
					echo $e->getMessage();
				}

				if (!ctype_alnum($userName) || !preg_match("@[a-z]@", $userName)){
					$msg = 'Please enter a valid username';
					$msgClass = 'w3-panel w3-pale-red w3-border';
				}else if ($username_found){
					$msg = 'Username already taken';
					$msgClass = 'w3-panel w3-pale-red w3-border';
				}else{//Execute if all of the input is valid//
					try{
						$sql = 'UPDATE users SET user_name = :user_name WHERE id=:id';
						$stmt = $pdo->prepare($sql);
						$stmt->execute(['user_name'=>$userName, 'id'=>$_SESSION['user']->id]);
						$msg = 'Username successfully updated';
						$msgClass = 'w3-panel w3-pale-green w3-border';
						$_SESSION['user']->user_name = $userName;
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
		<h2 class="w3-text-red">Change Username</h2>
		<p>
			<label class="w3-text-red"><b>Username</b></label>
			<input class="w3-input w3-border w3-black" name="username" type="text" placeholder="New Username" required>
		</p>
		<p><input type="submit" name="submit" value="Change" class="w3-button w3-hover-red w3-padding-medium w3-black w3-border"></p>
	</form>
</div>
<?php require('footer.inc.php')?>
