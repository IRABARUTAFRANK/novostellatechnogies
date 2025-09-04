<?php


if(!isset($_SESSION['admin_id'])){
    header("Location: admin_login_1002.php");
    exit();
}

$total_orders = 0;
$query_total_orders = "SELECT COUNT(*) AS total FROM orders";
$result_total_orders = $conn->query($query_total_orders);
if ($result_total_orders && $result_total_orders->num_rows > 0) {
    $row = $result_total_orders->fetch_assoc();
    $total_orders = $row['total'];
}

$total_customers = 0;

$query_total_customers = "SELECT COUNT(DISTINCT customer_email) AS total FROM orders";
$result_total_customers = $conn->query($query_total_customers);
if ($result_total_customers && $result_total_customers->num_rows > 0) {
    $row = $result_total_customers->fetch_assoc();
    $total_customers = $row['total'];
}

$recent_orders = [];
$query_recent_orders = "SELECT * FROM orders ORDER BY order_datetime DESC LIMIT 3";
$result_recent_orders = $conn->query($query_recent_orders);
if ($result_recent_orders && $result_recent_orders->num_rows > 0) {
    while ($row = $result_recent_orders->fetch_assoc()) {
        $recent_orders[] = $row;
    }
}


$product_orders = [];
$query_product_orders = "
    SELECT 
        product_ordered, 
        COUNT(*) AS total 
    FROM orders 
    GROUP BY product_ordered
";
$result_product_orders = $conn->query($query_product_orders);
if ($result_product_orders && $result_product_orders->num_rows > 0) {
    while ($row = $result_product_orders->fetch_assoc()) {
        $product_orders[] = $row;
    }
}
$conn->close();

$chart_data = [['Product', 'Orders']];
foreach ($product_orders as $product) {
    $chart_data[] = [htmlspecialchars($product['product_ordered']), (int)$product['total']];
}
?>

<div class="container">
    <div class="dashboard-header">
        <h2 class="dashboard-title">NOVOSTELLA DASHBOARD OVERVIEW</h2>
        <div class="dashboard-welcome">
            Welcome back, <?= htmlspecialchars($admin_name); ?>!
        </div>
    </div>

    <div class="metrics-container">
        <div class="metric-card">
            <div class="metric-icon">
                <i class="fas fa-shopping-bag"></i>
            </div>
            <div class="metric-value"><?= htmlspecialchars($total_orders); ?></div>
            <div class="metric-label">Total Orders</div>
        </div>

        <div class="metric-card">
            <div class="metric-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="metric-value"><?= htmlspecialchars($total_customers); ?></div>
            <div class="metric-label">Total Customers</div>
        </div>
    </div>
<div class="chart-container">
    <h2>Orders by Product</h2>
    <div id="piechart" style="width: 100%; height: 500px;"></div>
</div>
 <div class="recent-orders-section">
        <div class="section-header">
            <h3>Recent Orders</h3>
            <a href="?page=orders" class="btn btn-view-more">View All Orders</a>
        </div>
        <div class="orders-grid">
            <?php if (!empty($recent_orders)): ?>
                <?php foreach ($recent_orders as $order): ?>
                    <div class="order-card">
                        <h4>Order #<?= htmlspecialchars($order['order_id']); ?></h4>
                        <p><strong>Customer:</strong> <?= htmlspecialchars($order['customer_name']); ?></p>
                        <p><strong>Product:</strong> <?= htmlspecialchars($order['product_ordered']); ?></p>
                        <p><strong>Date:</strong> <?= date('M j, Y H:i', strtotime($order['order_datetime'])); ?></p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No recent orders found.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
  <footer>
        <div class="copyright">
            <p>&copy; 2025 Novostella Technologies. All rights reserved.</p>
        </div>
    </footer>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">

    google.charts.load('current', {'packages':['corechart']});


    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
    
        var data = google.visualization.arrayToDataTable(
            <?php echo json_encode($chart_data); ?>
        );


        var options = {
            title: 'Breakdown of Orders by Product',
            is3D: true, 
            animation: {
                startup: true,
                duration: 1000,
                easing: 'out'
            },
            pieHole: 0.4, 
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));
        chart.draw(data, options);
    }
</script>