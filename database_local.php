<?php
//database configuration
    $dbHost = 'localhost';
    $dbUsername = '#';
    $dbPassword = '#';
    $dbName = '#';
	
	   //connect with the database
    $db = new mysqli($dbHost,$dbUsername,$dbPassword,$dbName);
	$db->set_charset("utf8");
?>
