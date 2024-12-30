<?php
// Database connection
$conn = new mysqli("localhost", "u723981255_jeet", "Dhaneshco@123", "u723981255_dhanesh_db");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get file ID from request
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid file ID.");
}

$id = intval($_GET['id']);

// Fetch file details from the database
$query = "SELECT file_path, file_name FROM pricelist WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    // Adjusting file path to match your directory structure
    $file_path = __DIR__ . "/../public/files/" . basename($row['file_path']); // Ensure safe file handling
    $file_name = $row['file_name'];

    // Check if the file exists
    if (file_exists($file_path)) {
        // Set headers for file download
        header('Content-Description: File Transfer');
        header('Content-Type: application/pdf'); // Adjust based on file type (PDF in this case)
        header('Content-Disposition: attachment; filename="' . basename($file_name) . '"');
        header('Content-Length: ' . filesize($file_path));
        header('Pragma: public');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');

        // Read the file and output to the response
        readfile($file_path);
        exit;
    } else {
        http_response_code(404);
        echo "Error: File not found.";
        exit;
    }
} else {
    http_response_code(400);
    echo "Error: Invalid file ID.";
    exit;
}

// Close database connection
$stmt->close();
$conn->close();
?>
