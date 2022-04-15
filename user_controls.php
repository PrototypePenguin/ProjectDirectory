<?php 
	session_start();

    require_once ('db.php');    //Contains database connection information
    require ('values.php');     //Contains constant values identified with VALUE_


    if($_SESSION['role'] == $VALUES_administrator_id || $_SESSION['role'] == $VALUES_moderator_id){
        if ($_POST && !empty($_POST['user'])) {
            $query = "SELECT UserID, UserName, Email, Address, users.RoleID, RoleName FROM users, roles WHERE users.RoleID = roles.RoleID AND INSTR(UserName, :UserName) <> 0";


        }
        else {
            $query = "SELECT UserID, UserName, Email, Address, users.RoleID, RoleName FROM users, roles WHERE users.RoleID = roles.RoleID";
        }

        $statement = $db->prepare($query);
        
        if ($_POST && !empty($_POST['user'])) {
            $user_query = filter_input(INPUT_POST, 'user', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $statement->bindValue(":UserName", $user_query, PDO::PARAM_STR);
        }

        $statement->execute();
    } 
    else {
        header("location: login.php");
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
 	<title>Admin User Controls - The Watcher</title>
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
                <h2 style="padding-top: 22px;">Users<?= ( !isset($_POST['user']) ? "" : ": Containing \"".$user_query."\"") ?></h2>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>UserName</th>
                                <th>Email</th>
                                <th>Address</th>
                                <th>Role</th>
                                <th> </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = $statement->fetch()): ?>
                                <tr>
                                        <td><a class="nav-link text-decoration-none text-body" href="user_edit.php?UserID=<?= $row['UserID'] ?>"><?= $row['UserName'] ?></a></td>
                                        <td><a class="nav-link text-decoration-none text-body" href="user_edit.php?UserID=<?= $row['UserID'] ?>"><?= $row['Email'] ?></a></td>
                                        <td><a class="nav-link text-decoration-none text-body" href="user_edit.php?UserID=<?= $row['UserID'] ?>"><?= $row['Address'] ?></a></td>
                                        <td><a class="nav-link text-decoration-none text-body" href="user_edit.php?UserID=<?= $row['UserID'] ?>"><?= $row['RoleName'] ?></a></td>
                                        <td><a class="nav-link" href="user_edit.php?UserID=<?= $row['UserID'] ?>">edit</a></td>
                                </tr>
                            <?php endwhile ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
            <?php if(isset($_SESSION['form_success']) && $_SESSION['form_success'] == true): ?>
            <div class="row">
                <div class="col-sm-6">
                    <div class="alert alert-success alert-dismissible fixed-bottom">
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        <strong>Success!</strong> Your user was successfully edited.
                    </div>
                </div>
            </div>
        <?php endif ?>
        <?php $_SESSION['form_success'] = false; ?>
    </div>  
 </body>
 </html>