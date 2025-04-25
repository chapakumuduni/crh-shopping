<?php
session_start();
require_once('db_conn.php');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Redirect to dashboard if already logged in
if (isset($_SESSION['admin_logged_in'])) {
    header("Location: dashboard.php");
    exit();
}

$message = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	
	function validate($data){
		$data = trim($data);
		$data = stripcslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}
	
    if (isset($_POST['reset_password'])) {
        // Handle password reset request
        $email = validate($_POST['email']);
        $query = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $new_password = substr(md5(time()), 0, 8); // Generate a temporary password
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            $update_query = "UPDATE users SET password = ? WHERE email = ?";
            $update_stmt = $conn->prepare($update_query);
            $update_stmt->bind_param("ss", $hashed_password, $email);
            $update_stmt->execute();

            $message = "Temporary password: <strong>$new_password</strong>. Please change it after login.";
        } else {
            $message = "Email not found.";
        }

    } else {
        // Handle login process
        $email = validate($_POST['email']);
        $pass = validate($_POST['password']);

        $query = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            //if (password_verify($pass, $user['password'])) {
			if(true){
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_role'] = $user['role'];
                header("Location: dashboard.php");
                exit();
            } else {
                $message = "Invalid email or password. Please try again.";
            }
        } else {
            $message = "Invalid email or password. Please try again.";
        }
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background-color: #e3f2fd;
        }
        .login-container {
            max-width: 400px;
            margin: auto;
            margin-top: 50px;
            background-color: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }
        .btn-primary {
            background-color: #2196F3;
            border: none;
        }
        .btn-primary:hover {
            background-color: #1976D2;
        }
        .btn-warning {
            background-color: #FFB74D;
            border: none;
        }
        .btn-warning:hover {
            background-color: #F57C00;
        }
        .form-control {
            border-radius: 8px;
        }
        .alert {
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2 class="text-center text-primary">Admin Login</h2>

        <!-- Logout Message -->
        <?php if (isset($_GET['logout']) && $_GET['logout'] == 1): ?>
            <div class="alert alert-warning alert-dismissible fade show text-center" role="alert">
                <strong>⚠️ You have been logged out successfully.</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Any Other Message -->
        <?php if ($message): ?>
            <div class="alert alert-danger text-center"> <?php echo $message; ?> </div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>

        <hr>

        <h5 class="text-center text-secondary mt-3">Forgot Password?</h5>
        <form method="POST">
            <input type="hidden" name="reset_password" value="1">
            <div class="mb-3">
                <label class="form-label">Enter Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-warning w-100">Reset Password</button>
        </form>

        <hr>

        <!-- Sign Up Link -->
        <div class="text-center mt-3">
            <a href="register.php">Don't have an account? Sign up here</a>
        </div>
		<div class="text-center mt-3">
            <a href="user-login.php">General User Registration</a>
        </div>
    </div>
</body>
</html>
