<?php
// Database connection details 
$host = 'localhost'; // or '154.41.233.204' (earlier this was used)
$user = 'u723981255_jeet'; 
$password = 'Dhaneshco@123';
$database = 'u723981255_dhanesh_db'; 

// Create a new connection
$conn = new mysqli($host, $user, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Close the connection (if needed)
// $conn->close();
?>
<?php