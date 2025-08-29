    <div class="container">
        <h1><i class="fas fa-box-open"></i> Recent Orders</h1>
        
        <div class="orders-grid">
            <?php
            require_once '../backend/fetch_orders.php';

            if (!empty($orders)) {
                foreach ($orders as $order) {
                    ?>
                    <div class="order-card">
                        <div class="order-header">
                            <span class="order-id">Order #<?php echo htmlspecialchars($order['order_id']); ?></span>
                            <span class="order-date"><?php echo date('M j, Y H:i', strtotime($order['order_datetime'])); ?></span>
                        </div>
                        <div class="order-details">
                            <p><strong>Customer:</strong> <?php echo htmlspecialchars($order['customer_name']); ?></p>
                            <p><strong>Product:</strong> <?php echo htmlspecialchars($order['product_ordered']); ?></p>
                            <p><strong>Business Name:</strong> <?php echo htmlspecialchars($order['business_name']); ?></p>
                            <p><strong>Phone:</strong> <?php echo htmlspecialchars($order['customer_phone']); ?></p>
                            <p><strong>Email:</strong> <?php echo htmlspecialchars($order['customer_email']); ?></p>
                            <p><strong>Address:</strong> <?php echo htmlspecialchars($order['customer_address']); ?></p>
                            <?php if (!empty($order['tin'])): ?>
                                <p><strong>TIN:</strong> <?php echo htmlspecialchars($order['tin']); ?></p>
                            <?php endif; ?>
                            <?php if (!empty($order['tel'])): ?>
                                <p><strong>Tel:</strong> <?php echo htmlspecialchars($order['tel']); ?></p>
                            <?php endif; ?>
                            <?php if (!empty($order['other_info'])): ?>
                                <p><strong>Other Info:</strong> <?php echo nl2br(htmlspecialchars($order['other_info'])); ?></p>
                            <?php endif; ?>
                        </div>
                        <?php if (!empty($order['product_image_url'])): ?>
                            <div class="product-image-container">
                                <img src="<?php echo htmlspecialchars($order['product_image_url']); ?>" alt="Product Image" class="product-image">
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php
                }
            } else {
                echo '<p class="no-orders">No orders have been placed yet.</p>';
            }
            ?>
        </div>
    </div>