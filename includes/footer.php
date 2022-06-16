<footer class="footer"></footer>

</div>
<?php include('includes/debug-output.php') ?>

<?php if ($logged_in_user) { ?>
    <script>
        //like unlike
        document.body.addEventListener('click', (e) => {
            if (e.target.className == 'heart-button') {
                likeUnlike(e.target);
            }
        });

        async function likeUnlike(el) {
            let postId = el.dataset.postid;
            let userId = <?php echo $logged_in_user['user_id']; ?>;
            //get the parent container so we can update the interface later
            let container = el.closest('.likes');

            let formData = new FormData();
            //thing.append('name',value)
            formData.append('postId', postId);
            formData.append('userId', userId);

            let response = await fetch('fetch-handlers/like-unlike.php', {
                method: 'POST',
                body: formData
            });
            //feedback
            if (response.ok) {
                let result = await response.text();
                container.innerHTML = result;
            } else {
                console.log(response.status);
            }
        }
    </script>
<?php } //end if logged in 
?>
</body>

</html>