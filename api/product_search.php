<?php
// Include database connection (make sure this file exists and connects to your database)
include_once '../config/db.php';

// Get the search term from the query parameters
$searchTerm = $_GET['search'] ?? ''; // Default to empty string if not provided

// If search term is empty, return an error message
if (empty($searchTerm)) {
    echo json_encode(["error" => "No search term provided"]);
    exit();
}

// Sanitize the search term to avoid SQL injection
$searchTerm = mysqli_real_escape_string($conn, $searchTerm);

// Query to fetch products that match the search term
$sql = "SELECT * FROM products WHERE product_name LIKE '%$searchTerm%' OR product_description LIKE '%$searchTerm%'";

// Execute the query
$result = mysqli_query($conn, $sql);

// Check if the query was successful
if ($result) {
    $products = [];
    
    // Fetch all matching products
    while ($row = mysqli_fetch_assoc($result)) {
        $products[] = $row;
    }

    // Return the products as JSON
    echo json_encode($products);
} else {
    echo json_encode(["error" => "Failed to fetch products"]);
}
?>
