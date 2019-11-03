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
			
			$pdo = null;
			$stmt = null;
		}catch(PDOException $e){
			echo $e.getMessage();
		}
	}
?>

<?php $page_title = 'Camagru - '.$post->title.'!';require('header.inc.php')?>
<div class="w3-container w3-center feed w3-half w3-display-middle">
	<div class="w3-card w3-margin w3-padding w3-border w3-border-red">
		<p class="w3-text-red"><?php echo $post->title?></p>
		<img class="w3-padding" src="<?php echo $post->image?>" style="max-width: 100%" alt="">
		<p class="w3-text-white"><?php echo $post->body?></p>
		<img src="<?php echo $post->display_picture?>" width="20px" class="w3-circle">
		<small class="w3-text-white"><?php echo $post->user_name?>, on <?php echo $post->published_at?></small>
	</div>
</div>
<?php require('footer.inc.php')?>