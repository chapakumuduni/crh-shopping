<?php

try {

    $host = 'localhost';
    $dbname = 'shop';
    $username = 'root';
    $password = '';
	$query = '%';
	
	try {
        $conn = new mysqli($host, $username, $password, $dbname);

        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
    } catch (PDOException $e) {
        echo "DB conn failed";
    }

  
} catch(PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
}
?>