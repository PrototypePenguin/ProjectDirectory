<?php 
	$address = "/WEBD-2008/Project/ProjectDirectory/";

	// Contents of navbar for one stop url edits
	$index = "index.php";
	$login = "login.php";
	$logout = "logout.php";

	if (!isset($_SESSION['role'])) {
        $_SESSION['role'] = 0;
    }
 ?>
<nav>
	<div>
		<?php if($address.$index != $_SERVER['REQUEST_URI']): // Home Page ?>
			<a href="<?= $index ?>" >Home</a>
		<?php else: ?>
			<a href="#">Home</a>
		<?php endif ?>
		
		<?php if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): // Login / Logout ?>
			<?php if($address.$login != $_SERVER['REQUEST_URI']): ?>
				<a href="<?= $logout ?>">Logout</a>
			<?php else: ?>
				<a href="#">Logout</a>
			<?php endif ?>
		<?php else: ?>
			<?php if($address.$login != $_SERVER['REQUEST_URI']): ?>
				<a href="<?= $login ?>">Login</a>
			<?php else: ?>
				<a href="#">Login</a>
			<?php endif ?>
		<?php endif ?>

		<?php if($_SESSION['role'] == 1 || $_SESSION['role'] == 5): // Admin User settings ?>
			<?php if($address.$user_controls != $_SERVER['REQUEST_URI']): ?>
				<a href="<?= $user_controls ?>">User Settings</a>
			<?php else: ?>
				<a href="#">User Settings</a>
			<?php endif ?>
		<?php endif ?>
	</div>
</nav>