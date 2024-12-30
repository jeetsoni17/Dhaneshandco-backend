<?php
include_once '../config/db.php';

// Check if 'id' is provided to fetch a specific product
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // Prepare the query to fetch a specific product by its ID
    $query = "SELECT * FROM products WHERE product_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $product_id); // 'i' for integer
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Fetch and return the product as JSON
        $product = $result->fetch_assoc();
        echo json_encode($product);
    } else {
        // Return a 404 if the product is not found
        http_response_code(404);
        echo json_encode(["error" => "Product not found"]);
    }

    $stmt->close();
} else {
    // If no 'id' is provided, fetch all products
    $query = "SELECT * FROM products";
    $result = $conn->query($query);

    if ($result) {
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        echo json_encode($data);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Failed to fetch products"]);
    }
}

$conn->close();
?>
