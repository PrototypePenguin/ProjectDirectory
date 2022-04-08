<?php 
	session_start();

	require("db.php");
	require("values.php");

	if($_SESSION['role'] == $VALUES_administrator_id || $_SESSION['role'] == $VALUES_moderator_id){
		$query = "SELECT UserName, Email, Address, RoleID FROM users WHERE :UserName = UserName";

		$statement = $db->prepare($query);

		$statement->bindValue(":UserName", $_GET['UserName'], PDO::PARAM_STR);

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
 <html>
 <head>
 	<meta charset="utf-8">
 	<meta name="viewport" content="width=device-width, initial-scale=1">
 	<link rel="stylesheet" type="text/css" href="styles\styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script> 
 	<title>Edit <?= $_GET['UserName'] ?></title>
 </head>
 <body>
 	<div class="container">
 		<div class="row">
 			<div class="col-sm-6">
 				<?php require("nav.php"); ?>
 			</div>
 		</div>
 		<div class="row">
			<form action="user_update.php" method="post">
 				<div class="mb-3 mt-3">
 					<label for="UserName" class="form-label">UserName:</label>
 					<input type="text" name="UserName" class="form-control" id="UserName" value="<?= $quote['UserName'] ?>">
 				</div>
				<div class="mb-3 mt-3">
 					<label for="Email" class="form-label">Email:</label>
 					<input type="text" name="Email" class="form-control" id="Email" value="<?= $quote['Email'] ?>">
 				</div>
				<div class="mb-3 mt-3">
 					<label for="Address" class="form-label">Address:</label>
 					<input type="text" name="Address" class="form-control" id="Address" value="<?= $quote['Address'] ?>">
 				</div>
 				<div class="mb-3 mt-3">
 					<label for="Role" class="form-label">Role:</label>
 					<select class="form-select">
 						<?php while($row = $row_statement->fetch()): ?>
 							<option value="<?= $row['RoleName'] ?>" <?php if($quote['RoleID'] == $row['RoleID']): ?>selected<?php endif ?>><?= $row['RoleName'] ?></option>
 						<?php endwhile ?>
 					</select>
 				</div>
 				<div class="form-check">
 					<input class="form-check-input" type="checkbox" id="reset-password" name="reset-password" value="something">
 					<label class="form-check-label" style="padding-bottom: 8px;">Reset Password</label>
 				</div>
 				<button type="submit" class="btn btn-primary">Submit</button>
			</form>
		</div>
 	</div>
 </body>
 </html>