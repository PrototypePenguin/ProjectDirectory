<?php 
/******************************************************************
 * Author:  Charles Burns
 * Date:    Mar 24, 2022
 * Purpose: Allows users to see a single blog post without being
 * truncated
 ******************************************************************/
    require ('connect.php');

    if ($_GET && !empty($_GET['PostID'])) {

        $query = "SELECT * FROM Posts WHERE PostID = :PostID LIMIT 1";
        $statement = $db->prepare($query);

        $PostID = filter_input(INPUT_GET, 'PostID', FILTER_SANITIZE_NUMBER_INT);

        if ($PostID > 0 && $PostID != "") {
            $statement->bindValue(':PostID', $PostID, PDO::PARAM_INT);

            $statement->execute();

            $quote = $statement->fetch();
        } else {
            header('Location: home.php');
        }
        
    } else {
        header('Location: home.php');
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
    <div class="nav">
        <a href="home.php">Home</a>
    </div>
    <?php if($statement->rowCount() != 0): ?>
        <div class="posts">
            <h1><?= $quote['PostTitle'] ?></h1>
            <p class="edit"><a href="update_delete.php?PostID=<?= $quote['PostID'] ?>">edit</a></p>

            <p class="time"><?= date("F j, Y, g:i a", strtotime($quote['PostTimestamp'])) ?></p><br>
            <p class="content"><?= $quote['PostContent'] ?></p>
        </div>
    <?php endif ?>
 </body>
 </html>