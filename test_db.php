<?php
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'fashionfabrics';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "Database connection successful!<br>";
    
    // Check tables
    $result = $conn->query("SHOW TABLES");
    echo "Tables in database:<br>";
    while ($row = $result->fetch_array()) {
        echo "- " . $row[0] . "<br>";
    }
    
    $conn->close();
}
?>