<?php

require_once 'connection.php';

header('Content-Type: application/json');


$sql = "SELECT admin_id, admin_name FROM admins";
$result = $conn->query($sql);

$admins = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $admins[] = $row;
    }
}

echo json_encode($admins);

$conn->close();
?>