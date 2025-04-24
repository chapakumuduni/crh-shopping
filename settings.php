<?php
session_start();
require_once('db.php');

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Fetch admin settings
$settings_query = "SELECT * FROM settings WHERE id = 1";
$settings_result = $conn->query($settings_query);
$settings = $settings_result->fetch_assoc();

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update_settings'])) {
        $site_name = $_POST['site_name'];
        $admin_email = $_POST['admin_email'];

        $update_query = "UPDATE settings SET site_name=?, admin_email=? WHERE id=1";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("ss", $site_name, $admin_email);

        if ($stmt->execute()) {
            $message = "✅ Settings updated successfully!";
        } else {
            $message = "❌ Error updating settings.";
        }
        $stmt->close();
    }

    if (isset($_POST['update_password'])) {
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        $user_query = "SELECT password FROM users WHERE id=1";
        $user_result = $conn->query($user_query);
        $user = $user_result->fetch_assoc();

        if ($user && password_verify($current_password, $user['password'])) {
            if ($new_password === $confirm_password) {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $update_password_query = "UPDATE users SET password=? WHERE id=1";
                $stmt = $conn->prepare($update_password_query);
                $stmt->bind_param("s", $hashed_password);

                if ($stmt->execute()) {
                    $message = "✅ Password updated successfully!";
                } else {
                    $message = "❌ Error updating password.";
                }
                $stmt->close();
            } else {
                $message = "⚠️ New passwords do not match.";
            }
        } else {
            $message = "⚠️ Current password is incorrect.";
        }
    }

    if (isset($_POST['logout'])) {
        session_destroy();
        header("Location: login.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Settings</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/js/all.min.js"></script>
</head>
<body>
    <div class="container mt-4">
        <h2>Admin Settings</h2>
        <a href="dashboard.php" class="btn btn-primary mb-3">
            <i class="fas fa-tachometer-alt"></i> Back to Dashboard
        </a>

        <?php if (!empty($message)): ?>
            <div class="alert alert-info"><?php echo $message; ?></div>
        <?php endif; ?>

        <!-- General Settings -->
        <form method="POST">
            <h4>General Settings</h4>
            <div class="mb-3">
                <label class="form-label">Site Name</label>
                <input type="text" name="site_name" class="form-control"
                       value="<?php echo htmlspecialchars($settings['site_name'] ?? ''); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Admin Email</label>
                <input type="email" name="admin_email" class="form-control"
                       value="<?php echo htmlspecialchars($settings['admin_email'] ?? ''); ?>" required>
            </div>
            <button type="submit" name="update_settings" class="btn btn-success">Save Changes</button>
        </form>

        <hr>

        <!-- Change Password -->
        <form method="POST">
            <h4>Change Password</h4>
            <div class="mb-3">
                <label class="form-label">Current Password</label>
                <input type="password" name="current_password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">New Password</label>
                <input type="password" name="new_password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Confirm New Password</label>
                <input type="password" name="confirm_password" class="form-control" required>
            </div>
            <button type="submit" name="update_password" class="btn btn-warning">Update Password</button>
        </form>

        <hr>

        <!-- Logout -->
        <form method="POST">
            <h4>Logout</h4>
            <button type="submit" name="logout" class="btn btn-danger">Logout</button>
        </form>
    </div>
</body>
</html>
