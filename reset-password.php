<?php 
	// Initialize the session
	session_start();

	// Check if the user is logged in, otherwise redirect to login page
	if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
		header("location: login.php");
		exit;
	}

	// Include config file
	require_once ('db.php');	//Contains database connection information
    require ('values.php');		//Contains constant values identified with VALUE_

	// Define variables and initialize with empty values
	$new_password = $confirm_password = "";
	$new_password_err = $confirm_password_err = $login_err = "";

	// Processing form data when form is submitted
	if ($_SERVER['REQUEST_METHOD'] == "POST") {
		
		// Validate new password
		if (empty(trim($_POST['new_password']))) {
			$new_password_err = "Please enter the new password.";
		} elseif (strlen(trim($_POST['new_password'])) < 6) {
			$new_password_err = "Password must have at least 6 characters.";
		} else {
			$new_password = trim($_POST['new_password']);
		}

		// Validate confirm password
		if (empty(trim($_POST['confirm_password']))) {
			$confirm_password_err = "Please confirm password.";
		} else {
			$confirm_password = trim($_POST['confirm_password']);
			if (empty($new_password_err) && ($new_password != $confirm_password)) {
				$confirm_password_err = "Password did not match.";
			}
		}

		// Check input errors before updating the database
		if (empty($new_password_err) && empty($confirm_password_err)) {
			//Prepare an update statement
			$query = "UPDATE users SET Password = :Password WHERE UserID = :UserID";

			if($statement = $db->prepare($query)) {
				// Set parameters
				$param_password = password_hash(($new_password), PASSWORD_DEFAULT); // Salts and hashes password as per php 8.0
				$param_id = $_SESSION['id'];

				// Bind variables to the prepared statement as parameters
				$statement->BindParam(':UserID', $param_id, PDO::PARAM_INT);
				$statement->BindValue(':Password', $param_password);

				// Attempt to execute the prepared statement
				if($statement->execute()) {
					// Password updated successfully. Destroy the session, and redirect to login page
					session_destroy();
					header("location: login.php");
					exit();
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
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; }
        .wrapper{ width: 360px; padding: 20px; }
    </style>
</head>
<body>
	<?php include("nav.php"); ?>
    <div class="wrapper">
        <h2>Reset Password</h2>
        <p>Please fill out this form to reset your password.</p>
        <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post"> 
            <div class="form-group">
                <label>New Password</label>
                <input type="password" name="new_password" class="form-control <?= (!empty($new_password_err)) ? 'is-invalid' : '' ?>" value="<?= $new_password ?>">
                <span class="invalid-feedback"><?= $new_password_err ?></span>
            </div>
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control <?= (!empty($confirm_password_err)) ? 'is-invalid' : '' ?>">
                <span class="invalid-feedback"><?= $confirm_password_err ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <a class="btn btn-link ml-2" href="welcome.php">Cancel</a>
            </div>
        </form>
    </div>    
</body>
</html>