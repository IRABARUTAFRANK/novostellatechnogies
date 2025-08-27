<?php
header("Content-Type: application/json");
require_once 'connection.php';

$data = json_decode(file_get_contents("php://input"));

if (!isset($data->sample_id) || !isset($data->name) || !isset($data->review)) {
    http_response_code(400);
    echo json_encode(["error" => "Sample ID, name, and review are required."]);
    exit;
}

$sample_id = filter_var($data->sample_id, FILTER_VALIDATE_INT);
$name = htmlspecialchars($data->name);
$review_text = htmlspecialchars($data->review);

if ($sample_id === false) {
    http_response_code(400);
    echo json_encode(["error" => "Invalid Sample ID."]);
    exit;
}

$stmt = $conn->prepare("INSERT INTO reviews (sample_id, reviewer_name, review_text) VALUES (?, ?, ?)");
$stmt->bind_param("iss", $sample_id, $name, $review_text);

if ($stmt->execute()) {
    echo json_encode(["message" => "Review submitted successfully."]);
} else {
    http_response_code(500);
    echo json_encode(["error" => "Failed to submit review: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>