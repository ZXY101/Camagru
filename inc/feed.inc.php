<?php
	session_start();
	require('config/database.php');
	$start = 0;
	try{

		$pdo = connectDB($DB_DSN, $DB_USER, $DB_PASSWORD);
		if (!isset($_POST['order_by']))
			$sql = 'SELECT * FROM posts INNER JOIN users ON users.id = posts.user_id ORDER BY posts.published_at DESC';
		else
			$sql = 'SELECT * FROM posts INNER JOIN users ON users.id = posts.user_id ORDER BY '.htmlspecialchars($_POST['order_by']);
		$stmt = $pdo->prepare($sql);
		$stmt->execute();
		$posts = $stmt->fetchAll();
		
		$pdo = null;
		$stmt = null;
	}catch(PDOException $e){
		echo $e->getMessage();
	}
?>

<?php $page_title = 'Camagru - Welcome!';require('inc/header.inc.php')?>
<div class="w3-container w3-center feed w3-half w3-display-topmiddle">
	<br class="w3-hide-medium w3-hide-small hideme">
	<br class="w3-hide-medium w3-hide-small hideme">
	<h2 class="w3-padding w3-text-red">Feed</h2>
	<div class="w3-margin">
		<select id="orderBy" class="w3-select  w3-black w3-border w3-border-red" name="option" onchange="orderBy()">
			<option value="" disabled selected>Order By</option>
			<option value="posts.published_at DESC">Upload Date DESC</option>
			<option value="posts.published_at ASC">Upload Date ASC</option>
			<option value="posts.title DESC">Title DESC</option>
			<option value="posts.title ASC">Title ASC</option>
		</select> 
	</div>

	<div id="the_feed">
		<?php foreach ($posts as $post):?>
		<a href="/Camagru/inc/post.php?id=<?php echo $post->post_id?>">
			<div class="w3-card w3-margin w3-padding w3-border w3-border-red">
				<p class="w3-text-red"><?php echo $post->title?></p>
				<img class="w3-padding" src="<?php echo $post->image?>" style="max-width: 100%" alt="">
				<p class="w3-text-white"><?php echo $post->body?></p>
				<img src="<?php echo $post->display_picture?>" width="20px" class="w3-circle">
				<small class="w3-text-white"><?php echo $post->user_name?>, on <?php echo $post->published_at?></small>
			</div>
		</a>
		<?php endforeach?>
	</div>

</div>
<?php require('inc/footer.inc.php')?>