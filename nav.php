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

	setcookie("Destination", $_COOKIE['Source']);
	setcookie("Source", basename($url));

	//Clears form cookies if not needed
	if (isset($_COOKIE['Source']) && $_COOKIE['Source'] != $images || $_COOKIE['Source'] != $subject_controls) {
		setcookie("PostTitle", "");
		setcookie("PostCategory", "");
		setcookie("PostDesc", "");
		setcookie("PostSubject", "");
		setcookie("PostContent", "");
	}
 ?>
<nav class="navbar navbar-expand-sm bg-light">
	<div class="container-fluid">
		<ul class="navbar-nav">
			<?php if($address.$index != $url): // Home Page ?>
				<li>
					<a class="nav-link" href="<?= $index ?>" >Home</a>
				</li>
			<?php else: ?>
				<li>
					<a class="nav-link disabled" href="#">Home</a>
				</li>
			<?php endif ?>
			<?php if($address.$posts != $url): // All Posts ?>
				<li>
					<a class="nav-link" href="<?= $posts ?>" >Posts</a>
				</li>
			<?php else: ?>
				<li>
					<a class="nav-link disabled" href="#">Posts</a>
				</li>
			<?php endif ?>
			
			<?php if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): // Login / Logout ?>
				<?php if($address.$login != $url): ?>
					<li>
						<a class="nav-link" href="<?= $logout ?>">Logout</a>
					</li>
				<?php else: ?>
					<li>
						<a class="nav-link disabled" href="#">Logout</a>
					</li>
				<?php endif ?>
			<?php else: ?>
				<?php if($address.$login != $url): ?>
					<li>
						<a class="nav-link" href="<?= $login ?>">Login</a>
					</li>
				<?php else: ?>
					<li>
						<a class="nav-link disabled" href="#">Login</a>
					</li>
				<?php endif ?>
			<?php endif ?>

			<?php if($_SESSION['role'] == 1 || $_SESSION['role'] == 2): // Admin User settings ?>
				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#">Admin Controls</a>
					<ul class="dropdown-menu">
						<?php if($address.$user_controls != $url): ?>
							<li>
								<a class="nav-link" href="<?= $user_controls ?>">User Controls</a>
							</li>
						<?php else: ?>
							<li>
								<a class="nav-link disabled" href="#">User Controls</a>
							</li>
						<?php endif ?>
						<?php if($address.$subject_controls != $url): ?>
							<li>
								<a class="nav-link" href="<?= $subject_controls ?>">Subject Controls</a>
							</li>
						<?php else: ?>
							<li>
								<a class="nav-link disabled" href="#">Subject Controls</a>
							</li>
						<?php endif ?>
						<?php if($address.$images != $url): ?>
							<li>
								<a class="nav-link" href="images.php">images</a>
							</li>
						<?php endif ?>
					</ul>
				</li>
				
			<?php endif ?>
		</ul>
		
	</div>
</nav>