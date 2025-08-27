<?php
// Note: This file is included in another, so it doesn't need its own security check or session start.
// It relies on the including file to handle that.
?>

<div class="main-settings">
    <h1><?= htmlspecialchars($admin_name); ?>'s Settings</h1>

    <?php if (!empty($message)): ?>
        <div class="message <?= htmlspecialchars($message_type); ?>">
            <?= htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <div class="settings-grid">
        <div class="settings-card">
            <h2>Change Password</h2>
            <form action="admin-dashboard-1001.php?page=settings" method="POST">
                <input type="hidden" name="change_password" value="1">
                <div class="form-group">
                    <label for="current_password">Current Password</label>
                    <input type="password" id="current_password" name="current_password" required>
                </div>
                <div class="form-group">
                    <label for="new_password">New Password</label>
                    <input type="password" id="new_password" name="new_password" required>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm New Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
                <button type="submit">Update Password</button>
            </form>
        </div>

        <div class="settings-card">
            <h2>Profile Photo</h2>
            <form action="admin-dashboard-1001.php?page=settings" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="change_photo" value="1">
                <div class="form-group">
                    <label for="profile_photo">Upload New Photo</label>
                    <input type="file" id="profile_photo" name="profile_photo" accept="image/jpeg, image/png, image/gif" required>
                </div>
                <button type="submit">Upload Photo</button>
            </form>
        </div>

        </div>
        <div class="copyright">
            <p>&copy; 2025 Novostella Technologies. All rights reserved.</p>
        </div>
</div>
