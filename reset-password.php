<?php 
// initialize session start

session_start();
include_once('includes/header.php');
include_once('includes/dbconnect.php');

// Check if user is logged in if not redirect to the login page.
if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true){

	header("Location: login.php");
	exit();
}

	if($_SERVER['REQUEST_METHOD'] == 'POST'){

		function test_input($data){
			$data = trim($data);
			$data = htmlspecialchars($data);
			$data = stripslashes($data);
			return $data;
		}


		$password = test_input($_POST['password']);
		$confirm_password = test_input($_POST['confirm_password']);

		if(empty($password)){
			$errors['password'] ="Please enter a new password";
		}elseif(empty($confirm_password)){
			$errors['confirm_password'] = "please confirm your password";
		}elseif(strcmp($password, $confirm_password) === 1){
			$errors['confirm_password'] = "This field must match the new password field";
		}else{
			$new_password = $password;
			$confirm_new_password = $confirm_password;
		}

		

		if(empty($errors)){
			// prepare update statement
			$sql = "UPDATE members SET password = ? WHERE id = ?";
			if($stmt = mysqli_prepare($link, $sql)){
				// bind variable to the prepared statement as parameters.

				mysqli_stmt_bind_param($stmt, 'si', $param_password, $param_id);

				// Set parameters
				$param_password = password_hash($new_password, PASSWORD_DEFAULT);
				$param_id = $_SESSION['id'];

				// Attempt to execute the statement
				if(mysqli_stmt_execute($stmt)){
					// password was updated successfully direct to login.
					session_destroy();
					header("Location: login.php");
				}else{
					echo "Something went wrong";
				}

				// close stmt
				mysqli_stmt_close($stmt);
			}
		}

			// close link
	mysqli_close($link);


	}

 ?>


 <section>
  <div class="container">
    <div class="row">
      <div class="col-md-6 offset-md-3">
        <form class="bg-light py-4 px-5 reg my-1" action="<?php echo $_SERVER['PHP_SELF']; ?>" method ="POST">
          <h2>Reset Password</h2>
          <div class="form-group">
            <label for="new_password">New Password</label>
            <input type="password" class="form-control <?php echo(isset($errors['password']))? 'is-invalid':'';?>" id="new_password" placeholder="New password" name="password">
            <div class="invalid-feedback">
            	<?php echo $errors['password']; ?>
            </div>
          </div>
          <div class="form-group">
            <label for="Cnew_password">Confirm Password</label>
            <input type="password" class="form-control <?php echo(isset($errors['confirm_password']))? 'is-invalid':'';?>" id="Cnew_password" placeholder="Confirm password" name="confirm_password">
            <div class="invalid-feedback">
            	<?php echo $errors['confirm_password']; ?>
            </div>
          </div>
          <div class="form-group">
            <button type="submit" class="btn btn-info">Reset Password</button>
            <a href="login.php" class="btn btn-primary" >Cancel</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>

<?php 	

include_once('includes/footer.php');
 ?>