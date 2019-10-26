<?php
	$msg = '';
	$msgClass = '';
	session_start();
	require('config/database.php');

	if (!isset($_SESSION['logged_in']))
	{
		header('Location: login.php');
	}

	if (filter_has_var(INPUT_POST, 'submit')){
		//Store and validate inputs
		$title = trim(htmlspecialchars($_POST['title']));
		$message = htmlspecialchars($_POST['message']);
		
		if (!empty($title) && !empty($message)){
			try{
				$pdo = connectDB($DB_DSN, $DB_USER, $DB_PASSWORD);

				//Search the DB for the user
				$sql = 'INSERT INTO posts(user_id, title, body)
						VALUES(:user_id, :title, :body)';
				$stmt = $pdo->prepare($sql);
				$stmt->execute(['user_id'=>$_SESSION['user']->id, 'title'=>$title, 'body'=>$message]);

				header('Location: index.php');
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

<?php $page_title = 'Camagru - Add Post!';require('inc/header.php')?>
<div class="w3-container w3-padding w3-display-middle w3-half w3-border w3-border-red">
	<?php if($msg != ''): ?>
		<div class="<?php echo $msgClass; ?>">
			<?php echo $msg?>
		</div>
	<?php endif?>
	<form class="w3-container w3-card-4" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post">
		<h2 class="w3-text-red">Add Post</h2>
		<p>
			<label class="w3-text-red"><b>Title</b></label>
			<input class="w3-input w3-border w3-black" name="title" type="text" placeholder="Title" required value="<?php echo isset($_POST['user_login']) ? $userLogin : ''; ?>">
		</p>
		<p>
			<label class="w3-text-red"><b>Message</b></label>
			<textarea class="w3-input w3-border w3-black" name="message" id="message" placeholder="Message"></textarea>
		</p>

		
		<p><input type="submit" name="submit" value="Post" class="w3-button w3-hover-red w3-padding-medium w3-black w3-border"></p>
	</form>
</div>
<?php require('inc/footer.php')?>
