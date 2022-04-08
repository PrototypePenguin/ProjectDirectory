<?php 
	session_start();

    require("db.php");
    require("values.php");

    if($_SESSION['role'] == $VALUES_administrator_id || $_SESSION['role'] == $VALUES_moderator_id){
        $query = "SELECT * FROM Users"
        $statement = $db->prepare($query);
    
        $statement->execute();
    } 
    else {
        header("location: login.php");
    }
 ?>

 <!DOCTYPE html>
 <html>
 <head>
 	<meta charset="utf-8">
 	<meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="styles\styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script> 
 	<title>Admin User Controls</title>
 </head>
 <body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                
            </div>
        </div>
        
    </div>
 </body>
 </html>