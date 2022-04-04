<?php 
/******************************************************************
 * Author:  Charles Burns
 * Date:    Mar 24, 2022
 * Purpose: Allows users to update and delete posts
 ******************************************************************/
	session_start();

    require ('db.php');

    $error = null;

    // Prevents users from simply typing url into address bar
    if (!isset($_SESSION['role'])) {
        header("location: index.php");
    } elseif($_SESSION['role'] == $VALUES_user_id || $_SESSION['role'] == 0) { // 8 is the roleID for the default user role. 0 is the roleID for non logged in users.
        header("location: index.php");
    }
    // Updates the $error variable with any errors from the submited form
    if ($_POST && isset($_POST['error'])) {
        $error = filter_input(INPUT_POST, 'error', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    }

    // UPDATE quote if PostTitle, PostContent and PostID are present in POST.
    if ($_POST && isset($_POST['PostTitle']) && isset($_POST['PostContent']) && isset($_POST['PostID']) && isset($_POST['update_button'])) {
        // Sanitize user input to escape HTML entities and filter out dangerous characters.
        $PostTitle   = filter_input(INPUT_POST, 'PostTitle', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $PostContent = filter_input(INPUT_POST, 'PostContent', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $PostID      = filter_input(INPUT_POST, 'PostID', FILTER_SANITIZE_NUMBER_INT);
        
        if ($PostID == "") {
            // If PostID is not a valid int return to homepage
            header("Location: index.php");
            exit;
        }

        // Build the parameterized SQL query and bind to the above sanitized values.
        $query     = "UPDATE Posts SET PostTitle = :PostTitle, PostContent = :PostContent WHERE PostID = :PostID";
        $statement = $db->prepare($query);
        $statement->bindValue(':PostTitle', $PostTitle);
        $statement->bindValue(':PostContent', $PostContent);
        $statement->bindValue(':PostID', $PostID, PDO::PARAM_INT);
        
        // Check if any values are beyond thier maximums.
        // If there are any find the problems to provide a detailed error message.
        if (strlen($PostTitle) > $VALUES_max_title_length || strlen($PostContent) > $VALUES_max_content_length || trim(strlen($PostTitle)) < 1 || trim(strlen($PostContent)) < 1) {
            // Check if either $PostTitle or $PostContent are within their limits
            if (strlen($PostTitle) > $VALUES_max_title_length) {
                $error = "The PostTitle of your post was " . strlen($PostTitle) - $VALUES_max_title_length . " characters too long";
            } elseif (strlen($PostTitle) < 1) {
                $error = "Your PostTitle cannot be empty";
            }
            // If the PostTitle was not too long $error will be null.
            if ($error == null && strlen($PostContent) > $VALUES_max_content_length) {
                $error = "Your post was " . strlen($PostContent) - $VALUES_max_content_length . " characters too long";
            } elseif ($error == null && strlen($PostContent) < 1) {
                $error = "Your PostContent cannot be empty";
                
            } elseif (strlen($PostContent) > $VALUES_max_content_length) {
                $error = $error . " and your post was " . strlen($PostContent) - $VALUES_max_content_length . " characters too long";
            } elseif (strlen($PostContent) < 1) {
                $error = $error . " and your PostContent cannot be empty";
            }
            $error = $error . ".";
        } elseif ($statement->execute()) {

        } else {
            $error = "Unhandled Error!";
        }
        
        // Redirect after update.
        header("Location: index.php");
        exit;

    // Check roles again to prevent injection from someone with edit privileges but not delete
    } else if ($_POST && isset($_POST['PostID']) && isset($_POST['delete_button']) && ($_SESSION['role'] == $VALUES_administrator_id || $_SESSION['role'] == $VALUES_moderator_id || $_SESSION['id'] == $quote['UserID'])) {
        
        // Sanitize user input to escape HTML entities and filter out dangerous characters.
        $PostID = filter_input(INPUT_POST, 'PostID', FILTER_SANITIZE_NUMBER_INT);

        if ($PostID > 0 && $PostID != "") {
            // Build the parameterized SQL query and bind to the above sanitized values.
            $query     = "DELETE FROM Posts WHERE PostID = :PostID";
            $statement = $db->prepare($query);
            $statement->bindValue(':PostID', $PostID, PDO::PARAM_INT);

            $statement->execute();
        }
        // Return on both good and bad PostID's
        header("Location: index.php");
        exit;
        
    } else if (isset($_GET['PostID'])) { // Retrieve post to be edited, if PostID GET parameter is in URL.
        // Sanitize the PostID. Like above but this time from INPUT_GET.
        $PostID = filter_input(INPUT_GET, 'PostID', FILTER_SANITIZE_NUMBER_INT);

        if ($PostID > 0 && $PostID != "") {
            // Build the parametrized SQL query using the filtered PostID.
            $query     = "SELECT * FROM Posts WHERE PostID = :PostID LIMIT 1";
            $statement = $db->prepare($query);
            $statement->bindValue(':PostID', $PostID, PDO::PARAM_INT);
            
            // Execute the SELECT and fetch the single row returned.
            $statement->execute();
            $quote = $statement->fetch();
        } else {
            // If the user enters an invalid PostID return to homepage
            header("Location: index.php");
            exit;
        }
            
    } else {
        $error = "Unhandled Error";
    }
 ?>

 <!DOCTYPE html>
 <html lang="en">
 <head>
 	<meta charset="utf-8">
 	<meta name="viewport" PostContent="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="style.css">
 	<PostTitle>Update <?php if(!isset($error)): ?><?= $quote['PostTitle'] ?><?php endif ?></PostTitle>
 </head>
 <body>
    <?php include("nav.php"); ?>
    <?php if (isset($error)): ?> <!-- Don't show form if there are errors -->
        <h1><?= $error ?></h1>
    <?php else: ?>
        <div class="form">
            <h1>Enter you next Posts post!</h1>
            <form action="update_delete.php?PostID=<?= $_GET['PostID'] ?>" method="post">
                <label for="PostTitle">Title:</label><br>
                <input type="text" PostID="PostTitle" name="PostTitle" value="<?= $quote['PostTitle'] ?>" autofocus><br>
                <label for="PostContent">Content:</label><br>
                <textarea PostID="PostContent" name="PostContent" rows="5" cols="50"><?= $quote['PostContent'] ?></textarea><br>
                <input type="hidden" id="PostID" name="PostID" value="<?= $quote['PostID'] ?>">
                <input type="hidden" id="error" name="error" value="<?= $error ?>">
                <input type="submit" name="update_button" value="Submit">
                <?php if($_SESSION['role'] == $VALUES_administrator_id || $_SESSION['role'] == $VALUES_moderator_id || $_SESSION['id'] == $quote['UserID']): ?>
                    <input type="submit" name="delete_button" value="Delete">
                <?php endif ?>
            </form>
        </div>
    <?php endif ?>
 </body>
 </html>