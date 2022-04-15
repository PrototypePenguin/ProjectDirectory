<?php 
/******************************************************************
 * Author:  Charles Burns
 * Date:    Mar 24, 2022
 * Purpose: A HTML form that allows the user to enter information
 * and Posts it to the insert page
 ******************************************************************/
   session_start();
   
   require_once ('db.php');   //Contains database connection information
   require ('values.php');      //Contains constant values identified with VALUE_

   // If there is not user logged in nav.php will set the $_SESSION['role'] to 0
   // Prevent the user from accessing the page if they do not have permission
   if ($_SESSION['role'] == 0 || $_SESSION['role'] == $VALUES_user_id || $_SESSION['role'] == $VALUES_editor_id) {
      header("location: index.php");
   }

   $query = "SELECT * FROM images";

   $statement_img = $db->prepare($query);

   $statement_img->execute();

   $query = "SELECT * FROM subjects";

   $statement_subject = $db->prepare($query);

   $statement_subject->execute();
 ?>

 <!DOCTYPE html>
 <html lang="en">
    <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
      <link rel="stylesheet" type="text/css" href="styles/styles.css">
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script> 
    	<title>New Post - The Watcher</title>
    </head>
    <body>
      <div class="container">
         <div class="row">
            <?php include("nav.php"); ?>
         </div>
         <div class="row">
            <div class="col-sm-6">
               <h2>Enter you next blog post!</h2>
            </div>
         </div>
         <div class="row">
            <form action="insert.php" method="post">
               <div class="mb-3 mt-3">
                  <label class="form-label" for="PostTitle">Title:</label>
                  <input class="form-control" type="text" id="PostTitle" name="PostTitle" value="<?= (isset($_COOKIE['PostTitle']) ? $_COOKIE['PostTitle'] : "") ?>" autofocus>
               </div>
               <div class="mb-3 mt-3">
                  <label class="form-label" for="PostCategory">Post Type:</label>
                  <input class="form-control" type="text" id="PostCategory" name="PostCategory" value="<?= (isset($_COOKIE['PostCategory']) ? $_COOKIE['PostCategory'] : "") ?>">
               </div>
               <div class="mb-3 mt-3">
                  <label class="form-label" for="PostDesc">Post Description:</label>
                  <input class="form-control" type="text" id="PostDesc" name="PostDesc" value="<?= (isset($_COOKIE['PostDesc']) ? $_COOKIE['PostDesc'] : "") ?>">
               </div>
               <div class="mb-3 mt-3">
                  <label class="form-label" for="PostContent">Content:</label>
                  <textarea class="form-control" id="PostContent" name="PostContent" rows="5" cols="50"> <?= (isset($_COOKIE['PostContent']) ? $_COOKIE['PostContent'] : "") ?></textarea>
               </div>
               <div class="mb-3">
                  <label for="PostSubject" class="form-label">Subject:</label>
                  <div class="input-group">
                     <button class="btn btn-primary" type="button" onclick="Subject()">New Subject</button>
                     <script>
                        function Subject() {
                           //Keeps items from disapearing when user decides to add an image.
                           document.cookie = "PostTitle=" + document.getElementById("PostTitle").value + ";";
                           document.cookie = "PostCategory=" + document.getElementById("PostCategory").value + ";";
                           document.cookie = "PostDesc=" + document.getElementById("PostDesc").value + ";";
                           document.cookie = "PostContent=" + document.getElementById("PostContent").value + ";";
                           document.cookie = "Source=new_post.php";

                           window.location = "subject_controls.php";
                        }
                     </script>
                     <select multiple class="form-select" name="PostSubject[]">
                        <?php while ($row = $statement_subject->fetch()): ?>
                           <option value="<?= $row['SubjectID'] ?>" id="PostSubject"><?= $row['Subject'] ?></option>
                        <?php endwhile ?>
                     </select>
                  </div>
               </div>
               <div class="mb-3 mt-3">
                  <label class="form-label" for="ImageID">Image:</label>
                  <div class="input-group">
                     <button class="btn btn-primary" type="button" onclick="Image()">Upload New Image</button>
                     <script>
                        function Image() {
                           //Keeps items from disapearing when user decides to add an image.
                           document.cookie = "PostTitle=" + document.getElementById("PostTitle").value + ";";
                           document.cookie = "PostCategory=" + document.getElementById("PostCategory").value + ";";
                           document.cookie = "PostDesc=" + document.getElementById("PostDesc").value + ";";
                           document.cookie = "PostSubject=" + document.getElementById("PostSubject").value + ";";
                           document.cookie = "PostContent=" + document.getElementById("PostContent").value + ";";
                           document.cookie = "Source=new_post.php";

                           window.location = "images.php";
                        }
                     </script>
                     <select class="form-select" name="ImageID">
                        <?php while($row = $statement_img->fetch()): ?>
                           <option value="<?= $row['ImageID'] ?>" id="ImageID"><?= substr($row['ImagePath'], strpos($row['ImagePath'], '/')+1) ?></option>
                        <?php endwhile ?>
                     </select>
                  </div>
               </div>
               <input class="form-control" id="UserID" type="hidden" name="UserID" value="<?= $_SESSION['id'] ?>">
               <input type="submit" value="Submit">
            </form>
            <script>
                  //Delete Cookies after page has loaded so that the page does not perpetually have the same data.
                  document.cookie = "PostTitle=";
                  document.cookie = "PostCategory=";
                  document.cookie = "PostDesc=";
                  document.cookie = "PostSubject=";
                  document.cookie = "PostContent=";
                  document.cookie = "Source=";
            </script>
         </div>
      </div>
      <?php if($_SESSION['form_success'] != false): ?>
         <div class="alert alert-success alert-dismissible fixed-bottom">
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            <strong>Success!</strong> Your <?= ($_SESSION['form_success'] == "images" ? "image" : "") ?> <?= ($_SESSION['form_success'] == "subject" ? "subject" : "") ?> was successfully uploaded.
         </div>
      <?php endif ?>
      <?php $_SESSION['form_success'] = false; ?>
    </body>
 </html>