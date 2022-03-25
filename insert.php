<?php 
/******************************************************************
 * Author:  Charles Burns
 * Date:    Jan 31, 2022
 * Accepts $_POST information for the purpose of adding a new entry
 * to the database if it passes validation
 ******************************************************************/
	require ('authenticate.php');
	require ('connect.php');

	$error = null;

	if ($_POST && !empty($_POST['title']) && !empty($_POST['content'])) {
        //  Sanitize user input to escape HTML entities and filter out dangerous characters.
        $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        
        //  Build the parameterized SQL query and bind to the above sanitized values.
        $query = "INSERT INTO blog (title, content) VALUES (:title, :content)";
        $statement = $db->prepare($query); // caches the statement on the server side
        
        //  Bind values to the parameters
        $statement->bindValue(":title", $title);
        $statement->bindValue(":content", $content);

        /*************************************************************************
         * Execute the INSERT.
         * execute() will check for possible SQL injection and remove if necessary
         * checks that the title and content portions fit in their varchar sizes
         * if the user enters chars that will be sanitized an empty string can
         * make it this far so it must be check that strings have content
         *************************************************************************/
                
        if (strlen($title) > 140 || strlen($content) > 1000 || trim(strlen($title)) < 1 || trim(strlen($content)) < 1) {
        	// Check if either $title or $content are within their limits
        	if (strlen($title) > 140) {
        		$error = "The title of your post was " . strlen($title)-140 . " characters too long";
        	} elseif (strlen($title) < 1) {
                $error = "Your title cannot be empty";
            }
        	// If the title was not too long $error will be null.
        	if ($error == null && strlen($content) > 1000) {
        		$error = "Your post was " . strlen($content)-1000 . " characters too long";
        	} elseif ($error == null && strlen($content) < 1) {
                $error = "Your content cannot be empty";
            } elseif (strlen($content) > 1000) {
        		$error = $error . " and your post was " . strlen($content)-1000 . " characters too long";
        	} elseif (strlen($content) < 1) {
                $error = $error . " and your content cannot be empty";
            }
        	$error = $error . ".";
        } elseif ($statement->execute()) {
        	// If $statement can execute go to the homepage
        	header('Location: home.php');
        	exit;
        } else {
        	$error = "Unhandled Error!";
        }

    } else {
    	$error = "Your post must have content";
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
            <a href="home.php">Go back to the homepage.</a>
        <?php endif ?>
    </div>
 </body>
 </html>