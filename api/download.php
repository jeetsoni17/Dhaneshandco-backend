<?php
// Database connection
$conn = new mysqli("154.41.233.204", "u723981255_jeet", "Dhaneshco@123", "u723981255_dhanesh_db");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the file ID from the request
$id = intval($_GET['id']);

// Fetch the file details from the database
$query = "SELECT file_path, file_name FROM pricelist WHERE id = $id";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $file = "../public/files/" . $row['file_path']; // Adjust the path to your files directory

    // Check if the file exists
    if (file_exists($file)) {
        // Set headers to initiate the file download
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $row['file_name'] . '"');
        header('Content-Length: ' . filesize($file));
        readfile($file);
        exit;
    } else {
        echo "File not found.";
    }
} else {
    echo "Invalid file ID.";
}

// Close the database connection
$conn->close();
?>
