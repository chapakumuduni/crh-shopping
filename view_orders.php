<?php
session_start();
require_once('db.php');

// Check admin login
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

// Get the customer ID from the URL (user_id is used to link orders)
$customer_id = isset($_GET['customer_id']) ? $_GET['customer_id'] : 0;
if ($customer_id == 0) {
    die("Customer ID is missing.");
}

// Fetch orders for the specific customer using user_id
$query = "SELECT * FROM orders WHERE user_id = ?";
$stmt = $conn->prepare($query);

// Check if the prepare statement is successful
if ($stmt === false) {
    die('MySQL prepare error: ' . $conn->error);
}

// Bind parameters and execute the query
$stmt->bind_param("i", $customer_id);
$stmt->execute();

// Get the result
$result = $stmt->get_result();

// Check if there are any orders for this customer
if ($result->num_rows == 0) {
    echo "No orders found for this customer.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Orders</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/js/all.min.js"></script>
</head>
<body>
<div class="container mt-4">
    <h2>Orders for Customer ID: <?= htmlspecialchars($customer_id); ?></h2>

    <div class="mb-3">
        <a href="customers.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back to Customers</a>
    </div>

    <table class="table table-bordered table-striped">
        <thead class="table-light">
        <tr>
            <th>Order ID</th>
            <th>Total Price</th>
            <th>Status</th>
            <th>Order Date</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($order = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $order['id']; ?></td>
                <td>$<?= number_format($order['total_price'], 2); ?></td>
                <td><?= htmlspecialchars($order['status']); ?></td>
                <td><?= date("M d, Y H:i A", strtotime($order['order_date'])); ?></td>
                <td>
                    <a href="#" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#orderDetailsModal" 
                       data-order-id="<?= $order['id']; ?>" 
                       data-order-price="<?= $order['total_price']; ?>"
                       data-order-status="<?= $order['status']; ?>"
                       data-order-date="<?= date("M d, Y H:i A", strtotime($order['order_date'])); ?>">
                        <i class="fas fa-eye"></i> View Details
                    </a>
                    <!-- Optionally, add a Cancel Order button here -->
                    <a href="cancel_order.php?order_id=<?= $order['id']; ?>" class="btn btn-danger btn-sm"
                       onclick="return confirm('Are you sure you want to cancel this order?');">
                        <i class="fas fa-times"></i> Cancel Order
                    </a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- Order Details Modal -->
<div class="modal fade" id="orderDetailsModal" tabindex="-1" aria-labelledby="orderDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="orderDetailsModalLabel">Order Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>Order ID:</strong> <span id="order_id"></span></p>
                <p><strong>Total Price:</strong> $<span id="order_price"></span></p>
                <p><strong>Status:</strong> <span id="order_status"></span></p>
                <p><strong>Order Date:</strong> <span id="order_date"></span></p>
                <div id="order_items">
                    <!-- List of order items will be populated here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // JavaScript to populate the order details modal with the selected order's details
    var orderDetailsModal = document.getElementById('orderDetailsModal');
    orderDetailsModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var orderId = button.getAttribute('data-order-id');
        var orderPrice = button.getAttribute('data-order-price');
        var orderStatus = button.getAttribute('data-order-status');
        var orderDate = button.getAttribute('data-order-date');

        // Update modal content
        var modalOrderId = document.getElementById('order_id');
        var modalOrderPrice = document.getElementById('order_price');
        var modalOrderStatus = document.getElementById('order_status');
        var modalOrderDate = document.getElementById('order_date');
        
        modalOrderId.textContent = orderId;
        modalOrderPrice.textContent = orderPrice;
        modalOrderStatus.textContent = orderStatus;
        modalOrderDate.textContent = orderDate;

        // Fetch order items for this order (you may need to adjust this part to match your database structure)
        var orderItems = document.getElementById('order_items');
        orderItems.innerHTML = ''; // Clear previous items
        fetch(`fetch_order_items.php?order_id=${orderId}`)
            .then(response => response.json())
            .then(data => {
                var list = document.createElement('ul');
                data.items.forEach(item => {
                    var listItem = document.createElement('li');
                    listItem.textContent = `${item.name} - Quantity: ${item.quantity} - Price: $${item.price}`;
                    list.appendChild(listItem);
                });
                orderItems.appendChild(list);
            })
            .catch(error => {
                console.error('Error fetching order items:', error);
                orderItems.innerHTML = '<p>Error loading items.</p>';
            });
    });
</script>

</body>
</html>
