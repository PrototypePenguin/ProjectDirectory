<?php 
/******************************************************************
 * Author:  Charles Burns
 * Date:    Mar 24, 2022
 * Accepts $_POST information for the purpose of adding a new entry
 * to the database if it passes validation
 * 
 * TODO: prevent unauthorized posts from reaching php code.
 ******************************************************************/
	session_start();
    require_once ('db.php');    //Contains database connection information
    require ('values.php');     //Contains constant values identified with VALUE_

	$error = null;

	if ($_POST && !empty($_POST['PostTitle']) && !empty($_POST['PostContent']) && !empty($_POST['UserID'])) {
        //  Sanitize user input to escape HTML entities and filter out dangerous characters.
        $PostTitle = filter_input(INPUT_POST, 'PostTitle', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $PostContent = filter_input(INPUT_POST, 'PostContent', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $UserID = filter_input(INPUT_POST, 'UserID', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        
        //  Build the parameterized SQL query and bind to the above sanitized values.
        $query = "INSERT INTO Posts (PostTitle, PostContent, UserID) VALUES (:PostTitle, :PostContent, :UserID)";
        $statement = $db->prepare($query); // caches the statement on the server side
        
        //  Bind values to the parameters
        $statement->bindValue(":PostTitle", $PostTitle);
        $statement->bindValue(":PostContent", $PostContent);
        $statement->bindValue(":UserID", $UserID);

        /*************************************************************************
         * Execute the INSERT.
         * execute() will check for possible SQL injection and remove if necessary
         * checks that the PostTitle and PostContent portions fit in their varchar sizes
         * if the user enters chars that will be sanitized an empty string can
         * make it this far so it must be check that strings have PostContent
         *************************************************************************/

        if (strlen($PostTitle) > $VALUES_max_title_length || strlen($PostContent) > $VALUES_max_content_length || trim(strlen($PostTitle)) < 1 || trim(strlen($PostContent)) < 1) {
        	// Check if either $PostTitle or $PostContent are within their limits
        	if (strlen($PostTitle) > $VALUES_max_title_length) {
        		$error = "The PostTitle of your post was " . strlen($PostTitle)-$VALUES_max_title_length . " characters too long";
        	} elseif (strlen($PostTitle) < 1) {
                $error = "Your PostTitle cannot be empty";
            }
        	// If the PostTitle was not too long $error will be null.
        	if ($error == null && strlen($PostContent) > $VALUES_max_content_length) {
        		$error = "Your post was " . strlen($PostContent)-$VALUES_max_content_length . " characters too long";
        	} elseif ($error == null && strlen($PostContent) < 1) {
                $error = "Your PostContent cannot be empty";
            } elseif (strlen($PostContent) > $VALUES_max_content_length) {
        		$error = $error . " and your post was " . strlen($PostContent)-$VALUES_max_content_length . " characters too long";
        	} elseif (strlen($PostContent) < 1) {
                $error = $error . " and your PostContent cannot be empty";
            }
        	$error = $error . ".";
        } elseif ($statement->execute()) {
        	// If $statement can execute go to the homepage
        	header('Location: index.php');
        	exit;
        } else {
        	$error = "Unhandled Error!";
        }

    } else {
    	$error = "Your post must have PostContent";
    }
 ?>

 <!DOCTYPE html>
 <html lang="en">
 <head>
 	<meta charset="utf-8">
 	<meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="style.css">
 	<title>Insert</title>
 </head>
 <body>
 	<div>
        <?php if (isset($error)): ?> <!-- If the user entered invalid data show them the error -->
            <h1><?= $error ?></h1>
            <a href="index.php">Go back to the homepage.</a>
        <?php endif ?>
    </div>
 </body>
 </html>