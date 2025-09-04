<?php
session_start();
header('Content-Type: application/json');

require_once 'connection.php';

$response = ['success' => false, 'message' => 'An unknown error occurred.'];

if (!isset($_SESSION['admin_id'])) {
    $response['message'] = 'Unauthorized access.';
    echo json_encode($response);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sample_id = $_POST['sample_id'] ?? null;
    $action = $_POST['action'] ?? null;

    if ($action === 'delete' && $sample_id) {
        $stmt = $conn->prepare("DELETE FROM samples WHERE sample_id = ?");
        $stmt->bind_param("i", $sample_id);

        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = 'Sample deleted successfully!';
        } else {
            $response['message'] = "Error deleting sample: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $response['message'] = 'Invalid request parameters.';
    }
} else {
    $response['message'] = 'Invalid request method.';
}

$conn->close();
echo json_encode($response);
?>