<?php

require_once 'connection.php';


$sample_id = $_POST['sample_id'];
$sample_description = $_POST['sample_description'];
$sample_category = $_POST['sample_category'];
$admin_id = $_POST['admin_id'];

// Check if a new file was uploaded
if (isset($_FILES['sample_file']) && $_FILES['sample_file']['error'] == 0) {
    // Handle new file upload
    $upload_dir = 'uploads/';
    $file_name = basename($_FILES['sample_file']['name']);
    $destination = $upload_dir . $file_name;
    
    if (move_uploaded_file($_FILES['sample_file']['tmp_name'], $destination)) {
        // Update with new file path
        $sql = "UPDATE Samples SET sample_item=?, sample_description=?, sample_category=?, admin_id=? WHERE sample_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssii", $destination, $sample_description, $sample_category, $admin_id, $sample_id);
    } else {
        echo "Error uploading new file.";
        $conn->close();
        exit();
    }
} else {
    // No new file, just update text fields
    $sql = "UPDATE Samples SET sample_description=?, sample_category=?, admin_id=? WHERE sample_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssii", $sample_description, $sample_category, $admin_id, $sample_id);
}

if ($stmt->execute()) {
    echo "Sample updated successfully!";
} else {
    echo "Error: " . $stmt->error;
}
$stmt->close();
$conn->close();
?>