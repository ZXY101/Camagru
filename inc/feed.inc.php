<?php
	session_start();
	require('config/database.php');

	$pdo = connectDB($DB_DSN, $DB_USER, $DB_PASSWORD);
	$sql = 'SELECT * FROM posts INNER JOIN users ON users.id = posts.user_id ORDER BY posts.published_at DESC';
	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	$posts = $stmt->fetchAll();

	$pdo = null;
	$stmt = null;
?>

<?php $page_title = 'Camagru - Welcome!';require('inc/header.inc.php')?>
<div class="w3-container w3-center feed w3-half w3-display-topmiddle">
	<br class="w3-hide-medium w3-hide-small hideme">
	<br class="w3-hide-medium w3-hide-small hideme">
	<h2 class="w3-padding w3-text-red">Feed</h2>
	<?php foreach ($posts as $post):?>
	<div class="w3-card w3-margin w3-padding w3-border w3-border-red">
		<p class="w3-text-red"><?php echo $post->title?></p>
		<img class="w3-padding" src="<?php echo $post->image?>" style="max-width: 100%" alt="">
		<p class="w3-text-white"><?php echo $post->body?></p>
		<img src="<?php echo $post->display_picture?>" width="20px" class="w3-circle">
		<small class="w3-text-white"><?php echo $post->user_name?>, on <?php echo $post->published_at?></small>
	</div>
	<?php endforeach?>
</div>
<?php require('inc/footer.inc.php')?>
i