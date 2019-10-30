<?php
	$msg = '';
	$msgClass = '';
	session_start();
	require('config/database.php');

	if (!isset($_SESSION['logged_in']))
	{
		header('Location: /Camagru/index.php?page=login.inc.php');
	}

	if (filter_has_var(INPUT_POST, 'submit')){
		//Store and validate inputs
		$title = trim(htmlspecialchars($_POST['title']));
		$message = htmlspecialchars($_POST['message']);

		$image = "";
		$dir = "images/posts/";
		$file = $dir.basename($_FILES["image"]["name"]);
		$uploadable = 1;
		$postable = 0;
		$imgFileType = strtolower(pathinfo($file, PATHINFO_EXTENSION));

		if (isset($_FILES["image"]["name"])){
			$check = getimagesize($_FILES["image"]["tmp_name"]);
			if ($check !== false){
				$msg = "File is an image - ".$check['mime'].".";
				$msgClass = 'w3-panel w3-pale-green w3-border';
				$uploadable = 1;
			}else{
				$msg = "File is not an image";
				$msgClass = 'w3-panel w3-pale-red w3-border';
				$uploadable = 0;
			}
		}

		if (file_exists($file)) {
			$image = "/Camagru/images/posts/". $_FILES["image"]["name"];
			$postable = 1;
			$msg = "Display picture successfully updated";
			$msgClass = 'w3-panel w3-pale-green w3-border';
			$uploadable = 0;
		}

		if ($_FILES["image"]["size"] > 5000000) {
			$msg = "File is too large";
			$msgClass = 'w3-panel w3-pale-red w3-border';
			$uploadable = 0;
		}

		if($imgFileType != "jpg" && $imgFileType != "png" && $imgFileType != "jpeg" && $imgFileType != "gif" ) {
			$msg =  "Only JPG, JPEG, PNG & GIF files are allowed.";
			$msgClass = 'w3-panel w3-pale-red w3-border';
			$uploadable = 0;
		}
		
		if ($uploadable == 1){
			if (move_uploaded_file($_FILES["image"]["tmp_name"], $file)){
				$image = "/Camagru/images/posts/". $_FILES["image"]["name"];
				$msg = "Display picture successfully updated";
				$msgClass = 'w3-panel w3-pale-green w3-border';
				$postable = 1;
			}else{
				$msg = "Failed";
				$msgClass = 'w3-panel w3-pale-red w3-border';
			}
		}
		
		if (!empty($title)){
			if ($postable)
			{
				try{
					$pdo = connectDB($DB_DSN, $DB_USER, $DB_PASSWORD);

					//Search the DB for the user
					$sql = 'INSERT INTO posts(user_id, title, image, body)
							VALUES(:user_id, :title, :image, :body)';
					$stmt = $pdo->prepare($sql);
					$stmt->execute(['user_id'=>$_SESSION['user']->id, 'title'=>$title, 'image'=>$image, 'body'=>$message]);

					header('Location: /Camagru/index.php');
				}catch(PDOException $e){
					echo $e->getMessage();
				}
			}else{
				$msgClass = 'w3-panel w3-pale-red w3-border';
			}
		}
		else{
			$msg = 'Please enter a title';
			$msgClass = 'w3-panel w3-pale-red w3-border';
		}
	}
?>

<?php $page_title = 'Camagru - Add Post!';require('inc/header.inc.php')?>
<div class="w3-container w3-padding w3-display-middle w3-half w3-border w3-border-red">
	<?php if($msg != ''): ?>
		<div class="<?php echo $msgClass; ?>">
			<?php echo $msg?>
		</div>
	<?php endif?>
	<form class="w3-container w3-card-4" action="" method="post" enctype="multipart/form-data">
		<h2 class="w3-text-red">Add Post</h2>
		<p>
			<label class="w3-text-red"><b>Title</b></label>
			<input class="w3-input w3-border w3-black" name="title" type="text" placeholder="Title" required value="<?php echo isset($_POST['title']) ? $title : ''; ?>">
		</p>
		<p>
			<label class="w3-text-red "><b>Image</b></label>
			<p>
				<input class="w3-border w3-black w3-input w3-hover-red" name="image" id="image_up" type="file" required>
				<p class="w3-text-red w3-center">Or</p>
				<button type="button" class="w3-input w3-hover-red w3-padding-medium w3-black w3-border" onclick="alert('Open Webcam')">Use Webcam</button>
			</p>
		</p>
		<p>
			<label class="w3-text-red"><b>Message</b></label>
			<textarea class="w3-input w3-border w3-black" name="message" id="message" placeholder="Message"><?php echo isset($_POST['message']) ? $message : ''; ?></textarea>
		</p>

		
		<p><input type="submit" name="submit" value="Post" class="w3-button w3-hover-red w3-padding-medium w3-black w3-border"></p>
	</form>
</div>
<?php require('inc/footer.inc.php')?>
