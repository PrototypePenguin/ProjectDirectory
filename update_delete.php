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
        header("location: posts.php");
    } elseif($_SESSION['role'] == $VALUES_user_id || $_SESSION['role'] == 0) {
        header("location: posts.php");
    }
    // Updates the $error variable with any errors from the submited form
    if ($_POST && isset($_POST['error'])) {
        $error = filter_input(INPUT_POST, 'error', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    }

    // UPDATE quote if PostTitle, PostContent, ImageID are present in POST and PostID is in the GET.
    if ($_POST && isset($_POST['PostTitle']) && isset($_POST['PostContent']) && isset($_GET['PostID']) && isset($_POST['ImageID']) && isset($_POST['update_button'])) {
        // Sanitize user input to escape HTML entities and filter out dangerous characters.
        $PostTitle   = filter_input(INPUT_POST, 'PostTitle', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $PostContent = filter_input(INPUT_POST, 'PostContent', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $PostID      = filter_input(INPUT_GET, 'PostID', FILTER_SANITIZE_NUMBER_INT);
        $ImageID     = filter_input(INPUT_POST, 'ImageID', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        
        if ($PostID == "") {
            // If PostID is not a valid int return to homepage
            header("Location: posts.php");
            exit;
        }

        // Build the parameterized SQL query and bind to the above sanitized values.
        $query     = "UPDATE Posts SET PostTitle = :PostTitle, PostContent = :PostContent, ImageID = :ImageID WHERE PostID = :PostID";
        $statement = $db->prepare($query);
        $statement->bindValue(':PostTitle', $PostTitle);
        $statement->bindValue(':PostContent', $PostContent);
        $statement->bindValue(':PostID', $PostID, PDO::PARAM_INT);
        $statement->bindValue(':ImageID', $ImageID);
        
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

            foreach ($_POST['PostSubject'] as $SubjectID) {
                $query = "SELECT * FROM postsubject WHERE SubjectID = :SubjectID AND PostID = :PostID";

                $statement = $db->prepare($query);

                $statement->bindValue(":SubjectID", $SubjectID, PDO::PARAM_INT);
                $statement->bindValue("PostID", $PostID, PDO::PARAM_INT);

                $statement->execute();

                if ($statement->rowCount() == 0) {
                    $query = "INSERT INTO postsubject (PostID, SubjectID) VALUES (:PostID, :SubjectID)";

                    $statement = $db->prepare($query);
    
                    $statement->bindValue(":PostID", $PostID, PDO::PARAM_INT);
                    $statement->bindValue(":SubjectID", $SubjectID, PDO::PARAM_INT);
    
                    $statement->execute();
                }
            }
            

            // Redirect after update.
            header("Location: full_post.php?PostID=".$PostID);

        } else {
            $error = "Unhandled Error!";
        }
        exit;

    // Check roles again to prevent injection from someone with edit privileges but not delete
    } else if ($_POST && isset($_POST['PostID']) && isset($_POST['delete_button']) && ($_SESSION['role'] == $VALUES_administrator_id || $_SESSION['role'] == $VALUES_moderator_id || $_SESSION['id'] == $quote['UserID'])) {
        
        // Sanitize user input to escape HTML entities and filter out dangerous characters.
        $PostID = filter_input(INPUT_POST, 'PostID', FILTER_SANITIZE_NUMBER_INT);

        if ($PostID > 0 && $PostID != "") {
            // Delete Child rows in PostSubject
            $query     = "DELETE FROM PostSubject WHERE PostID = :PostID";
            $statement = $db->prepare($query);
            $statement->bindValue(":PostID", $PostID, PDO::PARAM_INT);

            $statement->execute();

            // Build the parameterized SQL query and bind to the above sanitized values.
            $query     = "DELETE FROM Posts WHERE PostID = :PostID";
            $statement = $db->prepare($query);
            $statement->bindValue(':PostID', $PostID, PDO::PARAM_INT);

            $statement->execute();
        }
        // Return on both good and bad PostID's
        header("Location: posts.php");
        exit;
        
    } else if (isset($_GET['PostID'])) { // Retrieve post to be edited, if PostID GET parameter is in URL.
        // Sanitize the PostID. Like above but this time from INPUT_GET.
        $PostID = filter_input(INPUT_GET, 'PostID', FILTER_SANITIZE_NUMBER_INT);

        if ($PostID > 0 && $PostID != "") {
            // Build the parametrized SQL query using the filtered PostID.
            $query     = "SELECT posts.PostID, UserID, PostTitle, PostCategory, PostDesc, PostContent, postsubject.SubjectID AS SubjectID, Subject, posts.ImageID, ImagePath FROM posts, subjects, postsubject, images WHERE posts.PostID = :PostID AND posts.PostID = postsubject.PostID AND postsubject.SubjectID = subjects.SubjectID AND images.ImageID = posts.ImageID LIMIT 1";
            $statement = $db->prepare($query);
            $statement->bindValue(':PostID', $PostID, PDO::PARAM_INT);
            
            // Execute the SELECT and fetch the single row returned.
            $statement->execute();
            $quote = $statement->fetch();

            //Grab information for drop down lists
            $query = "SELECT SubjectID, Subject FROM subjects";

            $subject_list = $db->prepare($query);

            $subject_list->execute();

            $query = "SELECT ImageID, ImagePath FROM images";

            $image_list = $db->prepare($query);

            $image_list->execute();

        } else {
            // If the user enters an invalid PostID return to homepage
            header("Location: posts.php");
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
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="styles/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script> 
    <title>Edit <?= $quote['PostTitle'] ?> - The Watcher</title>
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
                    <input type="text" class="form-control" id="PostTitle" name="PostTitle" value="<?= $quote['PostTitle'] ?>" autofocus required>
                    <div class="invalid-feedback">
                        Please provide a valid Title.
                    </div>
                </div>
                <div class="mb-3 mt-3">
                    <label for="PostCategory" class="form-label">Category:</label>
                    <input type="text" class="form-control" id="PostCategory" name="PostCategory" value="<?= $quote['PostCategory'] ?>" required>
                    <div class="invalid-feedback">
                        Please provide a valid Category.
                    </div>
                </div>
                <div class="mb-3">
                    <label for="PostContent" class="form-label">Content:</label>
                    <textarea class="form-control" id="PostContent" name="PostContent" rows="5" required><?= $quote['PostContent'] ?></textarea>
                    <div class="invalid-feedback">
                        Please provide valid content for this post.
                    </div>
                </div>
                <div class="mb-3">
                    <label for="PostDesc" class="form-label">Snippet:</label>
                    <textarea class="form-control" id="PostDesc" name="PostDesc" rows="3"><?= $quote['PostDesc'] ?></textarea>
                </div>
                <div class="mb-3">
                    <label for="PostSubject" class="form-label">Subject:</label>
                    <div class="input-group">
                        <button class="btn btn-primary" type="button" onclick="Subject()">New Subject</button>
                        <script>
                            //Stores form data in cookies so that off page forms don't undo progress
                            function StoreFormCookies() {
                               //Keeps items from disapearing when user decides to add an image.
                               document.cookie = "PostTitle=" + document.getElementById("PostTitle").value + ";";
                               document.cookie = "PostCategory=" + document.getElementById("PostCategory").value + ";";
                               document.cookie = "PostDesc=" + document.getElementById("PostDesc").value + ";";
                               document.cookie = "PostContent=" + document.getElementById("PostContent").value + ";";
                               document.cookie = "Source=update_delete.php?PostID=" + <?= $PostID ?>;
                            }
                            // Opens the subject_control form
                            function Subject() {
                                //Stores form data in cookies so that off page forms don't undo progress
                                StoreFormCookies();
                                
                                window.location = "subject_controls.php";
                            }
                        </script>
                        <select multiple class="form-select" name="PostSubject" id="PostSubject">
                            <?php while ($row = $subject_list->fetch()): ?>
                                <option value="<?= $row['SubjectID'] ?>" <?php if($quote['SubjectID'] == $row['SubjectID']): ?>selected<?php endif ?>><?= $row['Subject'] ?></option>
                            <?php endwhile ?>
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="ImageIDSelect" class="form-label">Image:</label>
                    <div class="input-group">
                        <button class="btn btn-primary" type="button" onclick="Image()">New Image</button>
                        <script>
                            // Opens the add image form
                            function Image() {
                                //Stores form data in cookies so that off page forms don't undo progress
                                StoreFormCookies();
                                
                                window.location = "images.php";
                            }
                        </script>
                        <select class="form-select" name="ImageID" id="ImageIDSelect" onchange="getSelectedValue()">
                            <?php while($row = $image_list->fetch()): ?>
                                <option value="<?= $row['ImageID'] ?>"
                                    <?php if(isset($_SESSION['form_success']) && isset($_SESSION['ImageID'] ) && ($_SESSION['ImageID'] != "") && $_SESSION['form_success'] == "images" && $_SESSION['ImageID'] == $row['ImageID']): ?>
                                        selected=""
                                    <?php elseif((!isset($_SESSION['ImageID']) || $_SESSION['ImageID'] == null) && $row['ImageID'] == $quote['ImageID']): ?>
                                        selected=""
                                    <?php endif ?>
                                    >
                                    <?php if($row['ImagePath'] == ""): ?>
                                        No Image
                                    <?php else: ?>
                                        <?= substr($row['ImagePath'], strpos($row['ImagePath'], '/')+1) ?>
                                    <?php endif ?>
                                </option>
                            <?php endwhile ?>
                        </select>
                        <button class="btn btn-primary" id="delete_image_button" value="" type="button" onclick="ImageDelete()">Delete</button>
                        <script>
                            function getSelectedValue() {
                                let selectedValue = document.getElementById('ImageIDSelect').value;
                                return selectedValue;
                            }
                            function ImageDelete() {
                                //Stores form data in cookies so that off page forms don't undo progress
                                StoreFormCookies();
                                
                                //onchange on the select ImageID sets the button value to the current index
                                let selectValue = getSelectedValue();

                                if(confirm("Are you sure you want to delete ImageID: " + selectValue) == true && selectValue != 6){
                                    document.cookie = "ImageIDDelete=" + selectValue;
                                    window.location = "image_delete.php";
                                } else if (selectValue == 6) {
                                    alert("You cannot delete the blank image.");
                                }
                            }
                        </script>
                    </div>
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
        <?php if(isset($_SESSION['form_success']) && $_SESSION['form_success'] != false): ?>
            <div class="alert alert-success alert-dismissible fixed-bottom">
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                <strong>Success!</strong> Your <?= ($_SESSION['form_success'] == "images" ? "image" : "") ?> <?= ($_SESSION['form_success'] == "subject" ? "subject" : "") ?> was           successfully uploaded.
            </div>
            <?php endif ?>
            <?php
                if(isset($_SESSION['form_success'])){ $_SESSION['form_success'] = $_SESSION['ImageID'] = false; }
            ?>
    </div>
 </body>
 </html>