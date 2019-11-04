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
			
			///////////////////////////


			$dest_image = imagecreatefrompng('../images/posts/'.$filename.'.png');

            imagesavealpha($dest_image, true);
            $trans_background = imagecolorallocatealpha($dest_image, 0, 0, 0, 127);
			imagefill($dest_image, 0, 0, $trans_background);
			
			$b = imagecreatefrompng('../images/posts/517597060854317056.png');

            imagecopy($dest_image, $b, 100, 100, 0, 0, 100, 100);
			
			header('Content-Type: image/png');
			imagepng($dest_image, '../images/posts/'.$filename.'.png');
			
			imagedestroy($b);
			imagedestroy($dest_image);
			

			///////////////////////

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