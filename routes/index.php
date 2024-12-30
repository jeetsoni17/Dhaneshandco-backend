<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

// Get the requested endpoint
$request = $_GET['endpoint'] ?? null;

// Include the necessary API file based on the endpoint
switch ($request) {
    case 'products':
        include_once '../api/products.php';
        break;
    case 'categories':
        include_once '../api/categories.php';
        break;
    case 'pricelist':
        include_once '../api/pricelist.php';
        case 'subcategories':
            include_once '../api/subcategories.php';
        break;
    default:
        http_response_code(404);
        echo json_encode(["error" => "Endpoint not found"]);
}
