<?php
require('CONFIG.php');
require_once('includes/functions.php');
require('includes/header.php');

?>

<main class="content">
	<?php //get up to 20 published posts, newest first
	$result = $DB->prepare('SELECT posts.*, categories.*, users.username, users.profile_pic, users.user_id
							FROM posts, categories,users
							WHERE posts.category_id = categories.category_id
							AND posts.user_id = users.user_id
							AND posts.is_published = 1
							ORDER BY posts.date DESC
							LIMIT 20');
	$result->execute();
	//check if any rows were found
	if ($result->rowCount() >= 1) {
		while ($row = $result->fetch()) {
			//make variables from the array keys
			extract($row);
	?>
			<div class="post">
				<a href="single.php?post_id=<?php echo $post_id; ?>"><?php show_post_image($image, 'large', $title); ?></a>
				<?php edit_post_button($post_id, $user_id); ?>

				<span class="author">
					<a href="profile.php?user_id=<?php echo $user_id; ?>">
						<?php show_profile_pic($profile_pic, $username);
						echo $username; ?>
					</a>
				</span>

				<h2><?php echo $title ?></h2>
				<p><?php echo $body ?></p>
				<span class="category"><?php echo $name; ?></span>
				<span class="comment-count"><?php echo count_comments($post_id); ?></span>
				<span class="date"><?php echo time_ago($date); ?></span>
				<span class="likes"><?php like_interface($post_id, $logged_in_user['user_id']); ?></span>
			</div>
	<?php
		} //end while	
	} else {
		//no rows found from our query
		echo 'No posts found';
	} //end else 
	?>

</main>
<?php
require('includes/aside.php');
require('includes/footer.php');
?>