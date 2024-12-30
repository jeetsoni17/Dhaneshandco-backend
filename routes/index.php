<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

// Get the requested endpoint
$request = $_GET['endpoint'] ?? null;

// Include the necessary API file based on the endpoint
switch ($request) {
    case 'products':
        // If the endpoint is 'products', fetch the product by its ID
        if (isset($_GET['id'])) {
            $product_id = $_GET['id'];
            include_once '../api/products.php'; // Include the product fetch script
            getProductById($product_id); // Call function to fetch product details
        } else {
            http_response_code(400);
            echo json_encode(["error" => "Product ID is required"]);
        }
        break;
    case 'categories':
        include_once '../api/categories.php';
        break;
    case 'pricelist':
        include_once '../api/pricelist.php';
        break;
    default:
        http_response_code(404);
        echo json_encode(["error" => "Endpoint not found"]);
}
