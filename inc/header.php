<?php
	session_start();
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
	<link rel="stylesheet" href="css/camagru.css">
	<link href="https://fonts.googleapis.com/css?family=Advent+Pro&display=swap" rel="stylesheet">
</head>
<body>
<div class="w3-top">
	<div class="w3-bar w3-black w3-card w3-border-bottom w3-border-red">
		<a href="index.php" class="w3-bar-item w3-button w3-hover-red w3-padding-medium ">Home</a>
		<?php if(!isset($_SESSION['user'])):?>
			<a href="signup.php" class="w3-bar-item w3-button w3-hover-red w3-padding-medium w3-right">Register</a>
			<a href="login.php" class="w3-bar-item w3-button w3-hover-red w3-padding-medium w3-right">Login</a>
		<?php else:?>
		<form method="post" action="inc/logout.inc.php">
			<input type="submit" class="w3-bar-item w3-button w3-hover-red w3-padding-medium w3-right" value="Logout">
		</form>
		<a href="#" class="w3-bar-item w3-button w3-hover-red w3-padding-medium w3-right">Welcome <?php echo $_SESSION['user']->first_name?></a>
		<?php endif?>
	</div>
</div>
