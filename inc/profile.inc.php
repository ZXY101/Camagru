<?php
	session_start();
	$msg = "";
	$msgClass = "";
	if (!isset($_SESSION['logged_in'])){
		header('Location: /Camagru/index.php?page=login.inc.php');
	}

	//Change display picture
	if (isset($_FILES["change_dp"]["name"])){
		$dir = "images/display_pictures/";
		$file = $dir.basename($_FILES["change_dp"]["name"]);
		$uploadable = 1;
		$imgFileType = strtolower(pathinfo($file, PATHINFO_EXTENSION));

		if (isset($_FILES["change_dp"]["name"])){
			$check = getimagesize($_FILES["change_dp"]["tmp_name"]);
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
			try{
				require('config/database.php');
				$pdo = connectDB($DB_DSN, $DB_USER, $DB_PASSWORD);

				$sql = "UPDATE users SET display_picture = :dp WHERE id = :id";
				$stmt = $pdo->prepare($sql);
				$stmt->execute(['dp'=>'/Camagru/images/display_pictures/'.$_FILES["change_dp"]["name"], 'id'=>$_SESSION['user']->id]);
				$_SESSION['user']->display_picture = '/Camagru/images/display_pictures/'.$_FILES["change_dp"]["name"];
			}catch(PDOException $e){
				$msg = $e.getMessage();
			}
			$msg = "Display picture successfully updated";
			$msgClass = 'w3-panel w3-pale-green w3-border';
			$uploadable = 0;
		}

		if ($_FILES["change_dp"]["size"] > 5000000) {
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
			if (move_uploaded_file($_FILES["change_dp"]["tmp_name"], $file)){
				//Update in DB
				try{
					require('config/database.php');
					$pdo = connectDB($DB_DSN, $DB_USER, $DB_PASSWORD);

					$sql = "UPDATE users SET display_picture = :dp WHERE id = :id";
					$stmt = $pdo->prepare($sql);
					$stmt->execute(['dp'=>'/Camagru/images/display_pictures/'.$_FILES["change_dp"]["name"], 'id'=>$_SESSION['user']->id]);
					$_SESSION['user']->display_picture = '/Camagru/images/display_pictures/'.$_FILES["change_dp"]["name"];
				}catch(PDOException $e){
					$msg = $e.getMessage();
				}
				$msg = "Display picture successfully updated";
				$msgClass = 'w3-panel w3-pale-green w3-border';
			}else{
				$msg = "Failed";
				$msgClass = 'w3-panel w3-pale-red w3-border';
			}
		}
		$pdo = null;
		$stmt = null;
		header('Refresh: 2; URL=http://localhost:8080/Camagru/index.php?page=profile.inc.php', true, 303);
	}

	//Change the email notification preference
	if (isset($_POST['email_pref']))
	{
		require('config/database.php');
		if ($_SESSION['user']->notifications == 1){
			$_SESSION['user']->notifications = 0;
		}else{
			$_SESSION['user']->notifications = 1;
		}
		try{
			$pdo = connectDB($DB_DSN, $DB_USER, $DB_PASSWORD);
			$sql = 'UPDATE users SET notifications = :notifications WHERE id = :id';
			$stmt = $pdo->prepare($sql);
			$stmt->execute(['notifications'=>$_SESSION['user']->notifications, 'id'=>$_SESSION['user']->id]);
		}catch(PDOException $e){
			echo $e->getMessage();
		}
		$pdo = null;
		$stmt = null;
		header("Location: /Camagru/index.php?page=profile.inc.php", true, 303);
	}
?>

<?php $page_title = 'Camagru - '.$_SESSION['user']->first_name." ".$_SESSION['user']->last_name."'s Profile".'!';require('inc/header.inc.php')?>
<div class="w3-container w3-padding w3-display-middle w3-half w3-border w3-border-red ">
	<?php if($msg != ''): ?>
		<div class="<?php echo $msgClass; ?>">
			<?php echo $msg?>
		</div>
	<?php endif?>
	<p class="w3-text-red w3-center"><?php echo $_SESSION['user']->first_name." ".$_SESSION['user']->last_name."'s Profile"?></p>
	<div class="w3-text-white w3-border-bottom w3-border-white">
		<p>Display Picture:</p>
		<p class="w3-center image-upload">
			<label for="change_dp">
				<img src="<?php echo is_null($_SESSION['user']->display_picture) ? 'https://upload.wikimedia.org/wikipedia/commons/7/72/Default-welcomer.png' : $_SESSION['user']->display_picture?>" class="w3-circle w3-image w3-border w3-border-white w3-margin w3-hover-sepia" style="width:100%;max-width:200px; height:200px" alt="">
			</label>
			<form method="post" action="" enctype="multipart/form-data" id="form_dp">
				<input type="file" accept="image/*" name="change_dp" id="change_dp" class="w3-input" style="display:none" onchange="form.submit()">
			</form>

		</p>
	</div>
	<div class="w3-text-white w3-border-bottom w3-border-white">
		<a href="/Camagru/index.php?page=changeusername.inc.php" class="w3-hover-red w3-black w3-right">Change</a>
		<p>User Name: <span class="w3-text-red"><?php echo $_SESSION['user']->user_name?></span></p>
	</div>
	<div class="w3-text-white w3-border-bottom w3-border-white">
		<a href="/Camagru/index.php?page=changeemail.inc.php" class="w3-hover-red w3-black w3-right">Change</a>
		<p>Email: <span class="w3-text-red"><?php echo $_SESSION['user']->email?></span></p>
	</div>
	<div class="w3-text-white w3-border-bottom w3-border-white">
		<a href="/Camagru/index.php?page=changepassword.inc.php" class="w3-hover-red w3-black w3-right">Change</a>
		<p>Password: <span class="w3-text-red">••••••••</span></p>
	</div>
	<div class="w3-text-white">
		<form action="" method="post">
			<label for="email_pref">
				<span href="#" class="w3-hover-red w3-black w3-right email_pref">Change</span>
			</label>
			<input type="submit" name="email_pref" id="email_pref" style="display: none">
		</form>
		<p>Email Preference: <span class="w3-text-red"><?php echo $_SESSION['user']->notifications == 0 ? 'No Notifications' : 'Send Notifications'?></span></p>
	</div>
</div>
<?php require('inc/footer.inc.php')?>
