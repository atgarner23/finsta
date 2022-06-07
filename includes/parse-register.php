<?php
//pre-define vars
$errors = array();
$username = '';
$password = '';
$email = '';
$policy = '';

//process the registration if it was submitted
if (isset($_POST['did_register'])) {
    //sanitize everything
    $username = clean_string($_POST['username']);
    $password = clean_string($_POST['password']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    //if policy isn't checked, set it to 0
    if (!isset($_POST['policy']) or $_POST['policy'] != 1) {
        $policy = 0;
    } else {
        $policy = 1;
    }
    //validate
    $valid = true;
    //username too short or too long
    if (strlen($username) < USERNAME_MIN or strlen($username) > USERNAME_MAX) {
        $valid = false;
        $errors['username'] = 'Username must be between ' . USERNAME_MIN . ' and ' . USERNAME_MAX . ' characters';
    } else {
        //username already taken in the DB
        $result = $DB->prepare('SELECT username
                                FROM users
                                WHERE username = ?
                                LIMIT 1');
        $result->execute(array($username));
        //if one row found, the username is taken
        if ($result->rowCount()) {
            $valid = false;
            $errors['username'] = 'That username is already taken. Try another.';
        }
    } //end username checks

    //invalid email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $valid = false;
        $errors['email'] = 'Invalid Email';
    } else {
        //email already taken in the DB
        $result = $DB->prepare('SELECT email
                                FROM users
                                WHERE email = ?
                                LIMIT 1');
        $result->execute(array($email));
        //if one row found, the email is taken
        if ($result->rowCount()) {
            $valid = false;
            $errors['email'] = 'That email is already registered. Try logging in.';
        }
    } //end email checks

    //password too short
    if (strlen($password) < PASSWORD_MIN) {
        $valid = false;
        $errors['password'] = 'Your password must be at least ' . PASSWORD_MIN . 'characters long.';
    }
    //policy unchecked
    if (!$policy) {
        $valid = false;
        $errors['policy'] = 'You must agree to our Terms of Service to sign up.';
    }


    //if valid, add the user to the DB
    if ($valid) {
        $result = $DB->prepare('INSERT INTO users
                                (username, password, email, is_admin, join_date)
                                VALUES
                                (:username, :hashpass, :email, 0, NOW())');
        $hashed_pass = password_hash($password, PASSWORD_DEFAULT);
        $result->execute(array(
            'username' => $username,
            'hashpass' => $hashed_pass,
            'email' => $email
        ));
        //check the query
        if ($result->rowCount()) {
            $feedback = 'Success. Welcome to Finsta!';
            $feedback_class = 'success';
        } else {
            $feedback = 'Error creating account. Try again later.';
            $feedback_class = 'error';
        }
    } else {
        $feedback = 'There were problems with your registration. Fix the following:';
        $feedback_class = 'error';
    }
    //show feedback and redirect

}//end if did_register