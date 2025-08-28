<?php
// Database connection details
require_once 'connection.php';

// Define the directory where files will be stored
$upload_dir = '../uploads/';
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true); // Create directory if it doesn't exist
}

// Check if a file was uploaded
if (isset($_FILES['sample_file']) && $_FILES['sample_file']['error'] == 0) {
    $file_tmp_name = $_FILES['sample_file']['tmp_name'];
    $file_name = basename($_FILES['sample_file']['name']);
    $destination = $upload_dir . $file_name;

    // Move the file to the permanent location
    if (move_uploaded_file($file_tmp_name, $destination)) {
        // File moved successfully, now save the path to the database
        $sample_item = $destination; // The path to the uploaded file
        $sample_description = $_POST['sample_description'];
        $sample_category = $_POST['sample_category'];
        $admin_id = $_POST['admin_id'];

        // SQL query to insert data
        $sql = "INSERT INTO Samples (sample_item, sample_description, sample_category, admin_id)
                VALUES (?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $sample_item, $sample_description, $sample_category, $admin_id);
        
        if ($stmt->execute()) {
            echo "File uploaded and data saved successfully!";
        } else {
            echo "Error saving to database: " . $stmt->error;
        }

        $stmt->close();

    } else {
        echo "Error moving the uploaded file.";
    }
} else {
    echo "No file was uploaded or an error occurred during upload.";
}

$conn->close();
?>