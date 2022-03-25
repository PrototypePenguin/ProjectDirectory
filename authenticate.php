<?php 
/******************************************************************
 * Author:  Alan Simpson
 * Updated:	Charles Burns
 * Date:    Jan 31, 2022
 * Purpose:	Forces users to log in when required by a page
 ******************************************************************/
	define('ADMIN_LOGIN','serveruser');

	define('ADMIN_PASSWORD','gorgonzola7!');

	if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW'])

		|| ($_SERVER['PHP_AUTH_USER'] != ADMIN_LOGIN)

		|| ($_SERVER['PHP_AUTH_PW'] != ADMIN_PASSWORD)) {

	header('HTTP/1.1 401 Unauthorized');

	header('WWW-Authenticate: Basic realm="Our Blog"');

	exit("Access Denied: Username and password required.");

	} 
 ?>