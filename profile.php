<?php
	session_start();
	if (!isset($_SESSION['logged_in'])){
		header('Location: login.php');
	}
?>

<?php $page_title = 'Camagru - Welcome!';require('inc/header.php')?>
<div class="w3-container w3-padding w3-display-middle w3-half w3-border w3-border-red w3-center">
	<p class="w3-text-white "><?php echo $_SESSION['user']->first_name." ".$_SESSION['user']->last_name."'s Profile"?></p>
</div>
<?php require('inc/footer.php')?>