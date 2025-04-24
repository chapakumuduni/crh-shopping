<?php
session_start();
require_once('db.php');

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['message'] = "Invalid request.";
    header("Location: products.php");
    exit();
}

$id = intval($_GET['id']);

// Fetch current status
$stmt = $conn->prepare("SELECT status FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();
$stmt->close();

if ($product) {
    $current_status = $product['status'];
    $new_status = $current_status === 'Active' ? 'Inactive' : ($current_status === 'Inactive' ? 'Archived' : 'Active');

    $update = $conn->prepare("UPDATE products SET status = ? WHERE id = ?");
    $update->bind_param("si", $new_status, $id);
    $update->execute();
    $update->close();

    $_SESSION['message'] = "Product status updated to $new_status.";
} else {
    $_SESSION['message'] = "Product not found.";
}

header("Location: products.php");
exit();
?>
