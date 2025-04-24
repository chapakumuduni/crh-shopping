<?php
session_start();
require_once('db.php');

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

// Check if order_id is set
if (!isset($_GET['order_id']) || !is_numeric($_GET['order_id'])) {
    die("Invalid order ID.");
}

$order_id = $_GET['order_id'];

// Fetch order info
$query = "SELECT o.*, u.name AS customer_name, u.email AS customer_email, u.address AS customer_address, u.phone AS customer_phone
          FROM orders o
          LEFT JOIN customers u ON o.user_id = u.customer_id
          WHERE o.id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$order) {
    die("Order not found.");
}

// Fetch products related to the order
$product_query = "SELECT p.name AS product_name, p.price, op.quantity
                  FROM order_products op
                  LEFT JOIN products p ON op.product_id = p.id
                  WHERE op.order_id = ?";
$product_stmt = $conn->prepare($product_query);
$product_stmt->bind_param("i", $order_id);
$product_stmt->execute();
$products_result = $product_stmt->get_result();
$product_stmt->close();

$total_price = 0; // Total price calculation
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-4">
    <h3>Order Details</h3>
    <a href="orders.php" class="btn btn-secondary mb-3">← Back to Orders</a>

    <div class="card">
        <div class="card-body">
            <!-- Customer Information -->
            <h5>Customer Info</h5>
            <p><strong>Name:</strong> <?= htmlspecialchars($order['customer_name']); ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($order['customer_email']); ?></p>
            <p><strong>Address:</strong> <?= htmlspecialchars($order['customer_address']); ?></p>
            <p><strong>Phone:</strong> <?= htmlspecialchars($order['customer_phone']); ?></p>

            <h5 class="mt-4">Order Info</h5>
            <p><strong>Order ID:</strong> <?= $order['id']; ?></p>
            <p><strong>Total Price:</strong> $<?= number_format($order['total_price'], 2); ?></p>
            <p><strong>Status:</strong> <?= $order['status']; ?></p>
            <p><strong>Payment Method:</strong> <?= $order['payment_method']; ?></p>
            <p><strong>Order Date:</strong> <?= date("M d, Y H:i A", strtotime($order['order_date'])); ?></p>

            <!-- Product Details -->
            <h5 class="mt-4">Ordered Products</h5>
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Product Name</th>
                        <th>Unit Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($product = $products_result->fetch_assoc()): ?>
                        <?php 
                            $product_total = $product['quantity'] * $product['price'];
                            $total_price += $product_total; 
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($product['product_name']); ?></td>
                            <td>$<?= number_format($product['price'], 2); ?></td>
                            <td><?= $product['quantity']; ?></td>
                            <td>$<?= number_format($product_total, 2); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <h5>Total Price: $<?= number_format($total_price, 2); ?></h5>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
