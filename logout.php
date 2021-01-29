<?php 

// initialize session
session_start();

// unset all available sessions
$_SESSION = [];

// destroy session

session_destroy();

// redirect user to login page 
header('Location: login.php');
exit();

 ?>