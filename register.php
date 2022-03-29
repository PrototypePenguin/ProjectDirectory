<?php 
	// Include config file
	require_once "db.php";

	// Define variables and initialize with empty values
	$username = $email = $password = $confirm_password = "";
	$username_err = $password_err = $confirm_password_err = $login_err = $email_err = "";

	// Processing form data when form is submitted
	if($_SERVER['REQUEST_METHOD'] == 'POST') {

		// Validate username
		if (empty(trim($_POST['username']))) {
			$username_err = "Please enter a username.";
		} elseif (!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST['username']))) {
			$username_err = "Username can only contain letters, numbers and underscores.";
		} else {
			// Prepare a select statement
			$query = "SELECT UserID FROM users WHERE UserName = :UserName";

			if ($statement = $db->prepare($query)) {
				// Bind variables to the prepared statement as parameters
				$statement->BindParam(':UserName', $param_username, PDO::PARAM_STR);

				$param_username = trim($_POST['username']);

				// Atempt to execute the prepared statement
				if ($statement->execute()) {
					
					if ($statement->rowCount() == 1) {
						$username_err = "This username is already taken.";
					} else {
						$username = trim($_POST['username']);
					}
				} else {
					$login_err = "Oops! Something went wrong. Please try again later.";
				}
			}
		}

		// Validate email
		if (empty(trim($_POST['email']))) {
			$email_err = "Please enter a email.";
		} else {
			// Prepare a select statement
			$query = "SELECT UserID FROM users WHERE Email = :Email";

			if ($statement = $db->prepare($query)) {
				// Bind variables to the prepared statement as parameters
				$statement->BindParam(':Email', $email, PDO::PARAM_STR);

				$email = trim($_POST['email']);

				// Atempt to execute the prepared statement
				if ($statement->execute()) {
					
					if ($statement->rowCount() == 1) {
						$email_err = "This email is already taken.";
					} else {
						$email = trim($_POST['email']);
					}
				} else {
					$login_err = "Oops! Something went wrong. Please try again later.";
				}
			}
		}

		// Validate Password
		if(empty(trim($_POST['password']))) {
			$password_err = "Please enter a password.";
		} elseif(strlen(trim($_POST["password"])) < 6) {
			$password_err = "Password must have at least 6 characters.";
		} else {
			$password = trim($_POST['password']);
		}

		// Validate confirm password
		if(empty(trim($_POST['confirm_password']))) {
			$confirm_password_err = "Please confirm password";
		} else {
			$confirm_password = trim($_POST['confirm_password']);
			if (empty($password_err) && ($password != $confirm_password)) {
				$confirm_password_err = "Password did not match.";
			}
		}

		// Check input errors before inserting in database
		if (empty($username_err) && empty($password_err) && empty($confirm_password_err)) {
			
			// Prepare an insert statement
			$query = "INSERT INTO users (Email, UserName, Password, RoleID) VALUES (:Email, :UserName, :Password, :RoleID)";

			if ($statement = $db->prepare($query)) {

				$param_username = $username;
				$param_password = password_hash($password, PASSWORD_DEFAULT); // Creates password hash
				$param_email	= $email;

				// Bind variables to the prepared statement as parameters
				$statement->BindValue(':Email',	   $param_email, PDO::PARAM_STR);
				$statement->BindValue(':UserName', $param_username, PDO::PARAM_STR);
				$statement->BindValue(':Password', $param_password, PDO::PARAM_STR);
				$statement->BindValue(':RoleID', 8, PDO::PARAM_INT);					// 8 is the lowest level of permission

				// Attempt to execute the prepared statement
				if($statement->execute()) {
					//Redirect to login page
					header("location: login.php");
				} else {
					$login_err = "Oops Something went wrong. Please try again later.";
				}
			}
		}
	}
 ?>

 <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; }
        .wrapper{ width: 360px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Sign Up</h2>
        <p>Please fill this form to create an account.</p>
        <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
			<div class="form-group">
				<label for="email">Email</label>
				<input type="text" name="email" class="form-control <?= (!empty($email_err)) ? 'is-invalid' : '' ?>" value="<?= $email ?>">
			</div>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" class="form-control <?= (!empty($username_err)) ? 'is-invalid' : '' ?>" value="<?= $username; ?>">
                <span class="invalid-feedback"><?= $username_err ?></span>
            </div>    
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" class="form-control <?= (!empty($password_err)) ? 'is-invalid' : '' ?>" value="<?= $password; ?>">
                <span class="invalid-feedback"><?= $password_err ?></span>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control <?= (!empty($confirm_password_err)) ? 'is-invalid' : '' ?>" value="<?= $confirm_password; ?>">
                <span class="invalid-feedback"><?= $confirm_password_err ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="reset" class="btn btn-secondary ml-2" value="Reset">
            </div>
            <p>Already have an account? <a href="login.php">Login here</a>.</p>
        </form>
    </div>    
</body>
</html>