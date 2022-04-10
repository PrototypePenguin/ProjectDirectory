<?php 
	session_start();

    require_once ('db.php');	//Contains database connection information
    require ('values.php');		//Contains constant values identified with VALUE_

    $error = null;

    // Prevents users from simply typing url into address bar
    if (!isset($_SESSION['role'])) {
        header("location: index.php");
    } elseif($_SESSION['role'] == $VALUES_user_id || $_SESSION['role'] == 0) { // 8 is the roleID for the default user role. 0 is the roleID for non logged in users.
        header("location: index.php");
    }

    //UPDATE if valid Post information is provided
    if ($_POST && isset($_POST['UserID']) && isset($_POST['Email']) && isset($_POST['Address']) && isset($_POST['UserName'])) {

	    	//Sanitize Input
	    	$UserID 	= filter_input(INPUT_POST, 'UserID', FILTER_SANITIZE_NUMBER_INT);
	    	$UserName 	= filter_input(INPUT_POST, 'UserName', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	    	$Email		= filter_input(INPUT_POST, 'Email', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	    	$Address	= filter_input(INPUT_POST, 'Address', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	    	$Role		= filter_input(INPUT_POST, 'Role', FILTER_SANITIZE_NUMBER_INT);

	    	//Validate Input
	    	$query = "SELECT UserID, UserName, Email FROM users";

	    	$statement = $db->prepare($query);

	    	$statement->execute();

	    	while ($row = $statement->fetch()) {
	    		if ($row['Email'] == $Email && $row['UserID'] != $UserID){
	    			$error = " Email in use by another account.";
	    		}
	    	}

	    	$query = "SELECT RoleID FROM roles";

	    	$statement = $db->prepare($query);

	    	$statement->execute();

	    	//Count checks if the role appears in the database a value of 1 indicates it found the role successfully
	    	$count = 0;
	    	while($row = $statement->fetch()){
	    		if ($row['RoleID'] == $Role) {
	    			$count++;
	    		}
	    	}
	    	
	    	if ($count != 1) {
	    		$error = $error . " Invalid Role selected.";
	    	}

	    	//Persist Input if no errors present
	    	if ($error == null) {
	    		$query = "UPDATE users SET UserName = :UserName, Email = :Email, Address = :Address, RoleID = :RoleID WHERE UserID = :UserID";

	    		$statement = $db->prepare($query);

	    		$statement->bindValue(':UserID',	$UserID, PDO::PARAM_INT);
	    		$statement->bindValue(':UserName',	$UserName);
	    		$statement->bindValue(':Email',		$Email);
	    		$statement->bindValue(':Address',	$Address);
	    		$statement->bindValue(':RoleID',	$Role);

	    		$statement->execute();

	    		$_SESSION['form_success'] = true;
	    		header("location: user_controls.php");
	    	}
    	}
 ?>