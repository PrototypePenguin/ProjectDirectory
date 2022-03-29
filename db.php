<?php
/* Database credentials. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
define('DB_DSN', 'mysql:host=localhost;dbname=cburnsproject;charset=utf8');
define('DB_USERNAME', 'serveruser');
define('DB_PASSWORD', 'gorgonzola7!');
 

 
// Check connection
try {
  /* Attempt to connect to MySQL database */
  $db = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
}
catch(PDOException $e) {
  die("ERROR: Could not connect. " . $e->getMessage());
}
?>