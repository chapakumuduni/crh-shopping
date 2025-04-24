<?php
session_start();
require_once('db.php');

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

// Dashboard counts
$total_products = $conn->query("SELECT COUNT(*) AS count FROM products")->fetch_assoc()['count'];
$total_orders = $conn->query("SELECT COUNT(*) AS count FROM orders")->fetch_assoc()['count'];
$total_users = $conn->query("SELECT COUNT(*) AS count FROM users")->fetch_assoc()['count'];

// Order status counts
$status_counts = [
    'Pending' => 0,
    'Shipped' => 0,
    'Delivered' => 0,
    'Cancelled' => 0
];
$status_query = $conn->query("SELECT status, COUNT(*) as count FROM orders GROUP BY status");
while ($row = $status_query->fetch_assoc()) {
    $status = $row['status'];
    $count = $row['count'];
    if (array_key_exists($status, $status_counts)) {
        $status_counts[$status] = $count;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/js/all.min.js"></script>
    <style>
        body {
            background-color: #e3f2fd;
            color: #333;
        }
        .sidebar {
            width: 250px;
            height: 100vh;
            background-color: #90caf9;
            color: white;
            position: fixed;
            padding: 20px;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 10px;
            margin: 5px 0;
            border-radius: 5px;
            transition: 0.3s;
        }
        .sidebar a:hover {
            background-color: #64b5f6;
        }
        .main-content {
            margin-left: 260px;
            padding: 20px;
        }
        .card {
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease-in-out;
            border: none;
            background-color: #bbdefb;
            color: #01579b;
        }
        .card:hover {
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h3 class="text-center">Admin Panel</h3>
        <a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a>
        <a href="products.php"><i class="fas fa-box"></i> Products</a>
        <a href="orders.php"><i class="fas fa-shopping-cart"></i> Orders</a>
        <a href="users.php"><i class="fas fa-users"></i> Users</a>
        <a href="categories.php"><i class="fas fa-tags"></i> Categories</a>
        <a href="customers.php"><i class="fas fa-user-friends"></i> Customers</a> <!-- ✅ Added -->
        <a href="reports.php"><i class="fas fa-chart-line"></i> Reports</a>
        <a href="settings.php"><i class="fas fa-cogs"></i> Settings</a>
        <a href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </div>

    <div class="main-content">
        <h2>Dashboard</h2>
        <div class="row">
            <div class="col-md-4">
                <div class="card text-white mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Total Products</h5>
                        <p class="card-text"><?php echo $total_products; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Total Orders</h5>
                        <p class="card-text"><?php echo $total_orders; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Total Users</h5>
                        <p class="card-text"><?php echo $total_users; ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Status Chart -->
        <div class="row mt-4">
            <div class="col-md-8">
                <canvas id="orderChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Logout Confirmation Modal -->
    <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title text-dark" id="logoutModalLabel">
                        <i class="fas fa-sign-out-alt"></i> Confirm Logout
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center text-dark">
                    Are you sure you want to log out?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <a href="logout.php" class="btn btn-warning">Yes, Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const ctx = document.getElementById('orderChart').getContext('2d');
        const orderChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Pending', 'Shipped', 'Delivered', 'Cancelled'],
                datasets: [{
                    label: 'Order Status',
                    data: [
                        <?= $status_counts['Pending']; ?>,
                        <?= $status_counts['Shipped']; ?>,
                        <?= $status_counts['Delivered']; ?>,
                        <?= $status_counts['Cancelled']; ?>
                    ],
                    backgroundColor: ['#ffca28', '#42a5f5', '#66bb6a', '#ef5350']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>
