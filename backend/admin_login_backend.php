<?php

session_start();

require_once 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $admin_email = trim($_POST['adm_email']);
    $password = $_POST['adm_password'];
    $ip_address = $_SERVER['REMOTE_ADDR'];

    $stmt = $conn->prepare("SELECT admin_id, admin_name, admin_role, password_hash, mfa_secret_code FROM admins WHERE admin_email = ?");
    $stmt->bind_param("s", $admin_email);
    $stmt->execute();
    $result = $stmt->get_result();

    $admin_id = NULL;

    if ($result->num_rows === 1) {
        $admin = $result->fetch_assoc();
        $admin_id = $admin['admin_id'];

        if (password_verify($password, $admin['password_hash'])) {
            $login_status = 'success';

            if (!empty($admin['mfa_secret_code'])) {
                // Admin HAS an MFA secret, send to verification page
                $_SESSION['pending_admin_id'] = $admin['admin_id'];
                $_SESSION['pending_admin_role'] = $admin['admin_role'];
                $_SESSION['pending_admin_name'] = $admin['admin_name'];
                
                header("Location: mfa_verify.php");
                exit();
            } else {
                // Admin does NOT have an MFA secret, send to setup page
                $_SESSION['pending_admin_id'] = $admin['admin_id'];
                $_SESSION['pending_admin_role'] = $admin['admin_role'];
                $_SESSION['pending_admin_name'] = $admin['admin_name'];
                
                header("Location: mfa_setup.php");
                exit();
            }

        } else {
            $error_message = "Invalid email or password.";
        }
    } else {
        $error_message = "Invalid email or password.";
    }

    $stmt->close();
    $conn->close();

    $_SESSION['login_error'] = $error_message;
    header("Location: ../admin/admin_login_1002.php");
    exit();
}
?>