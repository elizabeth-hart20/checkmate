<?php
include_once 'db_connect.php';
include_once 'functions.php';
include '../ChromePhp.php';

 
sec_session_start(); // Our custom secure way of starting a PHP session.
 
if (isset($_POST['email'], $_POST['p'])) {
    $email = $_POST['email'];
    $password = $_POST['p']; // The hashed password.
    
 
    if (login($email, $password, $mysqli) == true) {
        // Login success 
        $url = "Location: ../protected_page.php?email=".urlencode($email);
        header($url);
    } else {
        // Login failed 
        echo '<script>console.log("login failed, setting error")</script>';
        header('Location: ../index.php?error=1');
    }
} else {
    // The correct POST variables were not sent to this page. 
    echo 'Invalid Request';
}