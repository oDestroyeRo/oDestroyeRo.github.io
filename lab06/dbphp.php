<?php
$servername = "localhost";
$username = "root";
$password = "";

try {
    $conn = new PDO("mysql:host=$servername;dbname=webtech", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected successfully"; 
	echo "<br>";
	
	$stmt = $conn->prepare("INSERT INTO customers ( CustomerID, CitizenID, Firstname, Lastname) VALUES ( :CustomerID, :CitizenID, :Firstname, :Lastname)");
	$stmt->bindParam(':CustomerID', $customerID);
	$stmt->bindParam(':CitizenID', $citizenID );
    $stmt->bindParam(':Firstname', $firstname);
    $stmt->bindParam(':Lastname', $lastname);
	

	// set parameters and execute
	$customerID = "1";
	$citizenID = "1100200125452";
	$firstname = "John";
	$lastname = "Doe";
	$stmt->execute();
	
	
	$customerID = "2";
	$citizenID = "1125400125451";
	$firstname = "ddddd";
	$lastname = "aaaaa";
	$stmt->execute();
	
	echo "Insert successfully";
	
	
    }
catch(PDOException $e)
    {
    echo "Connection failed: " . $e->getMessage();
    }
	
	
	
?>