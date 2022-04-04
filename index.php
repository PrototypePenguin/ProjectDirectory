<?php 
/*********************************************************************
 * Author:  Charles Burns
 * Date:    Mar 24, 2022
 * Purpose: Home page for Web Dev 2 - Assignment 3: Blogging with CRUD
 * Shows five most recent plog posts most recent to least
 *********************************************************************/
	session_start();

    require('db.php'); // db connection
    require('values.php'); // constants used throughout website

    // I don't know what this is or if it does anything but I'm leaving it here as a reminder of why well named variables are good.
    // https://imgflip.com/i/6bdtcr
    $i = 0;

    // Build and prepare SQL String with :id placeholder parameter.
    $query = "SELECT * FROM Posts ORDER BY PostID DESC LIMIT 5";
    $statement = $db->prepare($query);

    $statement->execute();
 ?>

 <!DOCTYPE html>
 <html lang="en">
 <head>
 	<meta charset="utf-8">
 	<meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="style.css">
 	<title>Home</title>
 </head>
 <body>
    <?php include("nav.php"); ?>
    <?php if ($_SESSION['role'] == $VALUES_administrator_id || $_SESSION['role'] == $VALUES_moderator_id || $_SESSION['role'] == $VALUES_writer_id && isset($_SESSION)): ?>
     	<div class="nav">
            <a href="new_post.php">New Post</a>
        </div>
    <?php endif ?>
    	<?php if ($statement->rowCount() == 0): ?>
        <div class="posts">
    			<h1>This page is under construction.</h1>
            <p>Come back later to see the results!</p>
        </div>
    	<?php else: ?>
    		<?php while ($row = $statement->fetch()): ?>
    			<div class="posts">
                <h1><a href="full_post.php?PostID=<?= $row['PostID'] ?>"><?= $row['PostTitle'] ?></a></h1>
                
                <?php if($_SESSION['role'] == $VALUES_administrator_id || $_SESSION['role'] == $VALUES_moderator_id || $_SESSION['role'] == $VALUES_writer_id || $_SESSION['role'] == $VALUES_editor_id): ?>
                    <p class="edit"><a href="update_delete.php?PostID=<?= $row['PostID'] ?>">edit</a></p>
                <?php endif ?>

				<p class="time"><?= date("F j, Y, g:i a", strtotime($row['PostTimestamp'])) ?></p>
                <p class="content">
                    <?= substr($row['PostContent'], 0, 200) ?>

    					<?php if (strlen($row['PostContent']) > 200): ?>
                        <a href="full_post.php?PostID=<?= $row['PostID'] ?>">Read Full Post</a>
                    <?php endif ?>
    			</p>
            </div>
    		<?php endwhile ?>
    	<?php endif ?>
 </body>
 </html>