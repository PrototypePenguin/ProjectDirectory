<?php 
	session_start();

    require_once ('db.php');    //Contains database connection information
    require ('values.php');     //Contains constant values identified with VALUE_

    // Amount of items to grab per page
    $limit = 15;

    $Search = "%".filter_input(INPUT_POST, 'Search', FILTER_SANITIZE_FULL_SPECIAL_CHARS)."%";
    if ($_POST && $_POST['subjectSelect'] > 0) {
        $Select = filter_input(INPUT_POST, 'subjectSelect', FILTER_SANITIZE_NUMBER_INT);

        // Amount of pages for all data
        $query = "SELECT Posts.PostID AS PostID FROM Posts, PostSubject, Subjects, Images, Users WHERE Posts.UserID = Users.UserID AND Posts.ImageID = Images.ImageID AND Posts.PostID = PostSubject.PostID AND Subjects.SubjectID = PostSubject.SubjectID AND Subjects.SubjectID = :SelectSubject AND (PostTitle LIKE :SearchTitle OR Subject LIKE :SearchSubject OR UserName LIKE :SearchAuthor) GROUP BY Posts.PostID";

        $statement = $db->prepare($query);
        $statement->bindValue(":SelectSubject", $Select, PDO::PARAM_INT);
    } else {
        // Amount of pages for all data
        $query = "SELECT Posts.PostID AS PostID FROM Posts, PostSubject, Subjects, Images, Users WHERE Posts.UserID = Users.UserID AND Posts.ImageID = Images.ImageID AND Posts.PostID = PostSubject.PostID AND Subjects.SubjectID = PostSubject.SubjectID AND (PostTitle LIKE :SearchTitle OR Subject LIKE :SearchSubject OR UserName LIKE :SearchAuthor) GROUP BY Posts.PostID";

        $statement = $db->prepare($query);
    }

    
    

    $statement->bindValue(":SearchTitle", $Search, PDO::PARAM_STR);
    $statement->bindValue(":SearchSubject", $Search, PDO::PARAM_STR);
    $statement->bindValue(":SearchAuthor", $Search, PDO::PARAM_STR);

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
    if ($current_page - $page_count <= 0 || $page_count == 0) {
        $next = " disabled";
    }

    $sort_by = "sortByTime";

    if ($_POST && !empty($_POST['optRadio'])) {
        $sort_by = filter_input(INPUT_POST, 'optRadio', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    }

    //Determines what order posts will appear by.
    if ($_POST && $_POST['subjectSelect'] != -1) {
        if ($sort_by == "sortByTime") {
            $query = "SELECT Posts.PostID AS PostID, PostTitle, PostContent, PostTimestamp, ImagePath, posts.ImageID, UserName FROM Posts, PostSubject, Subjects, Images, Users WHERE Posts.UserID = Users.UserID AND Posts.ImageID = Images.ImageID AND Posts.PostID = PostSubject.PostID AND Subjects.SubjectID = PostSubject.SubjectID AND Subjects.SubjectID = :SelectSubject AND (PostTitle LIKE   :SearchTitle OR Subject LIKE :SearchSubject OR UserName LIKE :SearchAuthor) GROUP BY Posts.PostID ORDER BY PostTimestamp DESC LIMIT ".(($current_page-1)*$limit).",".$limit;
        } elseif ($sort_by == "sortByTitle") {
            $query = "SELECT Posts.PostID AS PostID, PostTitle, PostContent, PostTimestamp, ImagePath, posts.ImageID, UserName FROM Posts, PostSubject, Subjects, Images, Users WHERE Posts.UserID = Users.UserID AND Posts.ImageID = Images.ImageID AND Posts.PostID = PostSubject.PostID AND Subjects.SubjectID = PostSubject.SubjectID AND Subjects.SubjectID = :SelectSubject AND (PostTitle LIKE   :SearchTitle OR Subject LIKE :SearchSubject OR UserName LIKE :SearchAuthor) GROUP BY Posts.PostID ORDER BY PostTitle ASC LIMIT ".(($current_page-1)*$limit).",".$limit;
        } elseif ($sort_by == "sortByAuthor") {
            $query = "SELECT Posts.PostID AS PostID, PostTitle, PostContent, PostTimestamp, ImagePath, posts.ImageID, UserName FROM Posts, PostSubject, Subjects, Images, Users WHERE Posts.UserID = Users.UserID AND Posts.ImageID = Images.ImageID AND Posts.PostID = PostSubject.PostID AND Subjects.SubjectID = PostSubject.SubjectID AND Subjects.SubjectID = :SelectSubject AND (PostTitle LIKE   :SearchTitle OR Subject LIKE :SearchSubject OR UserName LIKE :SearchAuthor) GROUP BY Posts.PostID ORDER BY Posts.UserID ASC LIMIT ".(($current_page-1)*$limit).",".$limit;
        }

        $statement = $db->prepare($query);
        $statement->bindValue(":SelectSubject", $Select, PDO::PARAM_INT);
    } else {
        if ($sort_by == "sortByTime") {
            $query = "SELECT Posts.PostID AS PostID, PostTitle, PostContent, PostTimestamp, ImagePath, posts.ImageID, UserName FROM Posts, PostSubject, Subjects, Images, Users WHERE Posts.UserID = Users.UserID AND Posts.ImageID = Images.ImageID AND Posts.PostID = PostSubject.PostID AND Subjects.SubjectID = PostSubject.SubjectID AND (PostTitle LIKE   :SearchTitle OR Subject LIKE :SearchSubject OR UserName LIKE :SearchAuthor) GROUP BY Posts.PostID ORDER BY PostTimestamp DESC LIMIT ".(($current_page-1)*$limit).",".$limit;
        } elseif ($sort_by == "sortByTitle") {
            $query = "SELECT Posts.PostID AS PostID, PostTitle, PostContent, PostTimestamp, ImagePath, posts.ImageID, UserName FROM Posts, PostSubject, Subjects, Images, Users WHERE Posts.UserID = Users.UserID AND Posts.ImageID = Images.ImageID AND Posts.PostID = PostSubject.PostID AND Subjects.SubjectID = PostSubject.SubjectID AND (PostTitle LIKE   :SearchTitle OR Subject LIKE :SearchSubject OR UserName LIKE :SearchAuthor) GROUP BY Posts.PostID ORDER BY PostTitle ASC LIMIT ".(($current_page-1)*$limit).",".$limit;
        } elseif ($sort_by == "sortByAuthor") {
            $query = "SELECT Posts.PostID AS PostID, PostTitle, PostContent, PostTimestamp, ImagePath, posts.ImageID, UserName FROM Posts, PostSubject, Subjects, Images, Users WHERE Posts.UserID = Users.UserID AND Posts.ImageID = Images.ImageID AND Posts.PostID = PostSubject.PostID AND Subjects.SubjectID = PostSubject.SubjectID AND (PostTitle LIKE   :SearchTitle OR Subject LIKE :SearchSubject OR UserName LIKE :SearchAuthor) GROUP BY Posts.PostID ORDER BY Posts.UserID ASC LIMIT ".(($current_page-1)*$limit).",".$limit;
        }

        $statement = $db->prepare($query);
    }

    // Build and prepare SQL String with :id placeholder parameter.
    
    $statement->bindValue(":SearchTitle", $Search, PDO::PARAM_STR);
    $statement->bindValue(":SearchSubject", $Search, PDO::PARAM_STR);
    $statement->bindValue(":SearchAuthor", $Search, PDO::PARAM_STR);

    $statement->execute();

    $query = "SELECT SubjectID, Subject FROM subjects";

    $subjectStatement = $db->prepare($query);

    $subjectStatement->execute();
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="styles/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script> 
	<title>The Watcher - Posts on mcu news, heroes, villains and movies</title>
</head>
<body>
	<div class="container">
		<div class="row">
			<?php require("nav.php"); ?>
		</div>
        <div class="row">
            <button class="accordion">Search</button>
            <div class="panel">
                <form action="posts.php" method="post">
                    <div class="mb-3 mt-3">
                        <label for="subjectSelect" class="form-label">Subject:</label>
                        <select class="form-select" name="subjectSelect" id="subjectSelect">
                            <option value="-1" selected>All Categories</option>
                            <?php while($row = $subjectStatement->fetch()): ?>
                                <option value="<?= $row['SubjectID'] ?>"><?= $row['Subject'] ?></option>
                            <?php endwhile ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="Search" class="form-label">Search: Article title, subjects, and authors!</label>
                        <div class="input-group">
                            <input class="form-control" type="text" name="Search" id="Search">
                            <button type="submit" name="Submit" class="btn btn-primary">Search</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2 col-lg-3 col-xl-2 form-check form-switch">
                            <input type="radio" class="form-check-input" id="sortByTime" name="optRadio" value="sortByTime" checked>
                            <label class="form-check-label" for="sortByTime">Sort By Time</label>
                        </div>
                        <div class="col-sm-2 col-lg-3 col-xl-2 form-check form-switch">
                            <input type="radio" class="form-check-input" id="sortByTitle" name="optRadio" value="sortByTitle">
                            <label class="form-check-label" for="sortByTitle">Sort By Title</label>
                        </div>
                        <div class="col-sm-2 col-lg-3 col-xl-2 form-check form-switch">
                            <input type="radio" class="form-check-input" id="sortByAuthor" name="optRadio" value="sortByAuthor">
                            <label class="form-check-label" for="sortByAuthor">Sort By Author</label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <p>Enter what you're looking for and how you would like to sort it</p>
                    </div>
                </form>
            </div>
        </div>
            <script>
                //Code for accordion feature on search form
                let accordion = document.getElementsByClassName("accordion");
                let i;

                for (let i = 0; i < accordion.length; i++) {
                  accordion[i].addEventListener("click", function() {
                    this.classList.toggle("active");
                    let panel = this.nextElementSibling;
                    if (panel.style.maxHeight) {
                      panel.style.maxHeight = null;
                    } else {
                      panel.style.maxHeight = panel.scrollHeight + "px";
                    } 
                  });
                }
            </script>
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
        	            <?php if($row['ImageID'] != 6): ?>
                            <img class="img-fluid" style="max-width: 100%;" src="<?= $row['ImagePath'] ?>" alt="<?= $row['PostTitle'] ?>">
                        <?php endif ?>
        	        </div>
        	        <div class="col-16 col-sm-16 col-md-16 col-lg-8 col-xl-8 col-xxl-9">
        	        	<div class="col-16 col-sm-16 col-md-16 col-lg-8 col-xl-8 col-xxl-9">
        	    	        <h3><?= $row['PostTitle'] ?></h3><p>Published: <?= $row['PostTimestamp'] ?></p>
        	        	</div>
        	        	<div class="col-16 col-sm-16 col-md-16 col-lg-8 col-xl-8 col-xxl-9">
                            <p>by: <?= $row['UserName'] ?></p>
                        </div>
                        <div class="col-16 col-sm-16 col-md-16 col-lg-8 col-xl-8 col-xxl-9">
        	        		<p style="max-height: 145px; overflow-y: hidden; text-overflow: ellipsis;"><?= $row['PostContent'] ?></p>
        	        	</div>
        	    	</div>
    			</div>
    		</a>
        <?php endwhile ?>
        <ul class="pagination justify-content-center">
        	<li class="page-item<?= $previous ?>"><a class="page-link" href="posts.php?page=<?= $current_page-1 ?>">Previous</a></li>
        	<?php for ($i=1; $i <= $page_count; $i++): ?>
        		<li class="page-item"><a class="page-link" href="posts.php?page=<?= $i ?>"><?= $i ?></a></li>
        	<?php endfor ?>
        	<li class="page-item<?= $next ?>"><a class="page-link" href="posts.php?page=<?= $current_page+1 ?>">Next</a></li>
        </ul>
	</div>
</body>
</html>