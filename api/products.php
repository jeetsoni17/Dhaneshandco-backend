<?php
include_once '../config/db.php';

// Check if an 'id' parameter is provided to fetch a specific product
$product_id = $_GET['id'] ?? null;

if ($product_id) {
    // Fetch a specific product by ID
    $query = "SELECT * FROM products WHERE product_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
        echo json_encode($product); // Return the single product as JSON
    } else {
        http_response_code(404);
        echo json_encode(["error" => "Product not found"]);
    }

    $stmt->close();
} else {
    // If no 'id' parameter is provided, fetch all products
    $query = "SELECT * FROM products";
    $result = $conn->query($query);

    if ($result) {
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        echo json_encode($data); // Return all products as JSON
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Failed to fetch products"]);
    }
}

$conn->close();
?>
