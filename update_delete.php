<?php 
/******************************************************************
 * Author:  Charles Burns
 * Date:    Mar 24, 2022
 * Purpose: Allows users to update and delete posts
 ******************************************************************/
	session_start();

    require_once ('db.php');    //Contains database connection information
    require ('values.php');     //Contains constant values identified with VALUE_

    $error = null;

    // Prevents users from simply typing url into address bar
    if (!isset($_SESSION['role'])) {
        header("location: index.php");
    } elseif($_SESSION['role'] == $VALUES_user_id || $_SESSION['role'] == 0) {
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
            $query     = "SELECT PostID, UserID, PostTitle, PostCategory, PostDesc, PostContent, PostTimestamp, posts.SubjectID AS SubjectID, subjects.Subject AS Subject FROM posts, subjects WHERE PostID = :PostID AND posts.SubjectID = subjects.SubjectID LIMIT 1";
            $statement = $db->prepare($query);
            $statement->bindValue(':PostID', $PostID, PDO::PARAM_INT);
            
            // Execute the SELECT and fetch the single row returned.
            $statement->execute();
            $quote = $statement->fetch();

            //Grab information for drop down lists
            $query = "SELECT SubjectID, Subject FROM subjects";

            $subject_list = $db->prepare($query);

            $subject_list->execute();
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
    <link rel="stylesheet" type="text/css" href="styles\styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
 </head>
 <body>
    <div class="container">
        <div class="row">
            <?php include("nav.php"); ?>
        </div>
        <div class="row">
            <?php if (isset($error)): ?> <!-- Don't show form if there are errors -->
                <h1><?= $error ?></h1>
            <?php else: ?>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <h1>Update: <?= $quote['PostTitle'] ?></h1>
                <a href="full_post.php?PostID=<?= $quote['PostID'] ?>"  class="btn btn-light">Go Back to Post</a>
            </div>
        </div>
        <div class="row">
            <form action="update_delete.php?PostID=<?= $_GET['PostID'] ?>" method="post">
                <div class="mb-3 mt-3">
                    <label for="PostTitle" class="form-label">Title:</label>
                    <input type="text" class="form-control" id="PostTitle" name="PostTitle" value="<?= $quote['PostTitle'] ?>" autofocus>
                </div>
                <div class="mb-3">
                    <label for="PostContent" class="form-label">Content:</label>
                    <textarea class="form-control" id="PostContent" name="PostContent" rows="5"><?= $quote['PostContent'] ?></textarea>
                </div>
                <div class="mb-3">
                    <label for="PostSubject" class="form-label">Subject:</label>
                    <input class="form-control" list="PostSubjectID" name="Subject" id="Subject">
                    <datalist id="PostSubjectID">
                        <?php while ($row = $subject_list->fetch()): ?>
                            <option value="<?= $row['SubjectID'] ?>"><?= $row['Subject'] ?></option>
                        <?php endwhile ?>
                    </datalist>
                </div>
                <div class="mb-3">
                    <label for="PostDescription" class="form-label">Snippet:</label>
                    <textarea class="form-control" id="PostDescription" name="PostDescription" rows="3"></textarea>
                </div>
                <div class="mb-3 mt-3">
                    <label for="image" class="form-label">Image:</label>
                    <input type="file" name="image" id="image" class="form-control">
                </div>
                <div class="mb-3 mt-3">
                    <select>
                        <?php  ?>
                    </select>
                    <select>
                        <?php  ?>
                    </select>
                </div>
                <div class="mb-3">
                    <input type="hidden" id="error" name="error" value="<?= $error ?>">
                    <input type="hidden" id="PostID" name="PostID" value="<?= $quote['PostID'] ?>">
                    <input type="submit" class="btn btn-primary" name="update_button" value="Submit">
                    <?php if($_SESSION['role'] == $VALUES_administrator_id || $_SESSION['role'] == $VALUES_moderator_id || $_SESSION['id'] == $quote['UserID']): ?>
                        <input type="submit" class="btn btn-secondary" name="delete_button" value="Delete">
                    <?php endif ?>
                </div>
            </form>
            <?php endif ?>
        </div>
    </div>
 </body>
 </html>