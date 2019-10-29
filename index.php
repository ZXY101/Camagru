<?php
	if (isset($_GET['page']))
	{
		$page = htmlspecialchars($_GET['page']);
		switch ($page) {
			case 'signup.inc.php':
				include('inc/signup.inc.php');
				break;
			case 'login.inc.php':
				include('inc/login.inc.php');
				break;
			case 'profile.inc.php':
				include('inc/profile.inc.php');
				break;
			case 'forgotpassword.inc.php':
				include('inc/forgotpassword.inc.php');
				break;
			case 'changepassword.inc.php':
				include('inc/changepassword.inc.php');
				break;
			case 'changeemail.inc.php':
				include('inc/changeemail.inc.php');
				break;
			case 'success.inc.php':
				include('inc/success.inc.php');
				break;
			case 'addpost.inc.php':
				include('inc/addpost.inc.php');
				break;
			default:
				include('inc/feed.inc.php');
		}
	}else{
		include('inc/feed.inc.php');
	}
?>
