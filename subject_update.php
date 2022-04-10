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
    if ($_POST && isset($_POST['SubjectID']) && isset($_POST['Subject'])) {

	    	//Sanitize Input
	    	$SubjectID 	= filter_input(INPUT_POST, 'SubjectID', FILTER_SANITIZE_NUMBER_INT);
	    	$Subject 	= filter_input(INPUT_POST, 'Subject', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

	    	//Validate Input
	    	$query = "SELECT SubjectID, Subject FROM subjects";

	    	$statement = $db->prepare($query);

	    	$statement->execute();

	    	while ($row = $statement->fetch()) {
	    		if ($row['Subject'] == $Subject && $row['SubjectID'] != $SubjectID){
	    			$error = " Subject already exists.";
	    		}
	    	}

	    	//Persist Input if no errors present
	    	if ($error == null) {
	    		$query = "UPDATE subjects SET Subject = :Subject WHERE SubjectID = :SubjectID";

	    		$statement = $db->prepare($query);

	    		$statement->bindValue(':SubjectID',	$SubjectID, PDO::PARAM_INT);
	    		$statement->bindValue(':Subject',	$Subject);

	    		$statement->execute();

	    		$_SESSION['form_success'] = true;
	    		
	    		header("location: subject_controls.php");
	    	}
    	}
    	echo $error;
 ?>