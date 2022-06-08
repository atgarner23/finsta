<?php
//if the user is trying to log out
//URL will be login.php?action=logout
if (isset($_GET['action']) and $_GET['action'] == 'logout') {
    //remove the access token from the DB
    $user_id = 0;
    if (isset($logged_in_user['user_id'])) {
        $user_id = $logged_in_user['user_id'];
    }
    //empty the access token for this user
    $result = $DB->prepare('UPDATE users
                            SET access_token = NULL
                            WHERE user_id = ?
                            LIMIT 1');
    $result->execute(array($user_id));

    //expire all cookies
    setcookie('user_id', 0, time() - 9999); //repeat this for all cookies that you set up
    setcookie('access_token', 0, time() - 9999); //repeat this for all cookies that you set up

    //kill the session via https://www.php.net/manual/en/function.session-destroy.php
    // Unset all of the session variables.
    $_SESSION = array();

    // If it's desired to kill the session, also delete the session cookie.
    // Note: This will destroy the session, and not just the session data!
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
    }

    // Finally, destroy the session.
    session_destroy();
} //end logout logic
