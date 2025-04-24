<?php
session_start();
require_once('db.php');

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Optional: Fetch product to check existence (and image, if needed)
    $query = "SELECT image FROM products WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    $stmt->close();

    if ($product) {
        // Archive the product
        $query = "UPDATE products SET is_archived = 1 WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            $_SESSION['message'] = "Product archived successfully!";
        } else {
            $_SESSION['message'] = "Error archiving product.";
        }
        $stmt->close();
    } else {
        $_SESSION['message'] = "Product not found.";
    }

    $conn->close();
    header("Location: products.php");
    exit();

} else {
    $_SESSION['message'] = "Invalid request.";
    header("Location: products.php");
    exit();
}
?>
