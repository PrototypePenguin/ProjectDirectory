<?php
	session_start();

	require_once ('db.php');    //Contains database connection information
   require ('values.php');     //Contains constant values identified with VALUE_

	require 'C:\xampp\htdocs\a\php-image-resize-master\lib\ImageResize.php';
	require 'C:\xampp\htdocs\a\php-image-resize-master\lib\ImageResizeException.php';

	$success = false;	//success will become true if image uploads successfully

	use \Gumlet\ImageResize;
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
			if (move_uploaded_file($_FILES['image']['tmp_name'], $target_image)) {
				// If not a pdf create thumbnails and low res versions
				if ($image_file_type != "pdf") {

					$image = new ImageResize($target_image);
					
					// Chained image resize
					$image
						// Create a smaller version of the image
						->resizeToWidth(400)
						->save($target_directory . $_FILES['image']['name'] . "_medium." . $image_file_type)

						// Create a version of the image for thumbnails
						->resizeToWidth(50)
						->save($target_directory . $_FILES['image']['name'] . "_thumbnail." . $image_file_type)
					;

					//Add Image info to database
					$query = "INSERT INTO images (ImagePath) VALUES (:ImagePath)";

					$statement = $db->prepare($query);

					$statement->bindValue(":ImagePath", $target_directory.$_FILES['image']['name'], PDO::PARAM_STR);

					$statement->execute();

					$_SESSION['form_success'] = true;

					// Return to the page that called the form
					if (!isset($_COOKIE['Source'])) {
						header("location: index.php");
					}
					else {
						header("location: ".$_COOKIE['Source']);
					}
					
				}
			} else {
    			// Failed Upload Error handling
    			$error_message = "ERR:Upload Failed";
  			}
		} else {
			// Illegal Upload Error handling
			$error_message = "ERR:Illegal Upload of type: " . $_FILES['image']['type'];
		}
	}
 ?>

 <!DOCTYPE html>
 <html lang="en">
 <head>
 	<meta charset="utf-8">
 	<meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="styles\styles.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script> 
 	<title>Image Upload</title>
 </head>
 <body>
 	<div class="container">
 		<div class="row">
 			<div class="col-sm-6">
 				<h2>Image Upload</h2>
 			</div>
 		</div>
 		<div class="row">
 			<p>Upload an image that is one of a gif, jpg, webp, or png.</p>
	 		<form method="post" action="images.php" enctype="multipart/form-data">
	 			<label class="form-label" id="image">Image</label>
	 			<input class="form-control" type="file" name="image">
	 			<button class="form-control" type="submit" name="submit">Submit</button>
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