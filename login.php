<?php 
	// Initialize the session
	session_start();

	// Check if the user is already logged in, if yes then redirect them to tthe welcome page
	if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true){
		header("location: welcome.php");
		exit;
	}

	// Include config file
	require_once ('db.php');	//Contains database connection information
    require ('values.php');		//Contains constant values identified with VALUE_

	// Define variables and initialize with empty values
	$username = $password = "";
	$username_err = $password_err = $login_err = "";

	// Processing form data when form is submitted
	if ($_SERVER['REQUEST_METHOD'] == "POST") {
		
		// Check if username is empty
		if (empty(trim($_POST['username']))) {
			$username_err = "Please enter username.";
		} else {
			$username = trim($_POST['username']);
		}

		// Check if password is empty
		if (empty(trim($_POST['password']))) {
			$password_err = "Please enter password.";
		} else {
			$password = trim($_POST['password']);
		}

		// Validate credentials
		if(empty($username_err) && empty($password_err)) {
			//Prepare a select statement
			$query = "SELECT UserID, UserName, RoleID, Password FROM users WHERE UserName = :UserName";

			if($statement = $db->prepare($query)) {
				// Bind variables to the prepared statement as parameters
				$statement->BindParam(":UserName", $username, PDO::PARAM_STR);

				// Attempt to execute the prepared statement
				if ($statement->execute()) {
					// Check if username exists, if yes then verify password
					if($statement->rowCount() == 1) {
						
						if ($quote = $statement->fetch()) {
							if (password_verify($password, $quote['Password'])) {
								// Password is correct, so start a new session
								session_start();

								// Store data in session variables
								$_SESSION['loggedin'] = true;
								$_SESSION['id'] = $quote['UserID'];
								$_SESSION['role'] = $quote['RoleID'];
								$_SESSION['username'] = $username;

								// Redirect user to welcome page
								header("location: index.php");
							} else {
								//Password is not valid, display a generic error message
								$login_err = "Invalid username or password. password";
							}
						}
					} else {
						// Username doesn't exist, display a generic error message
						$login_err = "Invalid username or password. username";
					}
				} else {
					$login_err = "Oops! Something went wrong. Please try again later.";
				}
			}
		}
	}
 ?>

 <!DOCTYPE html>
<html lang="en">
<head>
    <title>Login - The Watcher</title>
 	<meta charset="utf-8">
 	<meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="styles/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script> 
    <style>
        body{ font: 14px sans-serif; }
        .wrapper{ width: 360px; padding: 20px; }
    </style>
</head>
<body>
	<div class="container mb-3">
		<div class="row">
			<?php include("nav.php"); ?>
		</div>
		<div class="row">
			<div class="mb-3 mt-3">
		        <h2>Login</h2>
		        <p>Please fill in your credentials to login.</p>
		
		        <?php if(!empty($login_err)): ?>
		            <div class="alert alert-danger"><?= $login_err ?></div>;
		        <?php endif ?>
		
		        <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
		            <div class="mb-3">
		                <label>Username</label>
		                <input type="text" name="username" class="form-control <?= (!empty($username_err)) ? 'is-invalid' : '' ?>" value="<?= $username ?>">
		                <span class="invalid-feedback"><?= $username_err ?></span>
		            </div>
		            <div class="mb-3">
		                <label>Password</label>
		                <input type="password" name="password" class="form-control <?= (!empty($password_err)) ? 'is-invalid' : '' ?>">
		                <span class="invalid-feedback"><?= $password_err ?></span>
		            </div>
		            <div class="mb-3">
		                <input type="submit" class="btn btn-primary" value="Login">
		            </div>
		            <p>Don't have an account? <a href="register.php">Sign up now</a>.</p>
		        </form>
		    </div>
		</div>
	</div>
	
    
</body>
</html>