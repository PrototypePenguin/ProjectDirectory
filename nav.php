<?php 
	$address = "/WEBD-2008/Project/ProjectDirectory/";

	// Contents of navbar for one stop url edits
	$index = "index.php";
	$login = "login.php";
	$logout = "logout.php";
	$user_controls = "user_controls.php"; // Admin/Moderator only

	if (!isset($_SESSION['role'])) {
        $_SESSION['role'] = 0;
    }
 ?>
<nav class="navbar navbar-expand-sm bg-light">
	<div class="container-fluid">
		<ul class="navbar-nav">
			<?php if($address.$index != $_SERVER['REQUEST_URI']): // Home Page ?>
				<li>
					<a class="nav-link" href="<?= $index ?>" >Home</a>
				</li>
			<?php else: ?>
				<li>
					<a class="nav-link" href="#">Home</a>
				</li>
			<?php endif ?>
			
			<?php if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): // Login / Logout ?>
				<?php if($address.$login != $_SERVER['REQUEST_URI']): ?>
					<li>
						<a class="nav-link" href="<?= $logout ?>">Logout</a>
					</li>
				<?php else: ?>
					<li>
						<a class="nav-link" href="#">Logout</a>
					</li>
				<?php endif ?>
			<?php else: ?>
				<?php if($address.$login != $_SERVER['REQUEST_URI']): ?>
					<li>
						<a class="nav-link" href="<?= $login ?>">Login</a>
					</li>
				<?php else: ?>
					<li>
						<a class="nav-link" href="#">Login</a>
					</li>
				<?php endif ?>
			<?php endif ?>

			<?php if($_SESSION['role'] == 1 || $_SESSION['role'] == 5): // Admin User settings ?>
				<?php if($address.$user_controls != $_SERVER['REQUEST_URI']): ?>
					<li>
						<a class="nav-link" href="<?= $user_controls ?>">User Controls</a>
					</li>
				<?php else: ?>
					<li>
						<a class="nav-link" href="#">User Controls</a>
					</li>
				<?php endif ?>
			<?php endif ?>
		</ul>
		
	</div>
</nav>