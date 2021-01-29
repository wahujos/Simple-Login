<?php
include('includes/header.php');
include_once('includes/dbconnect.php');
$errors = [];
$name = $email = $user = $pass = $confirmpass ='';



if($_SERVER['REQUEST_METHOD'] === 'POST'){
  
  // echo var_dump($_POST);
  $name = trim(htmlspecialchars(stripslashes($_POST['name'])));
  $email = trim(htmlspecialchars(stripslashes($_POST['email'])));
  $user = trim(htmlspecialchars(stripslashes($_POST['username'])));
  $pass = trim(htmlspecialchars(stripslashes($_POST['password'])));
  $confirmpass = trim(htmlspecialchars(stripslashes($_POST['confirm_password'])));

    
  // Validating name
  // check for success first approach
  if(!empty($name)){
    if(!ctype_digit($name)){
        $valid_name = $name;
    }else{
      $errors['name'] = "Name field cannot contain numbers"; 
    }
  }else{
    $errors['name'] = "Name field cannot be empty";
  }

  // Validating Email
  // check for errors first approach
  if(empty($email)){
    $errors['email'] = "Email field is required";
  }elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
    $errors['email'] = "Input a correct email address";
  }else{
    $valid_email = $email;
  }

  // Validating Username
  // Errors first approach
  if(empty($user)){
    $errors['username'] = "Username input is required";
  }
  else{
    // Prepare sql statement
    $sql = "SELECT id FROM members WHERE username = ?";
    if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement

        mysqli_stmt_bind_param($stmt, 's', $param_user);

        // Set parameters
        $param_user = $user;

        // Attempt to execute the prepared statement

        if(mysqli_stmt_execute($stmt)){
          mysqli_stmt_store_result($stmt);

          if(mysqli_stmt_num_rows($stmt) == 1){
            $errors['username'] = "This username is already taken";
          }else{
            $valid_user = $user;
          }

          }else{
            echo "Something Went Wrong";
          }
          // Close Statement
          mysqli_stmt_close($stmt);

      }


  }


// Validating both passwords
if(empty($pass)){
  $errors['password'] = "Password field is required";
}elseif(empty($confirmpass)){
  $errors['confirm_password'] = "Confirm password field is required";
}elseif(strcmp($pass, $confirmpass) !== 0){
  $errors['confirm_password'] = "This field must match the password field";
}else{
  $valid_pass = $pass;
}


if(empty($errors)){
  // Prepare an insert statement
  $sql = "INSERT INTO members (name, email, username, password) VALUES (?,?,?,?)";
  if($stmt = mysqli_prepare($link, $sql)){
    // Bind variables to the prepared statement as parameters
    mysqli_stmt_bind_param($stmt, 'ssss', $param_name, $param_email, $param_username, $param_password);

    // Set Parameters
    $param_name = $valid_name;
    $param_email = $valid_email;
    $param_username = $valid_user;
    $param_password = password_hash($valid_pass, PASSWORD_DEFAULT);

    // Attempt to execute the prepared statement
    if(mysqli_stmt_execute($stmt)){
      // success register user redirect to login

      header("Location: login.php");

    }else{
      echo "Something went wrong";
    }

    // Close Stmt Statement
    mysqli_stmt_close($stmt);
    
  }
}

  // Close Connection
  mysqli_close($link);
}

?>


<section>
  <div class="container">
    
  <div class="row">
    <div class="col-md-6 offset-md-3">
      
    <form class="bg-light py-4 px-5 reg my-1" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
      <h2>Register</h2>
      <div class="form-group">
        <label for="fullname">Name</label>
        <input type="text" class="form-control <?php echo (isset($errors['name'])) ? 'is-invalid': ''; ?>" id="fullname" placeholder="Name" name="name">
        <div class="invalid-feedback">
          <?php echo (isset($errors['name'])) ? $errors['name'] :"" ; ?>
        </div>
      </div>
      <div class="form-group">
          <label for="mail">Email</label>
          <input type="email" class="form-control <?php echo (isset($errors['email']))? 'is-invalid':''; ?>" id="mail" placeholder="Email" name="email">
          <div class="invalid-feedback">
          <?php echo (isset($errors['email'])) ? $errors['email'] :"" ; ?>
        </div>
      </div>
      <div class="form-group">
        <label for="user">Username</label>
        <input type="text" class="form-control <?php echo (isset($errors['username'])) ? 'is-invalid': ''; ?>" id="user" placeholder="Username" name="username">
        <div class="invalid-feedback">
          <?php echo (isset($errors['username'])) ? $errors['username'] :"" ; ?>
        </div>
      </div>
      <div class="form-group">
          <label for="Password">Password</label>
          <input type="password" class="form-control <?php echo (isset($errors['password'])) ? 'is-invalid': ''; ?>" id="Password" placeholder="Password" name="password">
          <div class="invalid-feedback">
          <?php echo (isset($errors['password'])) ? $errors['password'] :"" ; ?>
          </div>
      </div>
      <div class="form-group">
        <label for="confirmPassword">Confirm Password</label>
        <input type="password" class="form-control <?php echo (isset($errors['confirm_password'])) ? 'is-invalid': ''; ?>" id="confirmPassword" placeholder="Confirm Password" name="confirm_password">
        <div class="invalid-feedback">
          <?php echo (isset($errors['confirm_password'])) ? $errors['confirm_password'] :"" ; ?>
        </div>
      </div>

      <button type="submit" class="btn btn-info">Sign in</button>
    </form>
  </div>
  </div>
</div>
</section>



<?php 

include_once('includes/footer.php');

?>

