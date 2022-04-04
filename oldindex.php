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

    // Amount of items to grab for the homepage
    $limit = 5;

    // Build and prepare SQL String with :id placeholder parameter.
    $query = "SELECT PostID, PostTitle, PostContent, PostTimestamp, ImagePath FROM Posts, Images WHERE Posts.SubjectID = Images.SubjectID ORDER BY PostID DESC LIMIT ".$limit;
    $statement = $db->prepare($query);

    $statement->execute();
 ?>

 <!DOCTYPE html>
 <html lang="en">
 <head>
 	<meta charset="utf-8">
 	<meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script> 
 	<title>Home</title>
 </head>
 <body>
    <?php include("nav.php"); ?>
    <?php if ($_SESSION['role'] == $VALUES_administrator_id || $_SESSION['role'] == $VALUES_moderator_id || $_SESSION['role'] == $VALUES_writer_id && isset($_SESSION)): ?>
     	<div class="nav">
            <a href="new_post.php">New Post</a>
        </div>
    <?php endif ?>
    	
    <div id="latest_posts" class="carousel slide" data-bs-ride="carousel">
        <!-- Indicators -->
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#latest_posts" data-bs-slide="0" class="active"></button>
            <?php for ($i=2; $i < $limit; $i++): ?>
                <button type="button" data-bs-target="#latest_posts" data-bs-slide="<?= $i ?>"></button>
            <?php endfor ?>
        </div>

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
                <!-- The carousel -->
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="<?= $row['ImagePath'] ?>" alt="<?= $PostTitle ?>" class="d-block w-100">
                    </div>
                </div>
                <img src="">
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
        

        <!-- Left and right controls -->
        <button class="carousel-control-prev" type="button" data-bs-target="#latest_posts" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#latest_posts" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>
 </body>
 </html>