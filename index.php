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
    $query = "SELECT PostID, PostTitle, PostContent, PostTimestamp, ImagePath, Posts.ImageID FROM Posts, Images WHERE Posts.ImageID = Images.ImageID OR Posts.ImageID = null ORDER BY PostID DESC LIMIT ".$limit;
    $statement = $db->prepare($query);

    $statement->execute();
 ?>

 <!DOCTYPE html>
 <html lang="en">
 <head>
 	<meta charset="utf-8">
 	<meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="styles/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script> 
 	<title>Home of fantastic mcu content - The Watcher</title>
 </head>
 <body>
    <div class="container">
        <div class="row">
            <div class="col-sm-6">
                <h1>The Watcher: A MCU Fansite</h1>
            </div>
        </div>
        <div class="row">
            <?php require("nav.php"); ?>
        </div>
        <div class="row" style="height: 500px;">
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
                        <?php if($_SESSION['role'] == $VALUES_administrator_id || $_SESSION['role'] == $VALUES_moderator_id || $_SESSION['role'] == $VALUES_writer_id): // Writer Permissions ?>
                            <a class="text-decoration-none text-body" href="new_post.php">Start us off with the first post</a>
                        <?php else: ?>
                            <p>Come back later to see the results!</p>
                        <?php endif ?>
                    </div>
                    <?php else: //if there are articles written?>
                          
                        <!-- The carousel -->
                        <div class="carousel-inner">
                            <?php if($row = $statement->fetch()): ?>
                                <div class="carousel-item active bg-dark" style="height: 70%; padding-top: 70%; padding-bottom: 70%; box-sizing: border-box; position: relative;">
                                    <?php if($row['ImageID'] != 6): ?>
                                        <img src="<?= $row['ImagePath'] ?>" alt="<?= $row['PostTitle'] ?>" class="w-100 mx-auto" style="position: absolute; top: 0; left: 0;">
                                    <?php endif ?>
                                    <div class="carousel-caption">
                                        <a class="text-light text-decoration-none" href="full_post.php?PostID=<?= $row['PostID'] ?>">
                                            <h3 class="bg-dark rounded-top mb-0 pb-2 px-3" style="--bs-bg-opacity: .5;"><?= $row['PostTitle'] ?></h3>
                                        </a>
                                        <a class="text-light text-decoration-none" href="full_post.php?PostID=<?= $row['PostID'] ?>">
                                            <p class="bg-dark rounded-bottom px-3" style="--bs-bg-opacity: .5;"><?= substr($row['PostContent'], 0, 300) ?></p>
                                        </a>
                                    </div>
                                </div>
                                <?php while ($row = $statement->fetch()): ?>
                                    <div class="carousel-item bg-dark" style="height: 70%; padding-top: 70%; padding-bottom: 70%; box-sizing: border-box; position: relative;">
                                        <img src="<?= $row['ImagePath'] ?>" alt="<?= $row['PostTitle'] ?>" class="w-100 mx-auto" style="position: absolute; top: 0; left: 0;">
                                        <div class="carousel-caption">
                                            <a class="text-light text-decoration-none" href="full_post.php?PostID=<?= $row['PostID'] ?>">
                                                <h3 class="bg-dark rounded-top px-3 mb-0 pb-2" style="--bs-bg-opacity: .5;"><?= $row['PostTitle'] ?></h3>
                                            </a>
                                            <a class="text-light text-decoration-none" href="full_post.php?PostID=<?= $row['PostID'] ?>">
                                                <p class="bg-dark rounded-bottom px-3" style="--bs-bg-opacity: .5;">
                                                    <?= substr($row['PostContent'], 0, 300) ?>
                                                    <?= (strlen($row['PostContent']) <= 300) ?: "..." ; ?>
                                                </p>
                                            </a>
                                        </div>
                                    </div>
                                <?php endwhile //No more rows to grab?>
                            <?php endif ?>
                        </div>
                    <?php endif ?>
                </div>
                

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