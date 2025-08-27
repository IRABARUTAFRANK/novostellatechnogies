<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - NOVOSTELLA TECHNOLOGIES</title>
    <link rel="stylesheet" href="../css/admin_login.css?v=1.0">
    <link rel="shortcut icon" href="../images/logo.png" type="image/x-icon">
</head>
<body>
    <div class="login-container">
        <h3>ADMIN LOGIN - NOVOSTELLA</h3>
            <?php
        session_start();
        if (isset($_SESSION['login_error'])) {
            echo '<div class="error-message">' . htmlspecialchars($_SESSION['login_error']) . '</div>';
            unset($_SESSION['login_error']);
        }
        ?>
        <form action="../backend/admin_login_backend.php" method="POST">
            <div class="form-group">
                <input type="email" id="admin_email" placeholder="Email address" name="adm_email" required>
            </div>
            <div class="form-group">
                <input type="password" id="admin_password" placeholder="Admin password" name="adm_password" required>
            </div>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>