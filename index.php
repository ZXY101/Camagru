<?php
	session_start();
	require('config/database.php');

	$pdo = connectDB($DB_DSN, $DB_USER, $DB_PASSWORD);
	$sql = 'SELECT * FROM posts';
	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	$posts = $stmt->fetchAll();

	$pdo = null;
	$stmt = null;
?>

<?php $page_title = 'Camagru - Welcome!';require('inc/header.php')?>
<div class="w3-container w3-center">
	<?php foreach ($posts as $post):?>
	<div class="w3-card w3-margin w3-border w3-border-red w3-padding">
		<p class="w3-text-red"><?php echo $post->title?></p>
		<small class="w3-text-white">By: <?php echo $post->user_id?> Created on <?php echo $post->published_at?></small>
		<p class="w3-text-white"><?php echo $post->body?></p>
	</div>
	<?php endforeach?>
</div>
<?php require('inc/footer.php')?>
