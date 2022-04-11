<?php 
	session_start();

    require_once ('db.php');    //Contains database connection information
    require ('values.php');     //Contains constant values identified with VALUE_

    // Amount of items to grab per page
    $limit = 15;

    // Amount of pages for all data
    $query = "SELECT PostID FROM Posts";
    $statement = $db->prepare($query);

    $current_page = 1;
    if (isset($_GET['page'])) {
        $current_page = $_GET['page'];
    }

    $statement->execute();

    $post_count = $statement->rowCount();
    $page_count = ceil($post_count / $limit);	//ceil rounds up fractions

    $previous = "";
    if ($current_page - 1 == 0) {
        $previous = " disabled";
    }

    $next = "";
    if ($current_page - $page_count <= 0) {
        $next = " disabled";
    }

    // Build and prepare SQL String with :id placeholder parameter.
    $query = "SELECT Posts.PostID AS PostID, PostTitle, PostContent, PostTimestamp, ImagePath FROM Posts, Images WHERE ImageOrientation = 'portrait' AND Posts.ImageID = Images.ImageID ORDER BY PostID DESC LIMIT ".($current_page-1).",".$limit; 
    $statement = $db->prepare($query);

    $statement->execute();
 ?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="styles\styles.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script> 
	<title></title>
</head>
<body>
	<div class="container">
		<div class="row">
			<?php require("nav.php"); ?>
		</div>
        <div class="row border-bottom pb-3 mb-3">
            <div class="col-sm-11">
                <h2 style="padding-top: 22px;">Posts</h2>
            </div>
            <?php if($_SESSION['role'] == $VALUES_administrator_id || $_SESSION['role'] == $VALUES_moderator_id || $_SESSION['role'] == $VALUES_writer_id): ?>
                <div class="col-1 mt-3 pt-3">
                    <a href="new_post.php">New Post</a>
                </div>
            <?php endif ?>
        </div>
        <?php if ($statement->rowCount() == 0): ?>
            <p>Nothing to see here.</p>
        <?php endif ?>
        <?php while($row = $statement->fetch()): ?>
    		<a class="text-decoration-none text-body" href="full_post.php?PostID=<?= $row['PostID'] ?>">
    			<div class="row border-bottom pb-3 mb-3">
        	        <div class="col-16 col-sm-16 col-md-16 col-lg-4 col-xl-4 col-xxl-3">
        	            <img class="img-fluid" style="max-width: 100%;" src="<?= $row['ImagePath'] ?>">
        	        </div>
        	        <div class="col-16 col-sm-16 col-md-16 col-lg-8 col-xl-8 col-xxl-9">
        	        	<div class="col-16 col-sm-16 col-md-16 col-lg-8 col-xl-8 col-xxl-9">
        	    	        <h3><?= $row['PostTitle'] ?></h3><p>Published: <?= $row['PostTimestamp'] ?></p>
        	        	</div>
        	        	<div class="col-16 col-sm-16 col-md-16 col-lg-8 col-xl-8 col-xxl-9">
        	        		<p style="max-height: 145px; overflow-y: hidden; text-overflow: ellipsis;"><?= $row['PostContent'] ?></p>
        	        	</div>
        	    	</div>
    			</div>
    		</a>
        <?php endwhile ?>
        <ul class="pagination">
        	<li class="page-item<?= $previous ?>"><a class="page-link" href="posts.php?page=<?= $current_page-1 ?>">Previous</a></li>
        	<?php for ($i=1; $i <= $page_count; $i++): ?>
        		<li class="page-item"><a class="page-link" href="posts.php?page=<?= $i ?>"><?= $i ?></a></li>
        	<?php endfor ?>
        	<li class="page-item<?= $next ?>"><a class="page-link" href="posts.php?page=<?= $current_page+1 ?>">Next</a></li>
        </ul>
	</div>
</body>
</html>