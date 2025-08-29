<?php
require_once 'connection.php'; 

$orders = [];
$sql = "SELECT order_id, customer_name, customer_phone, customer_address, customer_email, product_ordered, order_datetime, product_image_url, business_name, tin, tel, other_info FROM orders ORDER BY order_datetime DESC";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
}

$conn->close();
?>