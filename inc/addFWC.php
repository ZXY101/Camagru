<?php
	if (isset($_POST['title']) && isset($_POST['image']) && isset($_POST['message']))
	{
		try{
			require('../config/database.php');
			session_start();
			$pdo = connectDB($DB_DSN, $DB_USER, $DB_PASSWORD);

			$title = trim(htmlspecialchars($_POST['title']));
			$message = htmlspecialchars($_POST['message']);
			
			$data = $_POST['image'];
			$data = str_replace('data:image/png;base64,', '', $data);
			$data = str_replace(' ', '+', $data);
			$data = base64_decode($data);

			
			$filename = md5(date('Y-m-d H:i:s:u'));
			
			$image = '/Camagru/images/posts/'.$filename.'.png';
			file_put_contents('../images/posts/'.$filename.'.png', $data);
			
			$sql = 'INSERT INTO posts(user_id, title, image, body)
					VALUES(:user_id, :title, :image, :body)';
			$stmt = $pdo->prepare($sql);
			$stmt->execute(['user_id'=>$_SESSION['user']->id, 'title'=>$title, 'image'=>$image, 'body'=>$message]);

		}catch(PDOException $e){
			echo $e->getMessage();
		}
	}else{
		require('404.php');
	}
?>