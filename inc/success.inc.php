<?php
	session_start();
	if (isset($_SESSION['logged_in'])){
		header('Location: /Camagru/index.php');
	}
?>

<?php $page_title = 'Camagru - Welcome!';require('inc/header.inc.php')?>
<div class="w3-container w3-padding signup w3-display-middle w3-half w3-border w3-border-red w3-center">
	<p class="w3-text-white ">Thank you for registering, we have sent you an email to verify your account.</p>
	<a href="/Camagru/index.php?page=login.inc.php" class="w3-button w3-hover-red w3-padding-medium w3-black w3-border">Login</a>
</div>
<?php require('inc/footer.inc.php')?>