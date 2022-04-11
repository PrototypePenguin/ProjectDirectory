<?php 
/******************************************************************
 * Author:  Charles Burns
 * Date:    Mar 24, 2022
 * Purpose: Allows users to see a single blog post without being
 * truncated
 ******************************************************************/
    session_start();
    
    require_once ('db.php');    //Contains database connection information
    require ('values.php');     //Contains constant values identified with VALUE_

    if ($_GET && !empty($_GET['PostID'])) {

        $query = "SELECT * FROM Posts, Images WHERE PostID = :PostID AND Posts.ImageID = Images.ImageID LIMIT 1";
        $statement = $db->prepare($query);

        $PostID = filter_input(INPUT_GET, 'PostID', FILTER_SANITIZE_NUMBER_INT);

        if ($PostID > 0 && $PostID != "") {
            $statement->bindValue(':PostID', $PostID, PDO::PARAM_INT);

            $statement->execute();

            $quote = $statement->fetch();
        } else {
            header('Location: index.php');
        }
        
    } else {
        header('Location: index.php');
    }
 ?>

 <!DOCTYPE html>
 <html lang="en">
 <head>
 	<meta charset="utf-8">
 	<meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="styles\styles.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script> 
 	<title>
        <?php if($statement->rowCount() != 0): ?>
            <?= $quote['PostTitle'] ?>
        <?php endif ?>
    </title>
 </head>
 <body>
    <div class="container">
        <div class="row">
            <?php include("nav.php"); ?>
        </div>
        <?php if ($quote['ImageID'] != 6): ?>
            <div class="row">
                <div class="col-sm-6">
                    <img class="img-fluid" src="<?= $quote['ImagePath'] ?>">
                </div>
            </div>
        <?php endif ?>
        <div class="row">
            <div class="col-sm-6">
                <?php if($statement->rowCount() != 0): ?>
                    <div class="posts">
                        <h1><?= $quote['PostTitle'] ?></h1>
                        
                        <?php if($_SESSION['role'] == $VALUES_administrator_id || $_SESSION['role'] == $VALUES_moderator_id || $_SESSION['role'] == $VALUES_writer_id || $_SESSION['role'] == $VALUES_editor_id): ?>
                            <p class="edit"><a href="update_delete.php?PostID=<?= $quote['PostID'] ?>">edit</a></p>
                        <?php endif ?>

                        <p class="time"><?= date("F j, Y, g:i a", strtotime($quote['PostTimestamp'])) ?></p>
                        <p class="content"><?= $quote['PostContent'] ?></p>
                    </div>
                <?php endif ?>
            </div>
        </div>
    </div>
    

 </body>
 </html>