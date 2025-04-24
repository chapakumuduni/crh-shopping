<?php
session_start();
require_once('db.php');

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Prevent admin self-deletion
    $query = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
    
    if ($user['role'] === 'admin') {
        $_SESSION['message'] = "You cannot delete an admin user.";
        header("Location: users.php");
        exit();
    }
    
    // Soft delete user instead of permanent deletion
    $query = "UPDATE users SET is_deleted = 1 WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        $_SESSION['message'] = "User deleted successfully!";
    } else {
        $_SESSION['message'] = "Error deleting user.";
    }
    
    $stmt->close();
    $conn->close();
    header("Location: users.php");
    exit();
} else {
    $_SESSION['message'] = "Invalid request.";
    header("Location: users.php");
    exit();
}
?>
