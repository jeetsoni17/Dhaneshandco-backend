<?php
include_once '../config/db.php';

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
        $fileNameWithoutExtension = pathinfo($row['file_path'], PATHINFO_FILENAME);
        // Set headers to initiate the file download
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $fileNameWithoutExtension . '"');
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
