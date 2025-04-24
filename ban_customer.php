<?php
session_start();
require_once('db.php');

// Check if the admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

// Check if customer_id is passed
if (isset($_GET['id'])) {
    $customer_id = intval($_GET['id']);
    
    // Update the status to 'Banned'
    $stmt = $conn->prepare("UPDATE customers SET status = 'Banned' WHERE customer_id = ?");
    $stmt->bind_param("i", $customer_id);

    // Execute the query and show messages accordingly
    if ($stmt->execute()) {
        $_SESSION['message'] = "Customer has been banned successfully.";
        $message_class = "alert-success";
    } else {
        $_SESSION['message'] = "Error banning customer.";
        $message_class = "alert-danger";
    }
    $stmt->close();
} else {
    $_SESSION['message'] = "No customer ID provided.";
    $message_class = "alert-warning";
}

// Redirect back to the customer management page
header("Location: customers.php");
exit();
?>
