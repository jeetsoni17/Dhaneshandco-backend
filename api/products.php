<?php
// Show PHP errors for debugging (remove in production)

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

// Include DB config safely
include_once __DIR__ . '/../config/db.php';

// Get product ID from query if available
$product_id = $_GET['id'] ?? null;

if ($product_id) {
    // ✅ Fetch one product by ID
    $query = "SELECT * FROM products WHERE product_id = ?";
    $stmt = $conn->prepare($query);

    if (!$stmt) {
        http_response_code(500);
        echo json_encode(["error" => "Prepare failed: " . $conn->error]);
        exit;
    }

    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();

        // ✅ Related products from same subcategory (if available)
        $subcategory_id = $product['subcategory_id'] ?? null;
        $relatedProducts = [];

        if ($subcategory_id) {
            $relatedQuery = "SELECT * FROM products WHERE subcategory_id = ? AND product_id != ? ORDER BY RAND() LIMIT 4";
            $relatedStmt = $conn->prepare($relatedQuery);
            
            if ($relatedStmt) {
                $relatedStmt->bind_param("ii", $subcategory_id, $product_id);
                $relatedStmt->execute();
                $relatedResult = $relatedStmt->get_result();

                while ($row = $relatedResult->fetch_assoc()) {
                    $relatedProducts[] = $row;
                }

                $relatedStmt->close();
            }
        }

        echo json_encode([
            "product" => $product,
            "relatedProducts" => $relatedProducts
        ]);
    } else {
        http_response_code(404);
        echo json_encode(["error" => "Product not found"]);
    }

    $stmt->close();
} else {
    // ✅ Fetch all products
    $query = "SELECT * FROM products";
    $result = $conn->query($query);

    if ($result) {
        $products = [];
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
        echo json_encode($products);
    } else {
        http_response_code(500);
        echo json_encode([
            "error" => "Failed to fetch products",
            "sql_error" => $conn->error
        ]);
    }
}

$conn->close();
