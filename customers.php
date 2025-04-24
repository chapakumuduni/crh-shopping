<?php
session_start();
require_once('db.php');

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

// Handle search input
$search = isset($_GET['search']) ? $_GET['search'] : '';
$filter_status = isset($_GET['status']) ? $_GET['status'] : '';

// Query to get all customers
$query = "SELECT * FROM customers WHERE name LIKE ? OR email LIKE ?";
$params = ["%$search%", "%$search%"];
$param_types = "ss";

// Adding filter for status
if (!empty($filter_status)) {
    $query .= " AND status = ?";
    $params[] = $filter_status;
    $param_types .= "s";
}

// Prepare the statement
$stmt = $conn->prepare($query);

// Check if prepare statement was successful
if ($stmt === false) {
    die('MySQL prepare error: ' . $conn->error);
}

$stmt->bind_param($param_types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

// Set the message class for session-based message
$message_class = isset($_SESSION['message_type']) ? $_SESSION['message_type'] : 'info';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Management</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Customer Management</h2>
        <div>
            <a href="dashboard.php" class="btn btn-secondary me-2"><i class="fas fa-home"></i> Dashboard</a>
            <a href="orders.php" class="btn btn-primary"><i class="fas fa-shopping-cart"></i> Orders</a>
        </div>
    </div>

    <!-- Show session-based message -->
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-<?= $message_class; ?> alert-dismissible fade show" role="alert">
            <?php echo $_SESSION['message']; unset($_SESSION['message']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Search form -->
    <form method="GET" class="mb-3">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Search by name or email" value="<?= htmlspecialchars($search); ?>">
            <button class="btn btn-primary" type="submit">Search</button>
        </div>
    </form>

    <!-- Customers Table -->
    <table class="table table-bordered table-striped">
        <thead class="table-light">
            <tr>
                <th>Customer ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Address</th>
                <th>Status</th>
                <th>Registered At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($customer = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $customer['customer_id']; ?></td>
                <td><?= htmlspecialchars($customer['name']); ?></td>
                <td><?= htmlspecialchars($customer['email']); ?></td>
                <td><?= htmlspecialchars($customer['phone']); ?></td>
                <td><?= htmlspecialchars($customer['address']); ?></td>
                <td>
                    <span class="badge bg-<?= $customer['status'] === 'Active' ? 'success' : ($customer['status'] === 'Banned' ? 'danger' : 'secondary'); ?>">
                        <?= $customer['status']; ?>
                    </span>
                </td>
                <td><?= $customer['created_at']; ?></td>
                <td>
                    <a href="view_orders.php?customer_id=<?= $customer['customer_id']; ?>" class="btn btn-info btn-sm">View Orders</a>
                    <?php if ($customer['status'] !== 'Banned'): ?>
                        <a href="ban_customer.php?id=<?= $customer['customer_id']; ?>" class="btn btn-warning btn-sm" onclick="return confirm('Are you sure you want to ban this customer?');">Ban</a>
                    <?php else: ?>
                        <a href="activate_customer.php?id=<?= $customer['customer_id']; ?>" class="btn btn-success btn-sm" onclick="return confirm('Activate this customer?');">Activate</a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/js/all.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
