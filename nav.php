<?php 
	$address = "/WEBD-2008/Project/ProjectDirectory/";

	// Contents of navbar for one stop url edits
	$index 				= "index.php";
	$posts 				= "posts.php";
	$login 				= "login.php";
	$logout 			= "logout.php";
	$user_controls 		= "user_controls.php"; 		// Admin/Moderator only
	$subject_controls 	= "subject_controls.php";	// Admin/Moderator only
	$images				= "images.php";

	if (!isset($_SESSION['role'])) {
        $_SESSION['role'] = 0;
    }

    //Store current and last page for redirects on image and subject upload
	$url = $_SERVER['REQUEST_URI'];

	if(isset($_COOKIE) && isset($_COOKIE['Source'])) {
		setcookie("Destination", $_COOKIE['Source']);
	}
	setcookie("Source", basename($url));

	//Clears form cookies if not needed
	if (isset($_COOKIE) && isset($_COOKIE['Source'])) {
		if ($_COOKIE['Source'] != $images || $_COOKIE['Source'] != $subject_controls) {
		setcookie("PostTitle", "");
		setcookie("PostCategory", "");
		setcookie("PostDesc", "");
		setcookie("PostSubject", "");
		setcookie("PostContent", "");
		}
	}
 ?>
<div class="col-sm-6 p-3">
	<img alt="The Watcher" src="images/TheWatcher.png" style="max-width: 100%; height: auto;">
</div>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container-fluid">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
	    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
			<?php if($address.$index != $url): // All Posts ?>
				<li class="nav-item">
					<a class="nav-link" href="<?= $index ?>" >Home</a>
				</li>
			<?php else: ?>
				<li class="nav-item">
					<a class="nav-link Disabled rounded-3" style="background-color: lightgrey;" href="<?= $index ?>">Home</a>
				</li>
			<?php endif ?>
			<?php if($address.$posts != $url): // All Posts ?>
				<li class="nav-item">
					<a class="nav-link" href="<?= $posts ?>" >Posts</a>
				</li>
			<?php else: ?>
				<li class="nav-item">
					<a class="nav-link Disabled rounded-3" style="background-color: lightgrey;" href="<?= $posts ?>">Posts</a>
				</li>
			<?php endif ?>

			<?php if($_SESSION['role'] == 1 || $_SESSION['role'] == 2): // Admin User settings ?>
				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
						Admin Controls
					</a>
					<ul class="dropdown-menu" aria-labelledby="navbarDropdown">
						<li><a class="dropdown-item" href="<?= $user_controls ?>">User Controls</a></li>
						<li><a class="dropdown-item" href="<?= $subject_controls ?>">Subject Controls</a></li>
						<?php if($address.$images != $url): ?>
							<li><a class="dropdown-item" href="images.php">images</a></li>
						<?php endif ?>
					</ul>
				</li>
			<?php endif ?>
		</ul>
		<?php if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): // Login / Logout ?>
			<ul class="navbar-nav ms-auto">
				<li class="nav-item dropdown">
        			<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
        				<?= htmlspecialchars($_SESSION["username"]) ?>
    				</a>
        			<ul class="dropdown-menu" aria-labelledby="navbarDropdown">
        	  			<li><a class="dropdown-item" href="logout.php">Logout</a></li>
        	  			<li><a class="dropdown-item" href="reset-password.php">Reset Password</a></li>
        			</ul>
        		</li>
			</ul>
		<?php else: ?>
			<ul class="navbar-nav ms-auto">
				<li class="nav-item"><a class="nav-link" href="<?= $login ?>">Sign In</a></li>
			</ul>
		<?php endif ?>
    </div>
  </div>
</nav>