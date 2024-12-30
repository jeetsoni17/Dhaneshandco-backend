<?php
include_once '../config/db.php';

$query = "SELECT * FROM sub_subcategories";
$result = $conn->query($query);

if ($result) {
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    echo json_encode($data);
} else {
    http_response_code(500);
    echo json_encode(["error" => "Failed to fetch sub_subcategories"]);
}

$conn->close();
?>