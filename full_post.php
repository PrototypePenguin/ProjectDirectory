<?php 
/******************************************************************
 * Author:  Charles Burns
 * Date:    Mar 24, 2022
 * Purpose: Allows users to see a single blog post without being
 * truncated
 ******************************************************************/
    session_start();
    require ('db.php');

    if ($_GET && !empty($_GET['PostID'])) {

        $query = "SELECT * FROM Posts WHERE PostID = :PostID LIMIT 1";
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
    <link rel="stylesheet" type="text/css" href="style.css">
 	<title>
        <?php if($statement->rowCount() != 0): ?>
            <?= $quote['PostTitle'] ?>
        <?php endif ?>
    </title>
 </head>
 <body>
    <?php include("nav.php"); ?>
    <?php if($statement->rowCount() != 0): ?>
        <div class="posts">
            <h1><?= $quote['PostTitle'] ?></h1>
            
            <?php if($_SESSION['role'] == 1 || $_SESSION['role'] == 5 || $_SESSION['role'] == 6 || $_SESSION['role'] == 7): ?>
                <p class="edit"><a href="update_delete.php?PostID=<?= $row['PostID'] ?>">edit</a></p>
            <?php endif ?>

            <p class="time"><?= date("F j, Y, g:i a", strtotime($quote['PostTimestamp'])) ?></p>
            <p class="content"><?= $quote['PostContent'] ?></p>
        </div>
    <?php endif ?>
 </body>
 </html>