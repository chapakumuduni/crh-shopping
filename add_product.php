<?php
session_start();
require_once('db.php');

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

$message = "";

// Ensure the uploads directory exists
$upload_dir = "uploads/";
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// Fetch categories
$categories_query = "SELECT * FROM categories";
$categories_result = $conn->query($categories_query);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $category_id = $_POST['category'];
    $stock_quantity = $_POST['stock_quantity'];
    $status = $_POST['status'];

    // Handle Image Upload
    $image_name = NULL;
    if (!empty($_FILES['image']['name'])) {
        $image_name = time() . '_' . basename($_FILES['image']['name']);
        $image_path = $upload_dir . $image_name;
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $image_path)) {
            $message = "Error uploading file.";
        }
    }

    // Insert product into the database
    $query = "INSERT INTO products (name, price, description, category_id, stock_quantity, status, image)
              VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sdsisis", $name, $price, $description, $category_id, $stock_quantity, $status, $image_name);

    if ($stmt->execute()) {
        $message = "Product added successfully!";
        header("Location: products.php");
        exit();
    } else {
        $message = "Error adding product.";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Product</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <h2>Add Product</h2>

        <?php if ($message): ?>
            <div class="alert alert-info"><?php echo $message; ?></div>
        <?php endif; ?>

        <div class="d-flex mb-3">
            <a href="dashboard.php" class="btn btn-secondary me-2"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
            <a href="products.php" class="btn btn-primary"><i class="fas fa-box"></i> Manage Products</a>
        </div>

        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Product Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Price</label>
                <input type="number" step="0.01" name="price" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Stock Quantity</label>
                <input type="number" name="stock_quantity" class="form-control" required min="0">
            </div>

            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-control" required>
                    <option value="Active">Active</option>
                    <option value="Inactive">Inactive</option>
                    <option value="Archived">Archived</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control"></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Category</label>
                <select name="category" class="form-control" required>
                    <?php while ($category = $categories_result->fetch_assoc()) { ?>
                        <option value="<?php echo $category['id']; ?>">
                            <?php echo $category['name']; ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Product Image</label>
                <input type="file" name="image" class="form-control">
            </div>

            <button type="submit" class="btn btn-success">Add Product</button>
        </form>
    </div>
</body>
</html>
