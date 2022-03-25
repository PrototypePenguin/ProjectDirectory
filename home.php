<?php 
/*********************************************************************
 * Author:  Charles Burns
 * Date:    Mar 24, 2022
 * Purpose: Home page for Web Dev 2 - Assignment 3: Blogging with CRUD
 * Shows five most recent plog posts most recent to least
 *********************************************************************/
	require('connect.php');
    
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
 	<div class="nav">
        <a href="new_post.php">New Post</a>
    </div>
    	<?php if ($statement->rowCount() == 0): ?>
        <div class="posts">
    			<h1>It's Lonely Over Here!</h1>
            <p>Be the first to post!</p>
        </div>
    	<?php else: ?>
    		<?php while ($row = $statement->fetch()): ?>
    			<div class="posts">
                <h1><a href="full_post.php?PostID=<?= $row['PostID'] ?>"><?= $row['PostTitle'] ?></a></h1>
                <p class="edit"><a href="update_delete.php?PostID=<?= $row['PostID'] ?>">edit</a></p>
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