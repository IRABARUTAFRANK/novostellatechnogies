<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"));

if (!isset($data->email) || !isset($data->comment)) {
    http_response_code(400);
    echo json_encode(["error" => "Email and comment required."]);
    exit;
}

$email = filter_var($data->email, FILTER_SANITIZE_EMAIL);
$comment = htmlspecialchars($data->comment);

$host = "sql211.infinityfree.com";  
$user = "if0_39552079";
$password = "frabenber123"; 
$dbname = "if0_39552079_novostella_technologies";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed: " . $conn->connect_error]);
    exit;
}

$stmt = $conn->prepare("INSERT INTO feedback (email, comment) VALUES (?, ?)");
$stmt->bind_param("ss", $email, $comment);

if ($stmt->execute()) {
    echo json_encode(["message" => "Feedback submitted successfully."]);
} else {
    http_response_code(500);
    echo json_encode(["error" => "Failed to insert feedback."]);
}

$stmt->close();
$conn->close();
?>
