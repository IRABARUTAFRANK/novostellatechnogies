<?php
session_start();

require_once 'connection.php'; 

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"));


if (!isset($data->customer_email) || !isset($data->feedback_text)) {
    http_response_code(400);
    echo json_encode(["error" => "Email and comment required."]);
    exit;
}


$customer_email = filter_var($data->customer_email, FILTER_SANITIZE_EMAIL);
$feedback_text = htmlspecialchars($data->feedback_text);


$stmt = $conn->prepare("INSERT INTO Feedback (customer_email, feedback_text) VALUES (?, ?)");
$stmt->bind_param("ss", $customer_email, $feedback_text);

if ($stmt->execute()) {
    echo json_encode(["message" => "Feedback submitted successfully."]);
} else {
    http_response_code(500);
    echo json_encode(["error" => "Failed to insert feedback: " . $stmt->error]); 
}

$stmt->close();
$conn->close();
?>