<?php
$desired_password = 'password123';
echo password_hash($desired_password, PASSWORD_DEFAULT);
