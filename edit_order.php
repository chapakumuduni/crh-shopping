<?php
session_start();
require_once('db.php');

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

$message = "";
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Fetch order details
    $query = "SELECT * FROM orders WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $order = $result->fetch_assoc();
    $stmt->close();
} else {
    header("Location: orders.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $customer_name = $_POST['customer_name'];
    $status = $_POST['status'];
    
    $query = "UPDATE orders SET customer_name = ?, status = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssi", $customer_name, $status, $id);
    
    if ($stmt->execute()) {
        $message = "Order updated successfully!";
        header("Location: orders.php");
        exit();
    } else {
        $message = "Error updating order.";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Order</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <h2>Edit Order</h2>
        
        <?php if ($message): ?>
            <div class="alert alert-info"> <?php echo $message; ?> </div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Customer Name</label>
                <input type="text" name="customer_name" class="form-control" value="<?php echo $order['customer_name']; ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Order Status</label>
                <select name="status" class="form-control">
                    <option value="Pending" <?php echo ($order['status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                    <option value="Shipped" <?php echo ($order['status'] == 'Shipped') ? 'selected' : ''; ?>>Shipped</option>
                    <option value="Delivered" <?php echo ($order['status'] == 'Delivered') ? 'selected' : ''; ?>>Delivered</option>
                    <option value="Cancelled" <?php echo ($order['status'] == 'Cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update Order</button>
        </form>
    </div>
</body>
</html>
