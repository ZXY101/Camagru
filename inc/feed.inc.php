<?php
	session_start();
	require('config/database.php');
	$start = 0;
	try{

		$pdo = connectDB($DB_DSN, $DB_USER, $DB_PASSWORD);
		$sql = 'SELECT * FROM posts INNER JOIN users ON users.id = posts.user_id ORDER BY posts.published_at DESC';
		if (isset($_POST['order_by'])){
			switch ($_POST['order_by']){
				case 1:
					$sql = 'SELECT * FROM posts INNER JOIN users ON users.id = posts.user_id ORDER BY posts.published_at DESC';
					break;
				case 2:
					$sql = 'SELECT * FROM posts INNER JOIN users ON users.id = posts.user_id ORDER BY posts.published_at ASC';
					break;
				case 3:
					$sql = 'SELECT * FROM posts INNER JOIN users ON users.id = posts.user_id ORDER BY posts.title DESC';
					break;
				case 4:
					$sql = 'SELECT * FROM posts INNER JOIN users ON users.id = posts.user_id ORDER BY posts.title ASC';
					break;
				case 5:
					$sql = 'SELECT * FROM posts INNER JOIN users ON users.id = posts.user_id ORDER BY users.user_name DESC';
					break;
				case 6:
					$sql = 'SELECT * FROM posts INNER JOIN users ON users.id = posts.user_id ORDER BY users.user_name ASC';
					break;
				default:
					$sql = 'SELECT * FROM posts INNER JOIN users ON users.id = posts.user_id ORDER BY posts.published_at DESC';
			}
		}
		$stmt = $pdo->prepare($sql);
		$stmt->execute();
		$posts = $stmt->fetchAll();
		$postCount = $stmt->rowCount();
		
		$pdo = null;
		$stmt = null;
	}catch(PDOException $e){
		echo $e->getMessage();
	}

	$ppp = 5;

	if ($postCount >= $ppp)
		$postsOD = $ppp;
	else
		$postsOD = $postCount;
		
		if (isset($_POST['displayed_posts'])){
			if (($_POST['displayed_posts'] + 5) >= $postCount)
				$postsOD = $postCount;
			else
				$postsOD = $_POST['displayed_posts'] + 5;
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
			<option value="1">Upload Date DESC</option>
			<option value="2">Upload Date ASC</option>
			<option value="3">Title DESC</option>
			<option value="4">Title ASC</option>
			<option value="5">User DESC</option>
			<option value="6">User ASC</option>
		</select> 
	</div>

	<div id="the_feed">
		<?php $x = 0; for ($i = 0; $i < $postsOD; $i++):?>
			<a href="/Camagru/inc/post.php?id=<?php echo $posts[$i]->post_id?>">
				<div class="w3-card w3-margin w3-padding w3-border w3-border-red">
					<p class="w3-text-red"><?php echo $posts[$i]->title?></p>
					<img class="w3-padding" src="<?php echo $posts[$i]->image?>" style="max-width: 100%" alt="">
					<p class="w3-text-white"><?php echo $posts[$i]->body?></p>
					<img src="<?php echo $posts[$i]->display_picture?>" width="20px" class="w3-circle">
					<small class="w3-text-white"><?php echo $posts[$i]->user_name?>, on <?php echo $posts[$i]->published_at?></small>
				</div>
			</a>
		<?php $x++; endfor;?>
		<input type="hidden" id="post_count" value="<?php echo $x?>">
		<br class="w3-hide-medium w3-hide-small hideme">
		<br class="w3-hide-medium w3-hide-small hideme">
		<br class="w3-hide-medium w3-hide-small hideme">
	</div>

</div>
<script src="js/paginator.js"></script>
<?php require('inc/footer.inc.php')?>