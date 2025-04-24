<?php
session_start();
require_once('db.php');

use Dompdf\Dompdf;
require 'vendor/autoload.php'; // Make sure this is after opening PHP tag

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

// Handle search input
$search = isset($_GET['search']) ? $_GET['search'] : '';
$filter_status = isset($_GET['status']) ? $_GET['status'] : '';

// Query to get all orders with customer information (join with customers)
$query = "SELECT o.*, u.name AS customer_name, u.email AS customer_email, o.payment_method
          FROM orders o
          LEFT JOIN customers u ON o.user_id = u.customer_id
          WHERE o.user_id LIKE ?";
$params = ["%$search%"];
$param_types = "s";

if (!empty($filter_status)) {
    $query .= " AND o.status = ?";
    $params[] = $filter_status;
    $param_types .= "s";
}

$stmt = $conn->prepare($query);
$stmt->bind_param($param_types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

// Function to get status class for Bootstrap badge
function getStatusClass($status) {
    switch ($status) {
        case 'Pending': return 'warning';
        case 'Shipped': return 'info';
        case 'Delivered': return 'success';
        case 'Cancelled': return 'danger';
        default: return 'secondary';
    }
}

// Handle exporting orders to CSV
if (isset($_GET['export'])) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="orders.csv"');
    $output = fopen('php://output', 'w');
    
    // Add CSV headers
    fputcsv($output, ['Order ID', 'Customer Name', 'Email', 'Total Price', 'Order Date', 'Status', 'Payment Method']);
    
    // Write order data to CSV
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, [$row['id'], $row['customer_name'], $row['customer_email'], number_format($row['total_price'], 2), $row['order_date'], $row['status'], $row['payment_method']]);
    }
    fclose($output);
    exit();
}

// Handle exporting all orders to PDF
if (isset($_GET['generate_pdf'])) {
    $dompdf = new Dompdf();

    // Query for all orders
    $orders_query = "SELECT o.*, u.name AS customer_name, u.email AS customer_email 
                     FROM orders o
                     LEFT JOIN customers u ON o.user_id = u.customer_id";
    $orders_result = $conn->query($orders_query);

    $html = '
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .invoice-box { max-width: 100%; margin: auto; padding: 30px; border: 1px solid #eee; background: #fff; }
        .summary-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .summary-table td, .summary-table th { border: 1px solid #ddd; padding: 8px; }
        .summary-table th { background-color: #f8f9fa; text-align: left; }
        .right { text-align: right; }
    </style>
    <div class="invoice-box">
        <div class="header">
            <div class="company">CR Shopping</div>
            <div>All Orders</div>
        </div>
        <table class="summary-table">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer Name</th>
                    <th>Customer Email</th>
                    <th>Total Price</th>
                    <th>Status</th>
                    <th>Payment Method</th>
                </tr>
            </thead>
            <tbody>';
            
    while ($row = $orders_result->fetch_assoc()) {
        $html .= "
            <tr>
                <td>{$row['id']}</td>
                <td>{$row['customer_name']}</td>
                <td>{$row['customer_email']}</td>
                <td>\${$row['total_price']}</td>
                <td>{$row['status']}</td>
                <td>{$row['payment_method']}</td>
            </tr>";
    }

    $html .= '
            </tbody>
        </table>
    </div>';

    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4');
    $dompdf->render();
    $dompdf->stream("All_Orders.pdf", ["Attachment" => 0]);
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/js/all.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<div class="container mt-4">
    <h2>Manage Orders</h2>

    <div class="mb-3">
        <a href="dashboard.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
        <a href="orders.php?export=true" class="btn btn-success">
            <i class="fas fa-download"></i> Export Orders to CSV
        </a>
        <a href="orders.php?generate_pdf=true" class="btn btn-danger">
            <i class="fas fa-file-pdf"></i> Generate All Orders PDF
        </a>
    </div>

    <!-- Search and Filter Form -->
    <form method="GET" class="mb-3 d-flex">
        <input type="text" name="search" class="form-control me-2" placeholder="Search by Customer ID" value="<?= htmlspecialchars($search); ?>">
        <select name="status" class="form-control me-2">
            <option value="">All Statuses</option>
            <option value="Pending" <?= $filter_status == 'Pending' ? 'selected' : '' ?>>Pending</option>
            <option value="Shipped" <?= $filter_status == 'Shipped' ? 'selected' : '' ?>>Shipped</option>
            <option value="Delivered" <?= $filter_status == 'Delivered' ? 'selected' : '' ?>>Delivered</option>
            <option value="Cancelled" <?= $filter_status == 'Cancelled' ? 'selected' : '' ?>>Cancelled</option>
        </select>
        <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Search</button>
    </form>

    <!-- Orders Table -->
    <table class="table table-striped table-bordered">
        <thead class="table-light">
        <tr>
            <th>Order ID</th>
            <th>Customer ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Total Price</th>
            <th>Order Date</th>
            <th>Status</th>
            <th>Payment Method</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id']; ?></td>
                <td><?= $row['user_id']; ?></td>
                <td><?= htmlspecialchars($row['customer_name']); ?></td>
                <td><?= htmlspecialchars($row['customer_email']); ?></td>
                <td>$<?= number_format($row['total_price'], 2); ?></td>
                <td><?= date("M d, Y H:i A", strtotime($row['order_date'])); ?></td>
                <td><span class="badge bg-<?= getStatusClass($row['status']); ?>"><?= $row['status']; ?></span></td>
                <td><?= htmlspecialchars($row['payment_method']); ?></td>
                <td>
                    <a href="view_order_details.php?order_id=<?= $row['id']; ?>" class="btn btn-info btn-sm">
                        <i class="fas fa-eye"></i> View Details
                    </a>
                    <a href="invoice.php?order_id=<?= $row['id']; ?>" class="btn btn-warning btn-sm" target="_blank">
                        <i class="fas fa-file-invoice"></i> Invoice
                    </a>
                    <button class="btn btn-primary btn-sm update-btn"
                            data-id="<?= $row['id']; ?>"
                            data-status="<?= $row['status']; ?>"
                            data-bs-toggle="modal"
                            data-bs-target="#updateOrderModal">
                        <i class="fas fa-edit"></i> Update
                    </button>
                    <button class="btn btn-warning btn-sm cancel-btn" data-id="<?= $row['id']; ?>">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- Update Order Modal -->
<div class="modal fade" id="updateOrderModal" tabindex="-1" aria-labelledby="updateOrderModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="updateOrderForm" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Update Order Status</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="order_id" id="update_order_id">
        <div class="mb-3">
          <label for="update_status" class="form-label">Status</label>
          <select name="status" id="update_status" class="form-control">
            <option value="Pending">Pending</option>
            <option value="Shipped">Shipped</option>
            <option value="Delivered">Delivered</option>
            <option value="Cancelled">Cancelled</option>
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary" type="submit">Update</button>
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
      </div>
    </form>
  </div>
</div>

<script>
$(document).ready(function () {
    $('.update-btn').click(function () {
        $('#update_order_id').val($(this).data('id'));
        $('#update_status').val($(this).data('status'));
    });

    $('#updateOrderForm').submit(function (e) {
        e.preventDefault();
        $.post('update_order.php', $(this).serialize(), function () {
            $('#updateOrderModal').modal('hide');
            location.reload();
        });
    });

    $('.cancel-btn').click(function () {
        const id = $(this).data('id');
        if (confirm('Are you sure you want to cancel this order?')) {
            $.post('cancel_order.php', { order_id: id }, function () {
                location.reload();
            });
        }
    });
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
