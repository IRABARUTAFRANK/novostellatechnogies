<?php
session_start();
require_once 'connection.php';
require_once '../vendor/autoload.php';

use OTPHP\TOTP;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\RoundBlockSizeMode;

if (!isset($_SESSION['pending_admin_id'])) {
    header("Location: ../admin/admin_login_1002.php"); 
    exit();
}

$admin_id = $_SESSION['pending_admin_id'];
$admin_name = $_SESSION['pending_admin_name'];
$error_message = '';

if (!isset($_SESSION['mfa_temp_secret'])) {
    $otp = TOTP::create();
    $secret = $otp->getSecret();
    $_SESSION['mfa_temp_secret'] = $secret;
} else {
    $secret = $_SESSION['mfa_temp_secret'];
}

$otp = TOTP::create($secret);
$otp->setLabel('Novostella Admin (' . $admin_name . ')');


$provisioning_uri = $otp->getProvisioningUri();


$builder = new Builder(
    writer: new PngWriter(),
    data: $provisioning_uri,
    encoding: new Encoding('UTF-8'),
    errorCorrectionLevel: ErrorCorrectionLevel::Low,
    size: 200,
    margin: 10,
    roundBlockSizeMode: RoundBlockSizeMode::Margin
);

$result = $builder->build();
$qr_code_url = $result->getDataUri();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $otp_code = $_POST['otp_code'];

    if ($otp->verify($otp_code)) {
        $update_stmt = $conn->prepare("UPDATE admins SET mfa_secret_code = ? WHERE admin_id = ?");
        $update_stmt->bind_param("si", $secret, $admin_id);
        $update_stmt->execute();
        $update_stmt->close();
        
        $_SESSION['admin_id'] = $admin_id;
        $_SESSION['admin_name'] = $admin_name;
        $_SESSION['admin_role'] = $_SESSION['pending_admin_role'];

        unset($_SESSION['pending_admin_id']);
        unset($_SESSION['pending_admin_name']);
        unset($_SESSION['pending_admin_role']);
        unset($_SESSION['mfa_temp_secret']);

        header("Location: ../admin/admin-dashboard-1001.php");
        exit();
    } else {
        $error_message = "Invalid NOVOCODE. Please try again.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>NOVOCODE SETUP - NST</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../images/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="../css/mfa_setup.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
</head>
<body>
    <div class="mfa-container">
        <h3>SET UP YOUR <span class="novo_code">NOVOCODE</span></h3>
        <p>Scan the below QR CODE to get your novocode. NB: Use Authenticator or Authy to scan.</p>
        <img src="<?= htmlspecialchars($qr_code_url) ?>" alt="QR Code for MFA Setup">
        
        <?php if ($error_message): ?>
            <p style="color:red;"><?= htmlspecialchars($error_message); ?></p>
        <?php endif; ?>

        <p>Enter <span> NOVOCODE </span></p>
        <span><i class="bi bi-exclamation-triangle"></i> Don't forget it.</span>
        <form method="POST" action="mfa_setup.php">
            <input type="text" name="otp_code" placeholder="Enter 6-digit code" required>
            <button type="submit">DIVE IN</button>
        </form>
    </div>
</body>
</html>
