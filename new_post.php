<?php 
/******************************************************************
 * Author:  Charles Burns
 * Date:    Mar 24, 2022
 * Purpose: A HTML form that allows the user to enter information
 * and Posts it to the insert page
 ******************************************************************/
 ?>

 <!DOCTYPE html>
 <html lang="en">
 <head>
 	<meta charset="utf-8">
 	<meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="style.css">
 	<title>New Post</title>
 </head>
 <body>
 	<div class="nav">
 		<a href="index.php">Return to Home</a>
 	</div>
 	<div class="form">
 		<h2>Enter you next blog post!</h2>
 		<form action="insert.php" method="post">
         <label for="UserID">(Temp) User*</label>
         <input type="text" name="UserID">
         <label for="PostType">Post Type</label>
         <input type="text" name="PostType">
         <label for="PostDesc">PostDesc</label>
         <input type="text" name="PostDesc">
         <label for="PostSubject">PostSubject</label>
         <input type="text" name="PostSubject">
 			<label for="PostTitle">Title*</label>
 			<input type="text" id="PostTitle" name="PostTitle" autofocus>
 			<label for="PostContent">Content*</label>
 			<textarea id="PostContent" name="PostContent" rows="5" cols="50"></textarea>
 			<input type="submit" value="Submit">
 		</form>
      <p>* input is required</p>
 	</div>
 </body>
 </html>