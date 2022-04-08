<?php 
/*********************************************************************
 * Author:  Charles Burns
 * Date:    Mar 24, 2022
 * Purpose: Home page for Web Dev 2 - Assignment 3: Blogging with CRUD
 * Shows five most recent plog posts most recent to least
 *********************************************************************/
	session_start();

    require_once ('db.php');    //Contains database connection information
    require ('values.php');     //Contains constant values identified with VALUE_

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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="styles\styles.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script> 
 	<title>Home</title>
 </head>
 <body>
    
    <!-- <?php if ($_SESSION['role'] == $VALUES_administrator_id || $_SESSION['role'] == $VALUES_moderator_id || $_SESSION['role'] == $VALUES_writer_id && isset($_SESSION)): ?>
     	<div class="nav">
            <a href="new_post.php">New Post</a>
        </div>
    <?php endif //Ends Authentication Check ?> -->
    <div class="container">
        <div class="row">
            <div class="col-sm-6">
                <h1>The Watcher: A MCU Fansite</h1>
            </div>
        </div>
        <div class="row">
            <?php include("nav.php"); ?>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div id="latest_posts" class="carousel slide" data-bs-ride="carousel">
                    <!-- Indicators -->
                    <div class="carousel-indicators">
                        <button type="button" data-bs-target="#latest_posts" data-bs-slide-to="0" class="active"></button>
                        <?php for ($i=1; $i < $statement->rowCount(); $i++): ?>
                            <button type="button" data-bs-target="#latest_posts" data-bs-slide-to="<?= $i ?>"></button>
                        <?php endfor //Ends for defining carousel page count?>
                    </div>

                    <?php if ($statement->rowCount() == 0): ?>
                    <div class="posts">
                            <h1>This page is under construction.</h1>
                        <p>Come back later to see the results!</p>
                    </div>
                    <?php else: //if there are articles written?>
                          
                        <!-- The carousel -->
                        <div class="carousel-inner">
                            <?php if($row = $statement->fetch()): ?>
                                <div class="carousel-item active">
                                    <img src="<?= $row['ImagePath'] ?>" alt="<?= $row['PostTitle'] ?>" class="d-block w-100">
                                    <div class="carousel-caption">
                                        <h3><?= $row['PostTitle'] ?></h3>
                                        <p><?= substr($row['PostContent'], 0, 300) ?></p>
                                    </div>
                                </div>
                                <?php while ($row = $statement->fetch()): ?>
                                    <div class="carousel-item">
                                        <img src="<?= $row['ImagePath'] ?>" alt="<?= $row['PostTitle'] ?>" class="d-block w-100">
                                        <div class="carousel-caption">
                                            <h3><?= $row['PostTitle'] ?></h3>
                                            <p>
                                                <?= substr($row['PostContent'], 0, 300) ?>
                                                <?= (strlen($row['PostContent']) <= 300) ?: "..." ; ?>
                                            </p>
                                        </div>
                                    </div>
                                <?php endwhile //No more rows to grab?>
                            <?php endif ?>
                        </div>
                </div>
                        
                <?php endif ?>
                

                <!-- Left and right controls -->
                <button class="carousel-control-prev" type="button" data-bs-target="#latest_posts" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#latest_posts" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </button>
            </div>
            <div class="col-sm-6">
                <h2>A New Way to Keep Up With an Ever Expanding Universe</h2>
                <p>Here at The Watcher we pride ourselves on providing the best information on the comings and goings of the wonderful Marvel Cinimatic Universe.</p>
            </div>
        </div>
    </div>
 </body>
 </html>