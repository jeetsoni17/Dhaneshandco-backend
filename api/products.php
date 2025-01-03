<?php
include_once '../config/db.php';

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

        // Fetch random related products using subcategory_id
        $subcategory_id = $product['subcategory_id'] ?? null;
        if ($subcategory_id) {
            $relatedQuery = "SELECT * FROM products WHERE subcategory_id = ? AND product_id != ? ORDER BY RAND() LIMIT 4";
            $stmt = $conn->prepare($relatedQuery);
            $stmt->bind_param("ii", $subcategory_id, $product_id);
            $stmt->execute();
            $relatedResult = $stmt->get_result();

            $relatedProducts = [];
            while ($row = $relatedResult->fetch_assoc()) {
                $relatedProducts[] = $row;
            }
        } else {
            $relatedProducts = [];
        }

        // Combine the product and related products into a single response
        echo json_encode([
            "product" => $product,
            "relatedProducts" => $relatedProducts,
        ]);
    } else {
        http_response_code(404);
        echo json_encode(["error" => "Product not found"]);
    }

    $stmt->close();
} else {
    // Fetch all products
    $query = "SELECT * FROM products";
    $result = $conn->query($query);

    if ($result) {
        $products = [];
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
        echo json_encode($products); // Return all products as JSON
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Failed to fetch products"]);
    }
}

$conn->close();
?>
