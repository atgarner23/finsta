<?php
require('CONFIG.php');
require_once('includes/functions.php');
require('includes/header.php');
//kill the page if not logged in
if (!$logged_in_user) {
    exit('This page is for logged in users only');
}
?>

<main class="content">
    <?php require('includes/parse-edit.php');
    ?>
    <h2>Edit Post</h2>
    <?php show_feedback($feedback, $feedback_class, $errors);
    ?>
    <form action="edit-post.php?post_id=<?php echo $post_id; ?>" method="post">
        <label for="">Title</label>
        <input type="text" name="title" id="title" value="<?php echo $title; ?>">

        <label for="body">Body</label>
        <textarea name="body" id="body" cols="30" rows="10"><?php echo $body; ?></textarea>

        <label for="category">Category</label>
        <?php category_dropdown($category_id); ?>

        <label for="">
            <input type="checkbox" name="allow_comments" id="allow_comments" value="1" <?php checked(1, $allow_comments); ?>>
            Allow Comments on this post.
        </label>

        <label for="">
            <input type="checkbox" name="is_published" id="is_published" value="1" <?php checked(1, $is_published); ?>>
            Make this post public.
        </label>

        <input type="submit" value="Save Post">
        <input type="hidden" name="did_edit" value="1">
    </form>
</main>
<?php
require('includes/aside.php');
require('includes/footer.php');
?>