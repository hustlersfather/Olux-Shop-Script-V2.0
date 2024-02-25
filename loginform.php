<?php
ob_start();
session_start();
include "includes/config.php";
date_default_timezone_set('UTC');

if(isset($_SESSION['sname']) && isset($_SESSION['spass'])){
    header("location: index.html");
    exit();
}

if (isset($_POST['user'], $_POST['pass'])) {
    $username = mysqli_real_escape_string($dbcon, strip_tags($_POST['user']));
    $password = mysqli_real_escape_string($dbcon, strip_tags($_POST['pass']));
    $lvisi = date('Y-m-d');

    $finder = mysqli_query($dbcon, "SELECT * FROM users WHERE username='".strtolower($username)."'") or die("mysqli error");

    if(mysqli_num_rows($finder) != 0){
        $row = mysqli_fetch_assoc($finder);
        if(strtolower($username) == strtolower($row['username']) && $password == $row['password']){
            $_SESSION['sname'] = $username;
            $_SESSION['spass'] = $password;
            header('location:index.html');
            exit();
        } else {
            header('location:login.html?error=true');
            exit();
        }
    } else {
        header('location:login.html?error=true');
        exit();
    }
} else {
    header('location:index.html');
    exit();
}
?>