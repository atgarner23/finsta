<?php
require('CONFIG.php');
require_once('includes/functions.php');


//which post are we trying to show? URL will look like single.php?post_id=X
$post_id = filter_var($_GET['post_id'], FILTER_SANITIZE_NUMBER_INT);
//VALIDATE - MAKE SURE WE GOT A POSITIVE INTEGER
if ($post_id < 0) {
    $post_id = 0;
}


require('includes/parse-comment.php');
require('includes/header.php');




?>

<main class="content">
    <?php //get the one requested post
    $result = $DB->prepare('SELECT posts.*, categories.*, users.username, users.profile_pic, users.user_id
							FROM posts, categories,users
							WHERE posts.category_id = categories.category_id
							AND posts.user_id = users.user_id
							AND posts.is_published = 1
                            AND posts.post_id = ?
							LIMIT 1');
    $result->execute(array($post_id));
    //check if any rows were found
    if ($result->rowCount() >= 1) {
        while ($row = $result->fetch()) {
            //make variables from the array keys
            extract($row);
    ?>
            <div class="post">
                <img src="<?php echo $image ?>" alt="<?php echo $title ?>">

                <span class="author">
                    <img src="<?php echo $profile_pic; ?>" alt="<?php echo $username; ?>" width="50" height="50">
                    <?php echo $username; ?>
                </span>

                <h2><?php echo $title ?></h2>
                <p><?php echo $body ?></p>
                <span class="category"><?php echo $name; ?></span>

                <span class="date"><?php echo time_ago($date); ?></span>
            </div>
    <?php
            include('includes/comments.php');
            //only show the comment form if this post has comments enabled
            if ($allow_comments) {
                include('includes/comment-form.php');
            } else {
                echo '<div class ="message">Comments Closed.</div>';
            } //close if allow_comments
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