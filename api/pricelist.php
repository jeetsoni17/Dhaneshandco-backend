<?php
include_once '../config/db.php';

$query = "SELECT * FROM pricelist";
$result = $conn->query($query);

if ($result) {
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    echo json_encode($data);
} else {
    http_response_code(500);
    echo json_encode(["error" => "Failed to fetch categories"]);
}

$conn->close();
?>