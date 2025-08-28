<?php

require_once '../backend/connection.php'; 

if(!isset($_SESSION['admin_id'])){
    header("Location: admin_login_1002.php");
    exit();
}

$feedback_data = [];

$sql = "SELECT feedback_id, customer_email, feedback_text FROM Feedback ORDER BY feedback_id DESC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $feedback_data[] = $row;
    }
}

$stmt->close();
$conn->close();
?>

<div class="feedback-container">
    <h1>Customer Feedback</h1>

    <?php if (empty($feedback_data)): ?>
        <p class="no-feedback-message">No feedback has been submitted yet.</p>
    <?php else: ?>
        <div class="feedback-messages">
            <?php foreach ($feedback_data as $feedback): ?>
                <div class="feedback-card">
                    <p class="feedback-text"><?= nl2br(htmlspecialchars($feedback['feedback_text'])); ?></p>
                    <div class="feedback-footer">
                        <span class="feedback-email">From: <?= htmlspecialchars($feedback['customer_email']); ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
            <div class="copyright">
            <p>&copy; 2025 Novostella Technologies. All rights reserved.</p>
        </div>
</div>

</div>