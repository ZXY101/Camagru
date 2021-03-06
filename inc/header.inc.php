<?php
	if (session_status() == PHP_SESSION_NONE) {
		session_start();
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title><?php echo $page_title?></title>
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
	<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css"> 
	<link rel="stylesheet" href="/Camagru/css/camagru.css">
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	
</head>
<body>
<div id="header" class="w3-hide-medium w3-hide-small">
	<div class="w3-bar w3-black w3-card w3-border-bottom w3-border-red">
		<a href="/Camagru/index.php" class="w3-bar-item w3-button w3-hover-red w3-padding-medium ">Camagru</a>
		<?php if(!isset($_SESSION['user'])):?>
			<a href="/Camagru/index.php?page=signup.inc.php" class="w3-bar-item w3-button w3-hover-red w3-padding-medium w3-right">Register</a>
			<a href="/Camagru/index.php?page=login.inc.php" class="w3-bar-item w3-button w3-hover-red w3-padding-medium w3-right">Login</a>
		<?php else:?>
		<form method="post" action="/Camagru/inc/logout.php">
			<input type="submit" class="w3-bar-item w3-button w3-hover-red w3-padding-medium w3-right" value="Logout">
		</form>
		<a href="/Camagru/index.php?page=addpost.inc.php" class="w3-bar-item w3-button w3-hover-red w3-padding-medium">&#10010;</a>
		<a href="/Camagru/index.php?page=profile.inc.php" class="w3-bar-item w3-button w3-hover-red w3-padding-medium w3-right">Welcome <?php echo $_SESSION['user']->first_name?></a>
		<?php endif?>
	</div>
</div>

<!-- Sidebar -->
<div class="sidebar">
	<div class="w3-sidebar w3-bar-block w3-black w3-center" style="display: none" id="sidebar">
	  <button onclick="close_sidebar()" class="w3-bar-item w3-button w3-hover-red w3-padding-medium w3-right">Close</button>
	  <a href="/Camagru/index.php" class="w3-bar-item w3-button w3-hover-red w3-padding-medium ">Feed</a>
		<?php if(!isset($_SESSION['user'])):?>
			<a href="/Camagru/index.php?page=signup.inc.php" class="w3-bar-item w3-button w3-hover-red w3-padding-medium w3-right">Register</a>
			<a href="/Camagru/index.php?page=login.inc.php" class="w3-bar-item w3-button w3-hover-red w3-padding-medium w3-right">Login</a>
		<?php else:?>
		<a href="/Camagru/index.php?page=addpost.inc.php" class="w3-bar-item w3-button w3-hover-red w3-padding-medium">Add Post</a>
		<a href="/Camagru/index.php?page=profile.inc.php" class="w3-bar-item w3-button w3-hover-red w3-padding-medium w3-right">Profile</a>
		<form method="post" action="inc/logout.php">
			<input type="submit" class="w3-bar-item w3-button w3-hover-red w3-padding-medium w3-right" value="Logout">
		</form>
		<?php endif?>
	</div>
	
	<button class="w3-button w3-black w3-hover-red w3-xlarge burger w3-opacity" onclick="open_sidebar()">☰</button>
</div>
<script src="/Camagru/js/sidebar.js"></script>


