<?php 
	session_start();

    require_once ('db.php');    //Contains database connection information
    require ('values.php');     //Contains constant values identified with VALUE_


    if($_SESSION['role'] == $VALUES_administrator_id || $_SESSION['role'] == $VALUES_moderator_id){
        if ($_POST && !empty($_POST['subject'])) {
            $query = "SELECT * FROM subjects WHERE INSTR(Subject, :Subject) <> 0";


        }
        else {
            $query = "SELECT * FROM subjects";
        }

        $statement = $db->prepare($query);
        
        if ($_POST && !empty($_POST['subject'])) {
            $subject_query = filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $statement->bindValue(":Subject", $subject_query, PDO::PARAM_STR);
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
 	<title>Admin Subject Controls</title>
 </head>
 <body>
    <div class="container">
        <div class="row">
            <?php require("nav.php"); ?>
        </div>
        <div class="row">
            <form action="subject_controls.php" method="post">
                <div class="mb-3 mt-3">
                    <label for="subject" class="form-label">Subject:</label>
                    <input type="text" name="subject" id="subject" class="form-control" placeholder="Find a specific subject!">
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
        <div class="row">
            <div class="mb-3 mt-3">
                <h2 style="padding-top: 22px;">Subjects<?= ( !isset($_POST['$subject']) ? "" : ": Containing \"".$subject_query."\"") ?></h2>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>SubjectID</th>
                                <th>Subject</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = $statement->fetch()): ?>
                                <tr>
                                        <td><a href="subject_edit.php?SubjectID=<?= $row['SubjectID'] ?>" class="nav-link text-decoration-none text-body"><?= $row['SubjectID'] ?></a></td>
                                        <td><a href="subject_edit.php?SubjectID=<?= $row['SubjectID'] ?>" class="nav-link text-decoration-none text-body"><?= $row['Subject'] ?></a></td>
                                        <td><a href="subject_edit.php?SubjectID=<?= $row['SubjectID'] ?>">edit</a></td>
                                </tr>
                            <?php endwhile ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row mb-3">
    		<form action="subject_insert.php" method="post">
    			<div class="mb-3 mt-3">
    				<label for="new_subject" class="form-label">New Subject:</label>
    				<input type="text" name="new_subject" id="new_subject" class="form-control" placeholder="New Subject">
    			</div>
    			<button type="submit" class="btn btn-primary">Submit</button>
    		</form>
        </div>
        <?php if(isset($_SESSION['form_success']) && $_SESSION['form_success'] == true): ?>
            <div class="alert alert-success alert-dismissible fixed-bottom">
                <button type="button" class="btn-close" data-bs-dismiss="alert" onclick="window.close()"></button>
                <strong>Success!</strong> Your image was successfully uploaded.
            </div>
        <?php endif ?>
        <?php $_SESSION['form_success'] = false; ?>
    </div>
 </body>
 </html>