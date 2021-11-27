<?php
    chdir(dirname(__FILE__));
	error_reporting(0);

	include "../config/database.php";

	try {
		$db = new PDO("mysql:host={$dbHost};dbname={$dbName};", $dbUser, $dbPassword, array(PDO::ATTR_PERSISTENT => true));
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	} catch (PDOException $e){
		echo 'Connection failed: '.$e->getMessage();
	}
?>
