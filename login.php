<?php 
// Start session
session_start();

include_once('includes/header.php');


// Check if user is already logged in 
if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true){
	header("Location: welcome.php");
	exit();
}

include_once('includes/dbconnect.php');

$user = $pass ='';
$errors = []; 

if($_SERVER['REQUEST_METHOD'] === "POST" ){
		
	$user = trim(htmlspecialchars(stripslashes($_POST['username'])));
	$pass = trim(htmlspecialchars(stripslashes($_POST['password'])));


	if(empty($user)){
		$errors['username'] = "Username is required";
	}else{
		$username = $user;
	}

	if(empty($pass)){
		$errors['password'] = "Password is required";
	}else{
		$password = $pass;
	}

	if(empty($errors)){
		// prepare statement
		$sql = "SELECT id, username, password FROM members WHERE username =?";
		if($stmt = mysqli_prepare($link, $sql)){

			//Bind variables to the prepared statement as parameters
			mysqli_stmt_bind_param($stmt, 's' ,$param_username);

			// Set parameters
			$param_username = $username;

			// Attempt to execute prepared statement
			if(mysqli_stmt_execute($stmt)){
				// Store results
				mysqli_stmt_store_result($stmt);
				// check if username exists, if yes verify password
				if(mysqli_stmt_num_rows($stmt) === 1){
	
				    // bind result
					mysqli_stmt_bind_result($stmt , $id, $username, $hashed_password);
					if(mysqli_stmt_fetch($stmt)){
						if(password_verify($password, $hashed_password) == 1){
							// password is correct start a new session
							$_SESSION['loggedin'] = true;
							$_SESSION['id'] = $id;
							$_SESSION['username'] = $username;

							// redirect user to welcome page 
							header("Location: welcome.php");
							exit();

							var_dump($_POST);
							exit();
						}else{
							// Password not valid

							$errors['password'] = "password entered not valid";

						}
					}
				}else{
					$errors['username'] = "Enter Correct username";

				}
			}
			// close stmt
			mysqli_stmt_close($stmt);

		}
		
	}
	// close Connection
	mysqli_close($link);
}

?>

 <section>
  <div class="container">
    <div class="row">
      <div class="col-md-6 offset-md-3">
          
    <form class="bg-light py-4 px-5 reg my-1" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
      <h2>Login</h2>
      <div class="form-group">
        <label for="inputEmail">Username</label>
        <input type="text" class="form-control <?php echo (isset($errors['username']))? 'is-invalid': ''; ?>" id="inputEmail" placeholder="Username" name="username">
        <div class="invalid-feedback">
        	<?php echo (isset($errors['username']))? $errors['username']: ''; ?>
        </div>
      </div>
      <div class="form-group">
          <label for="inputPassword">Password</label>
          <input type="password" class="form-control <?php echo(isset($errors['password']))? 'is-invalid':''; ?>" id="inputPassword" placeholder="Password" name="password">
          <div class="invalid-feedback">
          	<?php echo (isset($errors['password'])) ? $errors['password']: ''; ?>
          </div>

      </div>

      <button type="submit" class="btn btn-info">Login</button>
    </form>
      </div>
    </div>
  </div>
</section>

<?php 	

	include_once('includes/footer.php');

 ?>