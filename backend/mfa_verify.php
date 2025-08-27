<?php
session_start();
require_once 'connection.php';
require_once '../vendor/autoload.php';

use OTPHP\TOTP;


if (!isset($_SESSION['pending_admin_id'])) {
    header("Location: ../admin/admin_login_1002.php");
    exit();
}

$error_message = '';
$admin_id = $_SESSION['pending_admin_id'];

if ($_SERVER['REQUEST_METHOD']  === 'POST') {
    $otp_code = $_POST['otp_code'];

    $stmt = $conn->prepare("SELECT mfa_secret_code FROM admins WHERE admin_id = ?");
    $stmt->bind_param("i", $admin_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $admin = $result->fetch_assoc();
    $stmt->close();
    
    $conn->close();

    if ($admin && !empty($admin['mfa_secret_code'])) {
        $otp = TOTP::create($admin['mfa_secret_code']);

      
        if ($otp->verify($otp_code)) {

            $_SESSION['admin_id'] = $admin_id;
            $_SESSION['admin_name'] = $_SESSION['pending_admin_name'];
            $_SESSION['admin_role'] = $_SESSION['pending_admin_role'];

        
            unset($_SESSION['pending_admin_id']);
            unset($_SESSION['pending_admin_name']);
            unset($_SESSION['pending_admin_role']);

            header("Location: ../admin/admin-dashboard-1001.php");
            exit();
        } else {
            $error_message = "Invalid NOVOCODE. Please try again.";
        }
    } else {
        $error_message = "NOVOCODE not found. Please contact support.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Verify NOVOCODE</title>
     <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../images/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="../css/mfa_verify.css">
</head>
</head>
<body>
    <div class="mfa-container">
        <h3><span class="novo_code"> NOVOCODE</span> Verification</h3>
        <p>Check your "Authenticator App" For the 6-digit code.</p>
        
        <?php if ($error_message): ?>
            <p style="color:red;"><?= htmlspecialchars($error_message); ?></p>
        <?php endif; ?>

        <form method="POST" action="mfa_verify.php">
            <input type="text" name="otp_code" placeholder="Enter 6-digit code" required>
            <button type="submit">Verify</button>
        </form>
    </div>
</body>
</html>