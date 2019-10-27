<?php
	session_start();
	if (!isset($_SESSION['logged_in'])){
		header('Location: /Camagru/index.php?page=login.inc.php');
	}
?>

<?php $page_title = 'Camagru - '.$_SESSION['user']->first_name." ".$_SESSION['user']->last_name."'s Profile".'!';require('inc/header.inc.php')?>
<div class="w3-container w3-padding w3-display-middle w3-half w3-border w3-border-red w3-center">
	<p class="w3-text-white "><?php echo $_SESSION['user']->first_name." ".$_SESSION['user']->last_name."'s Profile"?></p>
</div>
<?php require('inc/footer.inc.php')?>