<?php 
session_start();
    require_once ('db.php');    //Contains database connection information
    require ('values.php');     //Contains constant values identified with VALUE_

	$error = null;

	if ($_POST && !empty($_POST['new_subject'])) {
        //  Sanitize user input to escape HTML entities and filter out dangerous characters.
        $subject = filter_input(INPUT_POST, 'new_subject', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        
        //  Build the parameterized SQL query and bind to the above sanitized values.
        $query = "INSERT INTO subjects (Subject) VALUES (:Subject)";
        $statement = $db->prepare($query); // caches the statement on the server side
        
        //  Bind values to the parameters
        $statement->bindValue(":Subject", $subject, PDO::PARAM_STR);

        $statement->execute();

        $_SESSION['form_success'] = true;

        if (!empty($_COOKIE['Source'])) {
            header("location: ".$_COOKIE['Source']);
        } else {
            header("location: subject_controls.php");
        }
    }
 ?>