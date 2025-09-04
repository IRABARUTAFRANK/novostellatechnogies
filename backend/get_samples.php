<?php

require_once 'connection.php';

$sql = "SELECT sample_id, sample_item, sample_description, sample_category FROM samples ORDER BY sample_id DESC";
$result = $conn->query($sql);

$samples = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $samples[] = [
            'id' => $row['sample_id'],
            'url' => $row['sample_item'],
            'description' => $row['sample_description'],
            'category' => $row['sample_category']
        ];
    }
}

echo json_encode($samples);

$conn->close();
?>