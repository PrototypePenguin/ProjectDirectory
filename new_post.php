<?php 
/******************************************************************
 * Author:  Charles Burns
 * Date:    Jan 31, 2022
 * Purpose: A HTML form that allows the user to enter information
 * and Posts it to the insert page
 ******************************************************************/
	require ("authenticate.php"); // Page only available to logged in users.
	require ("connect.php");
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
 		<a href="home.php">Return to Home</a>
 	</div>
 	<div class="form">
 		<h2>Enter you next blog post!</h2>
 		<form action="insert.php" method="post">
 			<label for="title">Title</label>
 			<input type="text" id="title" name="title" autofocus>
 			<label for="content">Content</label>
 			<textarea id="content" name="content" rows="5" cols="50"></textarea>
 			<input type="submit" value="Submit">
 		</form>
 	</div>
 </body>
 </html>