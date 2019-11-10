<?php
	if (isset($_GET['id']))
	{
		session_start();
		require('../config/database.php');
		try{
			$pdo = connectDB($DB_DSN, $DB_USER, $DB_PASSWORD);
			$sql = 'SELECT * FROM posts INNER JOIN users ON users.id = posts.user_id WHERE post_id = ? ORDER BY posts.published_at DESC ';
			$stmt = $pdo->prepare($sql);
			$stmt->execute([$_GET['id']]);
			$post = $stmt->fetch();
			if ($stmt->rowCount() == 0)
				header('Location: 404');

			$sql = 'SELECT * FROM comments INNER JOIN users ON users.id = comments.user_id WHERE post_id = ? ORDER BY comments.posted_at DESC ';
			$stmt = $pdo->prepare($sql);
			$stmt->execute([$_GET['id']]);
			$comments = $stmt->fetchAll();

			$sql = 'SELECT * FROM likes INNER JOIN users ON users.id = likes.user_id WHERE post_id = ?';
			$stmt = $pdo->prepare($sql);
			$stmt->execute([$_GET['id']]);
			$likes = $stmt->rowCount();

			if (isset($_SESSION['logged_in'])){	
				$sql = 'SELECT * FROM likes INNER JOIN users ON users.id = likes.user_id WHERE post_id = :post_id AND user_id = :user_id';
				$stmt = $pdo->prepare($sql);
				$stmt->execute(['post_id'=>$_GET['id'], 'user_id'=>$_SESSION['user']->id]);
				$liked = $stmt->rowCount();
			}else{
				$liked = 0;
			}
			
			$pdo = null;
			$stmt = null;
		}catch(PDOException $e){
			echo $e->getMessage();
		}


		if (filter_has_var(INPUT_POST, 'like')){
			if (isset($_SESSION['logged_in']))
			{
				$pdo = connectDB($DB_DSN, $DB_USER, $DB_PASSWORD);
				if ($liked == 0){
					$sql = 'INSERT INTO likes(user_id, post_id) VALUES(:user_id, :post_id)';
					$stmt = $pdo->prepare($sql);
					$stmt->execute(['post_id'=>$_GET['id'], 'user_id'=>$_SESSION['user']->id]);
				}else{
					$sql = 'DELETE FROM likes WHERE user_id = :user_id AND post_id = :post_id';
					$stmt = $pdo->prepare($sql);
					$stmt->execute(['post_id'=>$_GET['id'], 'user_id'=>$_SESSION['user']->id]);
				}
				$pdo = null;
				$stmt = null;
				header('Location: /Camagru/inc/post.php?id='.$_GET['id']);
			}else{
				header('Location: /Camagru/index.php?page=login.inc.php');
			}
		}

		if (filter_has_var(INPUT_POST, 'submit')){
			$commentText = htmlspecialchars($_POST['comment']);
			try{
				$pdo = connectDB($DB_DSN, $DB_USER, $DB_PASSWORD);
				$sql = 'INSERT INTO comments(user_id, post_id, comment) 
						VALUES(:user_id, :post_id, :comment)';
				$stmt = $pdo->prepare($sql);
				$stmt->execute(['user_id'=>$_SESSION['user']->id, 'post_id'=>$_GET['id'], 'comment'=>$commentText]);

				if ($post->notifications == 1){
					$toEmail = $post->email;
					$subject = 'Someone commented on your Camagru post!';
					$body = '<h2>'.$_SESSION['user']->user_name.' commented on your post: '.$post->title.'</h2>
					<p>"'.$commentText.'"</p>
					<a href="http://localhost:8080/Camagru/inc/post.php?id='.$_GET['id'].'">View The Post</a>';
					$headers = "MIME-Version: 1.0" . "\r\n";
					$headers .= "Content-Type:text/html;charset=UTF-8"."\r\n";
					$headers .= "From: <no-reply@camagru.com>"."\r\n";
					//$headers .= "From: <xzxy101x@gmail.com>"."\r\n";
					

					if (mail($toEmail, $subject, $body, $headers)){
						echo 'sent';
					}else{
						echo 'failed';
					}
				}

				$pdo = null;
				$stmt = null;
				header('Location: /Camagru/inc/post.php?id='.$_GET['id']);
			}catch(PDOException $e){
				echo $e->getMessage();
			}
			
		}
	}
?>


<?php $page_title = 'Camagru - '.$post->title.'!';require('header.inc.php')?>
<div class="w3-container w3-center feed w3-half w3-display-topmiddle">
	<br class="w3-hide-medium w3-hide-small hideme">
	<br class="w3-hide-medium w3-hide-small hideme">
	<div class="w3-card w3-margin w3-padding w3-border w3-border-red">
		<h2 class="w3-text-red"><?php echo $post->title?></h2>
		<img class="w3-padding" src="<?php echo $post->image?>" style="max-width: 100%" alt="">
		<p class="w3-text-white"><?php echo $post->body?></p>
		<form action="" method="post">
		<p>
			<button type="submit" name="like" class="w3-black w3-border-0 ">
				<?php if ($liked):?>
					<i class="material-icons w3-text-red">thumb_up</i>
				<?php else:?>
					<i class="material-icons w3-text-white">thumb_up</i>
				<?php endif?>
			</button>
			<span class="w3-text-white"><?php echo $likes?></span>
		</p>
		</form>
		<img src="<?php echo $post->display_picture?>" width="30px" class="w3-circle">
		<small class="w3-text-white"><?php echo $post->user_name?>, on <?php echo $post->published_at?></small>
		<?php if (isset($_SESSION['user']) && ($_SESSION['user']->id == $post->user_id || $_SESSION['user']->is_admin == 1)):?>
			<button type="button" name="like" class="w3-black w3-border-0" onclick="deletePost()">
				<i class="material-icons w3-text-red">delete</i>
				<input type="hidden" id ="user_id" value = "<?php echo $_SESSION['user']->id?>">
				<input type="hidden" id ="post_id" value = "<?php echo $_GET['id']?>">
			</button>
		<?php endif?>
		<p class="w3-text-red">
			<a href="https://www.facebook.com/sharer/sharer.php?u=#url">Facebook</a>
			<a href="https://twitter.com/intent/tweet?text=Camagru%20Post%20http://localhost:8080/Camagru/inc/post.php?id=<?php echo $_GET['id']?>&hashtags=camagru">Twitter</a>
		</p>


		<div class="w3-card w3-margin w3-padding w3-border w3-border-red">
			<p class="w3-text-red">Comments</p>
			<?php if(isset($_SESSION['logged_in'])): ?>
			<form action="" method="post">
				<textarea class="w3-input w3-border w3-black" name="comment" id="comment" placeholder="Comment" required></textarea>
				<input type="submit" name="submit" value="Add Comment" class="w3-hover-red w3-padding-medium w3-black w3-border w3-input w3-margin-bottom">
			</form>
			<?php endif ?>
			<?php foreach($comments as $comment): ?>
				<div class="w3-card w3-margin-top w3-margin-bottom w3-padding w3-border w3-border-white">
					<div>
						<img src="<?php echo $comment->display_picture?>" width="30px" class="w3-circle w3-left w3-margin-small">
						<span class="w3-text-white w3-left w3-padding-small"><?php echo $comment->user_name?>:</span>
						<br>
					</div>
					<p class="w3-text-white" style="text-align:left"><?php echo $comment->comment?></p>
				</div>
			<?php endforeach?>
		</div>
	</div>
</div>
<?php require('footer.inc.php')?>