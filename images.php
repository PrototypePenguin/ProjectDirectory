<?php
// WEBD-2008 Challenge 7 Part 3
// By: Charles Burns
// March 11 2022
	session_start();

   require_once ('db.php');    //Contains database connection information
   require ('values.php');     //Contains constant values identified with VALUE_

	// Checks if the upload matches required file types
	function filetype_check($temporary_path, $new_path) {
		$allowed_mime_types			= ['image/gif', 'image/jpg', 'image/jpeg', 'image/png', 'image/webp'];
		$allowed_file_extensions	= ['gif', 'jpg', 'jpeg', 'png', 'webp'];

		$file_extension				= pathinfo($new_path, PATHINFO_EXTENSION);
		if ($file_extension != 'gif' && $file_extension != 'jpg' && $file_extension != 'jpeg' && $file_extension != 'png' && $file_extension != 'webp') {
			$mime_type = $_FILES['image']['type'];
		} else {
			$mime_type = getimagesize($temporary_path)['mime'];
		}

		$file_extension_is_valid = in_array($file_extension, $allowed_file_extensions);
		$mime_type_is_valid		 = in_array($mime_type, $allowed_mime_types);

		return $file_extension_is_valid && $mime_type_is_valid;
	}

	// Returns the location of the file after upload
	function file_upload_path($file_name, $directory) {
		$current_folder = dirname(__FILE__);
		$path_segments = [$current_folder, $directory, basename($file_name)];

		return join(DIRECTORY_SEPARATOR, $path_segments);
	}

	$upload_status = 1;

	if (isset($_POST['submit'])) {

		// Sets the image and the location to save the image
		$target_directory = "images/";
		$target_image = $target_directory . basename($_FILES['image']['name']);
		$image_filename = $_FILES['image']['name'];
		$image_path = file_upload_path($image_filename, "images");

		// Grabs the file extension of the image
		$image_file_type = strtolower(pathinfo($target_image, PATHINFO_EXTENSION));

		// Check if uploaded file is a valid image
		$check = filetype_check($_FILES['image']['tmp_name'], $image_path);
		
		// If the image is not valid cancel upload
		// If it is a pdf continue anyways
		if ($check === false) {
			$upload_status = 0;
		}

		if ($upload_status === 1) {
			//Check whether to grab existing ID or to create new one
			if (isset($_POST['Subject'])) {
				$subject = filter_input(INPUT_POST, 'Subject', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

				$query = "SELECT SubjectID FROM subjects WHERE Subject = :Subject";

				$statement = $db->prepare($query);

				$statement->bindValue(':Subject', $subject, PDO::PARAM_STR);

				$statement->execute();

				if ($statement->rowCount() == 1) {
					$subject_id = $statement->fetch()['SubjectID'];

				} elseif ($statement->rowCount() == 0) {
					$query = "INSERT INTO subjects (Subject) VALUES (:Subject)";

					$statement = $db->prepare($query);

					$statement->bindValue(':Subject', $subject, PDO::PARAM_STR);

					$statement->execute();

					$query = "SELECT SubjectID FROM subjects WHERE Subject = (:Subject)";

					$statement = $db->prepare($query);

					$statement->bindValue(':Subject', $subject, PDO::PARAM_STR);

					$statement->execute();

					$subject_id = $statement->fetch()['SubjectID'];
				} else {
					//Error for multiple subjects returned will be set in Add Files if/else block
					
					$subject_id = -1;
				}

				//Determine the orientation of a file to help maintain site cohesion
				$image_size = getimagesize($_FILES['image']['tmp_name']);

				if ($image_size[0] / $image_size[1] > .6 && $image_size[0] / $image_size[1] < .8) {
					$image_orientation = "portrait";
				} elseif ($image_size[0] / $image_size[1] > 1.3 && $image_size[0] / $image_size[1] < 2.5) {
					$image_orientation = "landscape";
				} elseif ($image_size[0] / $image_size[1] > .6 && $image_size[0] / $image_size[1] < 1.5) {
					$image_orientation = "square";
				} else {
					$image_orientation = "extreme";
				}

				//Add Files
				if (move_uploaded_file($_FILES['image']['tmp_name'], $target_image) && $subject_id != -1) {
					
					$query = "INSERT INTO images (ImagePath, SubjectID, ImageOrientation) VALUES (:ImagePath, :SubjectID, :ImageOrientation)";

					$statement = $db->prepare($query);

					$statement->bindValue(':ImagePath', $target_directory.$image_filename, PDO::PARAM_STR);
					$statement->bindValue(':SubjectID', $subject_id, PDO::PARAM_INT);
					$statement->bindValue(':ImageOrientation', $image_orientation, PDO::PARAM_STR);

					$statement->execute();
				} elseif($subject_id == -1) {
					$error_message = "DB_ERR:Multiple subject entries. Please contact your administrator to correct.";
				} else {
	    			// Failed Upload Error handling
	    			$error_message = "ERR:Upload Failed";
	  			}
			}
		} else {
			// Illegal Upload Error handling
			$error_message = "ERR:Illegal Upload of type: " . $_FILES['image']['type'];
		}
	}

	$query = "SELECT * FROM subjects";

	$statement = $db->prepare($query);

	$statement->execute();
 ?>

 <!DOCTYPE html>
 <html lang="en">
 <head>
 	<meta charset="utf-8">
 	<meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="styles\styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script> 
 	<title>File Uploads Part 3</title>
 </head>
 <body>
 	<div class="container">
 		<div class="row">
		 	<p>Upload an image that is one of a gif, jpg, webp, or png.</p>
		 	<form method="post" action="images.php" enctype="multipart/form-data">
		 		<div class="mb-3 mt-3">
			 		<label for="image" class="form-label">Image:</label>
			 		<input type="file" name="image" id="image" class="form-control">
			 	</div>
			 	<div class="mb-3 mt-3">
			 		<label for="Subject" class="form-label">Subject:</label>
			 		<input class="form-control" list="Subjects" name="Subject" id="Subject">
			 		<datalist id="Subjects">
			 			<?php while ($row = $statement->fetch()): ?>
			 				<option value="<?= $row['Subject'] ?>"><?= $row['Subject'] ?></option>
			 			<?php endwhile ?>
			 		</datalist>
			 	</div>
			 	<button class="btn btn-primary" type="submit" name="submit" value="Upload Image">Submit</button>
		 	</form>
		 	<?php if($upload_status === 0): ?>
		 		<p style="color: red;">
		 			<?= $error_message ?>
		 		</p>
		 	<?php endif ?>
	 	</div>
 	</div>
 </body>
 </html>