<?php 
	session_start();

	require_once ('db.php');    //Contains database connection information
	require ('values.php');     //Contains constant values identified with VALUE_
	print_r($_COOKIE);
	if ($_COOKIE && isset($_COOKIE['ImageIDDelete'])) {
		$image_to_delete = filter_input(INPUT_COOKIE, 'ImageIDDelete', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

		$query = "SELECT * FROM images WHERE ImageID = :ImageID";
		$statement = $db->prepare($query);
		$statement->bindValue(":ImageID", $image_to_delete, PDO::PARAM_STR);
		$statement->execute();
		
		if ($statement->rowCount() == 1) {
			$quote = $statement->fetch();
		
				//ImageID 6 is a special entry in the images table that has a null ImagePath (ImageID:6 allows an image to be removed from a post without removing it from other posts.) and should only be added/deleted via the database.
				if ($image_to_delete != 6) {
					unlink($quote['ImagePath']);
					unlink(substr_replace($quote['ImagePath'], "_medium", strpos($quote['ImagePath'], '.'), 0));
					unlink(substr_replace($quote['ImagePath'], "_thumbnail", strpos($quote['ImagePath'], '.'), 0));

					$query = "DELETE FROM imagesubject WHERE ImageID = :ImageID";
					$statement = $db->prepare($query);
					$statement->bindValue(":ImageID", $image_to_delete, PDO::PARAM_INT);
					$statement->execute();
		
					$query = "DELETE FROM images WHERE ImageID = :ImageID";
					$statement = $db->prepare($query);
					$statement->bindValue(":ImageID", $image_to_delete);
					$statement->execute();

					$query = "UPDATE posts SET ImageID = 6 WHERE ImageID = :ImageID";
					$statement = $db->prepare($query);
					$statement->bindValue(":ImageID", $image_to_delete, PDO::PARAM_INT);
					$statement->execute();

					echo "<script>alert('success');</script>";
				}
		}
		elseif ($statement->rowCount() < 1) {
			echo "<script>alert('SQL query grabbed no data!');</script>";
		} else {
			echo "<script>alert('SQL query grabbed more than one row!');</script>";
		}
		
	} else {
		echo "<script>alert('No ImagePathDelete Cookie!');</script>";
	}
	

	//header("location: index.php");
 ?>