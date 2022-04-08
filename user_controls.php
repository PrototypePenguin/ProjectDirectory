<?php 
	session_start();

    require("db.php");
    require("values.php");


    if($_SESSION['role'] == $VALUES_administrator_id || $_SESSION['role'] == $VALUES_moderator_id){
        if ($_POST && !empty($_POST['user'])) {
            $query = "SELECT UserName, Email, Address, users.RoleID, RoleName FROM users, roles WHERE users.RoleID = roles.RoleID AND INSTR(UserName, :UserName) <> 0";


        }
        else {
            $query = "SELECT UserName, Email, Address, users.RoleID, RoleName FROM users, roles WHERE users.RoleID = roles.RoleID";
        }

        $statement = $db->prepare($query);
        
        if ($_POST && !empty($_POST['user'])) {
            $statement->bindValue(":UserName", $_POST['user'], PDO::PARAM_STR);
        }

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
    <div class="container">
        <div class="row">
            <?php require("nav.php"); ?>
        </div>
        <div class="row">
            <form action="user_controls.php" method="post">
                <div class="mb-3 mt-3">
                    <label for="user" class="form-label">User:</label>
                    <input type="text" name="user" id="user" class="form-control" placeholder="Find a specific user!">
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
        <div class="row">
            <div class="mb-3 mt-3">
                <h2 style="padding-top: 22px;">Users</h2>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>UserName</th>
                                <th>Email</th>
                                <th>Address</th>
                                <th>Role</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = $statement->fetch()): ?>
                                <tr>
                                        <td><a href="user_edit.php?UserName=<?= $row['UserName'] ?>" class="text-decoration-none text-body"><?= $row['UserName'] ?></a></td>
                                        <td><a href="user_edit.php?UserName=<?= $row['UserName'] ?>" class="text-decoration-none text-body"><?= $row['Email'] ?></a></td>
                                        <td><a href="user_edit.php?UserName=<?= $row['UserName'] ?>" class="text-decoration-none text-body"><?= $row['Address'] ?></a></td>
                                        <td><a href="user_edit.php?UserName=<?= $row['UserName'] ?>" class="text-decoration-none text-body"><?= $row['RoleName'] ?></a></td>
                                        <td><a href="user_edit.php?UserName=<?= $row['UserName'] ?>">edit</a></td>
                                </tr>
                            <?php endwhile ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
    </div>
 </body>
 </html>