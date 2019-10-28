<?php
	session_start();
	if (!isset($_SESSION['logged_in'])){
		header('Location: /Camagru/index.php?page=login.inc.php');
	}
?>

<?php $page_title = 'Camagru - '.$_SESSION['user']->first_name." ".$_SESSION['user']->last_name."'s Profile".'!';require('inc/header.inc.php')?>
<div class="w3-container w3-padding w3-display-middle w3-half w3-border w3-border-red ">
	<p class="w3-text-red w3-center"><?php echo $_SESSION['user']->first_name." ".$_SESSION['user']->last_name."'s Profile"?></p>
	<div class="w3-text-white w3-border-bottom w3-border-white">
		<p>Display Picture:</p>
		<p class="w3-center image-upload">
			<label for="change_dp">
				<img src="<?php echo is_null($_SESSION['user']->display_picture) ? 'https://upload.wikimedia.org/wikipedia/commons/7/72/Default-welcomer.png' : $_SESSION['user']->display_picture?>" class="w3-circle w3-image w3-border w3-border-white w3-margin w3-hover-sepia" style="width:100%;max-width:200px; height:200px" alt="">
			</label>
			<input type="file" accept="image/*" name="change_dp" id="change_dp" class="w3-input">
		</p>
	</div>
	<div class="w3-text-white w3-border-bottom w3-border-white">
		<a href="#" class="w3-hover-red w3-black w3-right">Change</a>
		<p>User Name: <span class="w3-text-red"><?php echo $_SESSION['user']->user_name?></span></p>
	</div>
	<div class="w3-text-white w3-border-bottom w3-border-white">
		<a href="#" class="w3-hover-red w3-black w3-right">Change</a>
		<p>Email: <span class="w3-text-red"><?php echo $_SESSION['user']->email?></span></p>
	</div>
	<div class="w3-text-white w3-border-bottom w3-border-white">
		<a href="#" class="w3-hover-red w3-black w3-right">Change</a>
		<p>Password: <span class="w3-text-red">••••••••</span></p>
	</div>
	<div class="w3-text-white">
		<a href="#" class="w3-hover-red w3-black w3-right">Change</a>
		<p>Email Preference: <span class="w3-text-red">Send Notifications</span></p>
	</div>

</div>
<?php require('inc/footer.inc.php')?>