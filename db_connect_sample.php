<?php

try {

    $host = 'localhost';
    $dbname = 'shop';
    $username = 'root';
    $password = '';
	$query = '%';
	
	try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
        $stmt = $pdo->prepare("SELECT * FROM products WHERE name LIKE :query OR description LIKE :query");
		// $stmt->bindParam(':query', $query);
		$stmt->execute(['query' => $query]);
        
		echo "Connected successfully";
		
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
		echo $results;
		
		foreach($results as $result) {
			echo $result['image'], '<br>';
		}
		
    } catch (PDOException $e) {
        echo "DB conn failed";
    }

  
} catch(PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
}
?>