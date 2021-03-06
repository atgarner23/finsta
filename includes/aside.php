<aside class="sidebar">
    <?php //get up to 20 published posts, newest first
    $result = $DB->prepare('SELECT profile_pic, username, user_id
								FROM users
								ORDER BY user_id DESC
								LIMIT 5');
    $result->execute();
    //check if any rows were found
    if ($result->rowCount() >= 1) {

    ?>
        <section class="users">
            <h2>Newest Users</h2>
            <ul>
                <?php while ($row = $result->fetch()) {
                    //make variables from the array keys
                    extract($row); ?>
                    <li class="user">
                        <a href="profile.php?user_id=<?php echo $user_id; ?>">
                            <?php show_profile_pic($profile_pic, $username);
                            echo $username; ?>
                        </a>
                    </li>
                <?php } ?>
            </ul>

        </section>
    <?php } ?>


    <?php //get up to 20 published posts, newest first
    $result = $DB->prepare('SELECT categories.*, COUNT(*) AS total
                            FROM posts, categories
                            WHERE posts.category_id = categories.category_id
                            GROUP BY posts.category_id
							ORDER BY RAND()
							LIMIT 9');
    $result->execute();
    //check if any rows were found
    if ($result->rowCount() >= 1) {

    ?>
        <section class="categories">


            <h2>Categories</h2>
            <ul>
                <?php while ($row = $result->fetch()) {
                    //make variables from the array keys
                    extract($row);
                    echo "<li> $name ($total)</li>";
                } ?>
            </ul>

        </section>
    <?php } ?>


    <?php //get up to 20 published posts, newest first
    $result = $DB->prepare('SELECT *
								FROM tags
								ORDER BY RAND()
								LIMIT 9');
    $result->execute();
    //check if any rows were found
    if ($result->rowCount() >= 1) {

    ?>
        <section class="tags">


            <h2>Tags</h2>
            <ul>
                <?php while ($row = $result->fetch()) {
                    //make variables from the array keys
                    extract($row); ?>
                    <li><?php echo $name ?></li>
                <?php } ?>
            </ul>

        </section>
    <?php } ?>

</aside>