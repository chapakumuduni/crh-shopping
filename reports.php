<?php
session_start();
require_once('db.php');

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Fetch order reports
$query = "SELECT status, COUNT(*) as count FROM orders GROUP BY status";
$result = $conn->query($query);
$order_data = [];
while ($row = $result->fetch_assoc()) {
    $order_data[] = $row;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Reports</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="container mt-4">
        <h2>Order Reports</h2>
        <a href="dashboard.php" class="btn btn-primary mb-3"><i class="fas fa-tachometer-alt"></i> Back to Dashboard</a>
        
        <!-- Order Status Chart -->
        <div class="row">
            <div class="col-md-8">
                <canvas id="orderChart"></canvas>
            </div>
        </div>
    </div>

    <script>
        var ctx = document.getElementById('orderChart').getContext('2d');
        var orderData = {
            labels: <?php echo json_encode(array_column($order_data, 'status')); ?>,
            datasets: [{
                label: 'Order Status',
                data: <?php echo json_encode(array_column($order_data, 'count')); ?>,
                backgroundColor: ['blue', 'green', 'yellow', 'red'],
            }]
        };
        var orderChart = new Chart(ctx, {
            type: 'bar',
            data: orderData,
        });
    </script>
</body>
</html>
