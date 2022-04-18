<?php 
	$comment = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

	$query = "INSERT INTO comments (PostID, UserID, CommentContent, CommentTimestamp) VALUES (:PostID, :UserID, :Comment, :CommentTimestamp)";


?>
<div class="row">
	<form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
		<div class="mb-3 mt-3">
			<label for="comment" class="form-label">Leave a comment:</label>
			<input type="text" name="comment" class="form-control" id="comment">
		</div>
	</form>
</div>