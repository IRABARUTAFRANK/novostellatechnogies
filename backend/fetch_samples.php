<?php

require_once 'connection.php';

header('Content-Type: application/json');


$sql = "SELECT sample_id, sample_item, sample_description, sample_category FROM samples";
$result = $conn->query($sql);

$samples = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $samples[] = $row;
    }
}

echo json_encode($samples);

$conn->close();
?>