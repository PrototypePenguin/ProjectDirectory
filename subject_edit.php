<?php 
	session_start();

	require_once ('db.php');	//Contains database connection information
    require ('values.php');		//Contains constant values identified with VALUE_

	if($_SESSION['role'] == $VALUES_administrator_id || $_SESSION['role'] == $VALUES_moderator_id){
		$query = "SELECT * FROM subjects WHERE :SubjectID = SubjectID";

		$statement = $db->prepare($query);

		$statement->bindValue(":SubjectID", $_GET['SubjectID'], PDO::PARAM_STR);

		$statement->execute();

		$quote = $statement->fetch();

		$query = "SELECT * FROM roles";

		$row_statement = $db->prepare($query);

		$row_statement->execute();
	} 
    else {
        header("location: index.php");
    }
 ?>

 <!DOCTYPE html>
 <html lang="en">
 <head>
 	<meta charset="utf-8">
 	<meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="styles/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script> 
 	<title>Edit <?= $_GET['SubjectID'] ?> - The Watcher</title>
 </head>
 <body>
 	<div class="container">
 		<div class="row">
			<form action="subject_update.php" method="post">
 				<div class="mb-3 mt-3">
 					<label for="Subject" class="form-label">Subject:</label>
 					<input type="text" name="Subject" class="form-control" id="Subject" value="<?= $quote['Subject'] ?>">
 				</div>
 				<button type="submit" class="btn btn-primary">Submit</button>
 				<div class="mb-3 mt-3 invisible">
 					<label for="SubjectID" class="form-label invisible">SubjectID:</label>
 					<input type="text" name="SubjectID" class="form-control invisible" id="SubjectID" value="<?= $_GET['SubjectID'] ?>" readonly>
 				</div>
			</form>
		</div>
 	</div>
 </body>
 </html>