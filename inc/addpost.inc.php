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
<div class="w3-container w3-display-topmiddle w3-half">
	<br class="w3-hide-medium w3-hide-small hideme">
	<br class="w3-hide-medium w3-hide-small hideme">
	<?php if($msg != ''): ?>
		<div class="<?php echo $msgClass; ?>">
			<?php echo $msg?>
		</div>
	<?php endif?>
	<div>
		<form class="w3-container w3-card-4 w3-border w3-border-red w3-margin" id="img_form" action="" method="post" enctype="multipart/form-data">
			<h2 class="w3-text-red">Add Post</h2>
			<p>
				<label class="w3-text-red"><b>Title</b></label>
				<input class="w3-input w3-border w3-black" name="title" id="img_title" type="text" placeholder="Title" required value="<?php echo isset($_POST['title']) ? $title : ''; ?>">
			</p>
			<p>
				<p><label class="w3-text-red "><b>Image</b></label></p>
				<div class="w3-center" style="display:none" id="preview_div"><img id="preview" style="max-width:100%" class="w3-margin-bottom"></div>
				<input class="w3-border w3-black w3-input w3-hover-red w3-text-white" name="image" id="image_up" type="file" required onchange="previewImg(event)">
				<p class="w3-text-red w3-center" id="or">Or</p>
				<button type="button" id="webcam_btn" class="w3-input w3-hover-red w3-padding-medium w3-black w3-border" onclick="open_webcam()">Use Webcam</button>
				<div class="w3-center" id="webcam" style="display: none">
					<div id="wc_img" class="w3-margin"></div>
					<video id="video" class="wc w3-border w3-border-red w3-image">Stream Not Available...</video>
					<canvas id="canvas" class="wc 3-border w3-border-red w3-image" style="display: none"></canvas>
					<button type="button" id="photo_btn" class="w3-input w3-hover-red w3-padding-medium w3-black w3-border">Take Photo</button>
					<button type="button" id="clear_btn" class="w3-input w3-hover-red w3-padding-medium w3-black w3-border" style="display: none">Clear</button>
				</div>
				<button type="button" id="back_btn" class="w3-input w3-hover-red w3-padding-medium w3-black w3-border" style="display: none" onclick="back_webcam()">Back</button>
			</p>
			<p>
				<label class="w3-text-red"><b>Message</b></label>
				<textarea class="w3-input w3-border w3-black" name="message" id="message" placeholder="Message"><?php echo isset($_POST['message']) ? $message : ''; ?></textarea>
			</p>
	
			
			<p>
				<input type="submit" name="submit" id="submit_input" value="Post" class="w3-button w3-hover-red w3-padding-medium w3-black w3-border">
			</p>
		<br class="w3-hide-medium w3-hide-small hideme">

		</form>
	</div>
</div>
<script src="js/webcam.js"></script>
<?php require('inc/footer.inc.php')?>
