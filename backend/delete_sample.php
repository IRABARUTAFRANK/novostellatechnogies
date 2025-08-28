<?php

require_once 'connection.php';

$sample_id = $_POST['sample_id'];

$sql_delete = "DELETE FROM Samples WHERE sample_id=?";
$stmt_delete = $conn->prepare($sql_delete);
$stmt_delete->bind_param("i", $sample_id);

if ($stmt_delete->execute()) {
    echo "Sample deleted successfully!";
} else {
    echo "Error: " . $stmt_delete->error;
}
$stmt_delete->close();
$conn->close();
?>