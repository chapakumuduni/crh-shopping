<?php
require_once('db.php');

if (!isset($_GET['order_id']) || !is_numeric($_GET['order_id'])) {
    echo "<div class='alert alert-danger'>Invalid order ID.</div>";
    exit();
}

$order_id = intval($_GET['order_id']);

// Fetch order
$order_query = $conn->prepare("SELECT o.*, u.name AS user_name, u.email FROM orders o LEFT JOIN users u ON o.user_id = u.id WHERE o.id = ?");
$order_query->bind_param("i", $order_id);
$order_query->execute();
$order_result = $order_query->get_result();
$order = $order_result->fetch_assoc();

if (!$order) {
    echo "<div class='alert alert-warning'>Order not found.</div>";
    exit();
}

// Fetch items
$items_query = $conn->prepare("SELECT oi.*, p.name AS product_name FROM order_items oi LEFT JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?");
$items_query->bind_param("i", $order_id);
$items_query->execute();
$items_result = $items_query->get_result();

// Output
?>
<div>
    <h5>Customer Info</h5>
    <p><strong>Name:</strong> <?= htmlspecialchars($order['user_name']); ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($order['email']); ?></p>

    <h5 class="mt-3">Order Info</h5>
    <p><strong>Order ID:</strong> <?= $order['id']; ?></p>
    <p><strong>Status:</strong> <?= $order['status']; ?></p>
    <p><strong>Total:</strong> $<?= number_format($order['total_price'], 2); ?></p>
    <p><strong>Date:</strong> <?= date('M d, Y H:i A', strtotime($order['order_date'])); ?></p>

    <h5 class="mt-3">Items</h5>
    <table class="table table-bordered">
        <thead><tr><th>Product</th><th>Qty</th><th>Price</th></tr></thead>
        <tbody>
        <?php while ($item = $items_result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($item['product_name']); ?></td>
                <td><?= $item['quantity']; ?></td>
                <td>$<?= number_format($item['price'], 2); ?></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
