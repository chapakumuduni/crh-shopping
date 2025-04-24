<?php
session_start();
require_once('db.php');

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

$upload_dir = "uploads/";
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// Handle single product delete
if (isset($_GET['delete_id']) && is_numeric($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        $_SESSION['message'] = "✅ Product deleted successfully!";
    } else {
        $_SESSION['message'] = "❌ Error deleting product.";
    }
    $stmt->close();
    header("Location: products.php");
    exit();
}

// Handle bulk delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bulk_delete']) && isset($_POST['product_ids'])) {
    $ids = array_map('intval', $_POST['product_ids']);
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $types = str_repeat('i', count($ids));

    $stmt = $conn->prepare("DELETE FROM products WHERE id IN ($placeholders)");
    $stmt->bind_param($types, ...$ids);

    if ($stmt->execute()) {
        $_SESSION['message'] = "✅ Selected products deleted successfully!";
    } else {
        $_SESSION['message'] = "❌ Error deleting selected products.";
    }
    $stmt->close();
    header("Location: products.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Products</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/js/all.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            background-color: #e3f2fd;
            color: #333;
        }
        .container {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }
        .btn {
            border-radius: 8px;
        }
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #f1f8ff;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h2 class="mb-3">Manage Products</h2>

        <!-- ✅ Session Success or Error Alert -->
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo $_SESSION['message']; unset($_SESSION['message']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="mb-3">
            <a href="dashboard.php" class="btn btn-outline-primary"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
            <a href="add_product.php" class="btn btn-primary"><i class="fas fa-plus"></i> Add Product</a>
            <a href="add_categories.php" class="btn btn-info"><i class="fas fa-folder-plus"></i> Add Category</a>
        </div>

        <form method="POST">
            <table class="table table-striped table-bordered">
                <thead class="table-light">
                    <tr>
                        <th><input type="checkbox" id="select_all"></th>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Status</th>
                        <th>Description</th>
                        <th>Category</th>
                        <th>Image</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = "SELECT p.*, c.name AS category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id";
                    $result = $conn->query($query);
                    while ($row = $result->fetch_assoc()) {
                        $badge_class = $row['status'] === 'Active' ? 'success' :
                                       ($row['status'] === 'Inactive' ? 'warning' : 'secondary');
                    ?>
                        <tr>
                            <td><input type="checkbox" name="product_ids[]" value="<?php echo $row['id']; ?>"></td>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td>$<?php echo number_format($row['price'], 2); ?></td>
                            <td><?php echo (int)$row['stock_quantity']; ?></td>
                            <td><span class="badge bg-<?php echo $badge_class; ?>"><?php echo $row['status']; ?></span></td>
                            <td><?php echo htmlspecialchars($row['description']); ?></td>
                            <td><?php echo $row['category_name'] ?? 'Unknown'; ?></td>
                            <td>
                                <?php if (!empty($row['image'])): ?>
                                    <img src="uploads/<?php echo $row['image']; ?>" width="50" height="50" class="rounded">
                                <?php else: ?>
                                    No Image
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="edit_product.php?id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i> Edit</a>
                                <a href="?delete_id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this product?');"><i class="fas fa-trash"></i> Delete</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <button type="submit" name="bulk_delete" class="btn btn-danger mt-2">Delete Selected</button>
        </form>
    </div>

    <script>
        $(document).ready(function () {
            $('#select_all').click(function () {
                $('input[name="product_ids[]"]').prop('checked', this.checked);
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
