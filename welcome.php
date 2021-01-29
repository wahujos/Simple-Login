<?php 
// initialize session
session_start();
include_once('includes/dbconnect.php');


// check if user is logged in if not redirect to login page
if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true){
	header("Location: login.php");
	exit();
}

$sql = "SELECT name FROM members WHERE username = ?";

if($stmt = mysqli_prepare($link, $sql)){
	// bind variables to statement as parameters

	mysqli_stmt_bind_param($stmt, 's' ,$param_user);

	// set paramters
	$param_user = htmlspecialchars(stripslashes(trim($_SESSION['username'])));

	if(mysqli_stmt_execute($stmt)){

		if(mysqli_stmt_bind_result($stmt ,$name)){

			if(mysqli_stmt_fetch($stmt)){
				$member_name = ucwords($name);
			}
		}
	}
} 

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link rel="stylesheet" href="bootstrap-4.5.2-dist\css\bootstrap.min.css"> -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <title>Document</title>
</head>
<body>

  <nav class="navbar navbar-expand-md navbar-light bg-light fixed-top">
    <div class="container">
      <a href="#" class="navbar-brand justify-content-center" style="color: #fff;">Tech Ninjas</a>
      <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
          <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarCollapse">
          <div class="navbar-nav ml-auto nav-pills">
              <a href="reset-password.php" class="nav-item nav-link" style="color: #fff;">Reset Password</a>
              <a href="logout.php" class="nav-item nav-link" style="color: #fff;">Logout</a>
              
          </div>
      </div>
    </div>
</nav>

<div class="section">
	<div class="jumbotron reg jumbotron-fluid">
		<div class="container">

			<h1 class="display-3 text-align-center text-white text-center font-italic">Welcome to Tech Ninjas</h1>
			<h3 class="text-center text-white">Hello, <?php echo htmlspecialchars(stripslashes($member_name)); ?>.</h3> 
			<p class="lead text-white text-center">Its nice to have you around...</p>
			
		</div>
	</div>
</div>

 <?php 

	include_once("includes/footer.php");

?>