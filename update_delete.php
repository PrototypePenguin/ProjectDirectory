<?php 
/******************************************************************
 * Author:  Charles Burns
 * Date:    Jan 31, 2022
 * Purpose: Allows users to update and delete posts
 ******************************************************************/
	require ('authenticate.php');
    require ('connect.php');

    $error = null;

    // Updates the $error variable with any errors from the submited form
    if ($_POST && isset($_POST['error'])) {
        $error = filter_input(INPUT_POST, 'error', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    }

    // UPDATE quote if title, content and id are present in POST.
    if ($_POST && isset($_POST['title']) && isset($_POST['content']) && isset($_POST['id']) && isset($_POST['update_button'])) {
        // Sanitize user input to escape HTML entities and filter out dangerous characters.
        $title   = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $id      = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
        
        if ($id == "") {
            // If id is not a valid int return to homepage
            header("Location: home.php");
            exit;
        }

        // Build the parameterized SQL query and bind to the above sanitized values.
        $query     = "UPDATE blog SET title = :title, content = :content WHERE id = :id";
        $statement = $db->prepare($query);
        $statement->bindValue(':title', $title);
        $statement->bindValue(':content', $content);
        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        
        if (strlen($title) > 140 || strlen($content) > 1000 || trim(strlen($title)) < 1 || trim(strlen($content)) < 1) {
            // Check if either $title or $content are within their limits
            if (strlen($title) > 140) {
                $error = "The title of your post was " . strlen($title) - 140 . " characters too long";
            } elseif (strlen($title) < 1) {
                $error = "Your title cannot be empty";
            }
            // If the title was not too long $error will be null.
            if ($error == null && strlen($content) > 1000) {
                $error = "Your post was " . strlen($content) - 1000 . " characters too long";
            } elseif ($error == null && strlen($content) < 1) {
                $error = "Your content cannot be empty";
                
            } elseif (strlen($content) > 1000) {
                $error = $error . " and your post was " . strlen($content) - 1000 . " characters too long";
            } elseif (strlen($content) < 1) {
                $error = $error . " and your content cannot be empty";
            }
            $error = $error . ".";
        } elseif ($statement->execute()) {

        } else {
            $error = "Unhandled Error!";
        }
        
        // Redirect after update.
        header("Location: home.php");
        exit;
    } else if ($_POST && isset($_POST['id']) && isset($_POST['delete_button'])) {
        // Sanitize user input to escape HTML entities and filter out dangerous characters.
        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

        if ($id > 0 && $id != "") {
            // Build the parameterized SQL query and bind to the above sanitized values.
            $query     = "DELETE FROM blog WHERE id = :id";
            $statement = $db->prepare($query);
            $statement->bindValue(':id', $id, PDO::PARAM_INT);

            $statement->execute();
        }
        // Return on both good and bad id's
        header("Location: home.php");
        exit;
        
    } else if (isset($_GET['id'])) { // Retrieve post to be edited, if id GET parameter is in URL.
        // Sanitize the id. Like above but this time from INPUT_GET.
        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

        if ($id > 0 && $id != "") {
            // Build the parametrized SQL query using the filtered id.
            $query     = "SELECT * FROM blog WHERE id = :id LIMIT 1";
            $statement = $db->prepare($query);
            $statement->bindValue(':id', $id, PDO::PARAM_INT);
            
            // Execute the SELECT and fetch the single row returned.
            $statement->execute();
            $quote = $statement->fetch();
        } else {
            // If the user enters an invalid id return to homepage
            header("Location: home.php");
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
    <link rel="stylesheet" type="text/css" href="style.css">
 	<title>Update <?php if(!isset($error)): ?><?= $quote['title'] ?><?php endif ?></title>
 </head>
 <body>
    <div class="nav">
        <a href="home.php">Return to Home</a>
    </div>
    <?php if (isset($error)): ?> <!-- Don't show form if there are errors -->
        <h1><?= $error ?></h1>
    <?php else: ?>
        <div class="form">
            <h1>Enter you next blog post!</h1>
            <form action="update_delete.php?id=<?= $_GET['id'] ?>" method="post">
                <label for="title">Title:</label><br>
                <input type="text" id="title" name="title" value="<?= $quote['title'] ?>" autofocus><br>
                <label for="content">Content:</label><br>
                <textarea id="content" name="content" rows="5" cols="50"><?= $quote['content'] ?></textarea><br>
                <input type="hidden" id="id" name="id" value="<?= $quote['id'] ?>">
                <input type="hidden" id="error" name="error" value="<?= $error ?>">
                <input type="submit" name="update_button" value="Submit">
                <input type="submit" name="delete_button" value="Delete">
            </form>
        </div>
    <?php endif ?>
 </body>
 </html>