<?php
session_start();
require_once '../backend/connection.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login_1002.php");
    exit();
}

$admin_id = $_SESSION['admin_id'];
$admin_name = $_SESSION['admin_name'];
$admin_role = $_SESSION['admin_role'];
$message = '';
$message_type = '';


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    $stmt = $conn->prepare("SELECT password_hash FROM admins WHERE admin_id = ?");
    $stmt->bind_param("i", $admin_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $admin = $result->fetch_assoc();
    $stmt->close();

    if ($admin && password_verify($current_password, $admin['password_hash'])) {
        if ($new_password === $confirm_password) {
            $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);
            $update_stmt = $conn->prepare("UPDATE admins SET password_hash = ? WHERE admin_id = ?");
            $update_stmt->bind_param("si", $new_password_hash, $admin_id);
            if ($update_stmt->execute()) {
                $message = "Password updated successfully!";
                $message_type = "success";
            } else {
                $message = "Error updating password.";
                $message_type = "error";
            }
            $update_stmt->close();
        } else {
            $message = "New passwords do not match.";
            $message_type = "error";
        }
    } else {
        $message = "Invalid current password.";
        $message_type = "error";
    }
}
// --- Process Change Photo Form ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_photo'])) {
    if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] == UPLOAD_ERR_OK) {
        $photo_dir = '../images/avatars/';
        if (!is_dir($photo_dir)) {
            mkdir($photo_dir, 0777, true);
        }
        
        $file_info = pathinfo($_FILES['profile_photo']['name']);
        $file_extension = strtolower($file_info['extension']);
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($file_extension, $allowed_ext)) {
            $new_filename = $admin_id . '_' . time() . '.' . $file_extension;
            $destination = $photo_dir . $new_filename;
            
            if (move_uploaded_file($_FILES['profile_photo']['tmp_name'], $destination)) {
                $update_stmt = $conn->prepare("UPDATE admins SET profile_photo = ? WHERE admin_id = ?");
                $update_stmt->bind_param("si", $destination, $admin_id);
                if ($update_stmt->execute()) {
                    $message = "Profile photo updated successfully!";
                    $message_type = "success";
                } else {
                    $message = "Error updating photo.";
                    $message_type = "error";
                }
                $update_stmt->close();
            } else {
                $message = "Failed to upload file.";
                $message_type = "error";
            }
        } else {
            $message = "Invalid file type. Only JPG, PNG, and GIF are allowed.";
            $message_type = "error";
        }
    } else {
        $message = "No file uploaded or an error occurred.";
        $message_type = "error";
    }
}

$photo_stmt = $conn->prepare("SELECT profile_photo FROM admins WHERE admin_id = ?");
$photo_stmt->bind_param("i", $admin_id);
$photo_stmt->execute();

$photo_result = $photo_stmt->get_result();
$admin_data = $photo_result->fetch_assoc();
$profile_photo = $admin_data['profile_photo'];
$photo_stmt->close();

$avatar_src = !empty($profile_photo) ? $profile_photo : '../images/ceo.png';




$content_file = 'dashboard_content.php';
if (isset($_GET['page'])) {
    $requested_page = $_GET['page'];
    switch ($requested_page) {
        case 'settings':
            $content_file = 'admin_settings.php';
            break;
        case 'orders':
            $content_file = 'orders_content.php';
            break;
        case 'feedback':
            $content_file = 'feedback_content.php';
            break;
        case 'samples':
            $content_file = 'admin_samples.php';
            break;
        default:
            $content_file = 'dashboard_content.php';
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($admin_name); ?>'s Dashboard - NOVOSTELLA TECHNOLOGIES</title>
    <link rel="shortcut icon" href="../images/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../css/admin.css?v=9.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.cdnfonts.com/css/br-shape" rel="stylesheet">
    <link href="https://fonts.cdnfonts.com/css/bagel-fat-one" rel="stylesheet">
    <?php if (isset($_GET['page']) && $_GET['page'] == 'settings'): ?>
        <link rel="stylesheet" href="../css/settings.css?v=1.0">
    <?php endif; ?>
    <?php if (isset($_GET['page']) && $_GET['page'] == 'feedback'): ?>
        <link rel="stylesheet" href="../css/feedback.css?v=6.0">
    <?php endif; ?>
    <?php if (isset($_GET['page']) && $_GET['page'] == 'samples'): ?>
        <link rel="stylesheet" href="../css/admin_samples.css?v=3.0">
        <script src="../js/admin_samples.js"></script>
    <?php endif; ?>
        <?php if (isset($_GET['page']) && $_GET['page'] == 'orders'): ?>
        <link rel="stylesheet" href="../css/orders_content.css?v=3.0">
    <?php endif; ?>
</head>
<body>

    <div class="sidebar">
        <div class="sidebar-header">
            <img src="../images/logo.png" alt="Company Logo" class="sidebar-logo">
            <h5 class="sidebar-title">NOVOSTELLA</h5>
        </div>
        <ul class="sidebar-nav">
            <li class="sidebar-item active">
                <a href="?page=dashboard" class="sidebar-link">
                    <i class="bi bi-grid-fill"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="sidebar-item">
                <a href="?page=orders" class="sidebar-link">
                    <span>Orders</span>
                </a>
            </li>
            <li class="sidebar-item">
                <a href="?page=samples" class="sidebar-link">
                    <span>Samples</span>
                </a>
            </li>
            <li class="sidebar-item">
                <a href="?page=services" class="sidebar-link">
                    <span>Services</span>
                </a>
            </li>
            <li class="sidebar-item">
                <a href="?page=products" class="sidebar-link">
                    <span>Products</span>
                </a>
            </li>
              <li class="sidebar-item">
                <a href="admin-dashboard-1001.php?page=feedback" class="sidebar-link">
                    <span>Feedback</span>
                </a>
            </li>
            <li class="sidebar-item">
                <a href="?page=linked_customers" class="sidebar-link">
                    <span>Linked customers</span>
                </a>
            </li>
            <li class="sidebar-item-divider"></li>
            <li class="sidebar-item">
                <a href="?page=settings" class="sidebar-link">
                    <i class="bi bi-gear"></i>
                    <span>Settings</span>
                </a>
            </li>
            <li class="sidebar-item">
                <a href="../backend/logout.php" class="sidebar-link">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Logout</span>
                </a>
            </li>
        </ul>
    </div>

    <div class="main-content">
        <header class="main-header">
            <div class="search-bar">
                <input type="search" placeholder="Search...">
            </div>

            <div class="header-right">
                <a href="#" class="notification-icon">
                    <i class="bi bi-bell"></i>
                    <span class="notification-dot"></span>
                </a>
                
                <div class="user-profile">
                     <img src="<?= htmlspecialchars($avatar_src); ?>" alt="Admin" class="user-avatar">
                    <div class="user-info">
                        <h6 class="user-name">Hello, <?= htmlspecialchars($admin_name); ?>!</h6>
                        <small class="user-role"><?= htmlspecialchars($admin_role); ?></small>
                    </div>
                </div>
            </div>
        </header>

        <main class="content-area">
              <?php

            if (file_exists($content_file)) {
                include $content_file;
            } else {
                echo "<p>Content not found.</p>";
            }
            ?>

        </main>

    </div>

</body>
</html>