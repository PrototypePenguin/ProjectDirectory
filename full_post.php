<?php 
/******************************************************************
 * Author:  Charles Burns
 * Date:    Jan 31, 2022
 * Purpose: Allows users to see a single blog post without being
 * truncated
 ******************************************************************/
    require ('connect.php');

    if ($_GET && !empty($_GET['id'])) {

        $query = "SELECT * FROM blog WHERE id = :id LIMIT 1";
        $statement = $db->prepare($query);

        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

        if ($id > 0 && $id != "") {
            $statement->bindValue(':id', $id, PDO::PARAM_INT);

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
            <?= $quote['title'] ?>
        <?php endif ?>
    </title>
 </head>
 <body>
    <div class="nav">
        <a href="home.php">Home</a>
    </div>
    <?php if($statement->rowCount() != 0): ?>
        <div class="posts">
            <h1><?= $quote['title'] ?></h1>
            <p class="edit"><a href="update_delete.php?id=<?= $quote['id'] ?>">edit</a></p>

            <p class="time"><?= date("F j, Y, g:i a", strtotime($quote['date_time'])) ?></p><br>
            <p class="content"><?= $quote['content'] ?></p>
        </div>
    <?php endif ?>
 </body>
 </html>