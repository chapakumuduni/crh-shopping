<?php
session_start();
require_once('db.php');

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

// Load product and categories
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM products WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    $stmt->close();

    $categories_query = "SELECT * FROM categories";
    $categories_result = $conn->query($categories_query);
} else {
    header("Location: products.php");
    exit();
}

// Handle update form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $category_id = $_POST['category'];
    $stock_quantity = $_POST['stock_quantity'];
    $status = $_POST['status'];

    // Handle Image Upload
    if (!empty($_FILES['image']['name'])) {
        $image_name = time() . '_' . basename($_FILES['image']['name']);
        $image_path = "uploads/" . $image_name;
        move_uploaded_file($_FILES['image']['tmp_name'], $image_path);

        $query = "UPDATE products SET name = ?, price = ?, description = ?, category_id = ?, stock_quantity = ?, status = ?, image = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sdsisssi", $name, $price, $description, $category_id, $stock_quantity, $status, $image_name, $id);
    } else {
        $query = "UPDATE products SET name = ?, price = ?, description = ?, category_id = ?, stock_quantity = ?, status = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sdsissi", $name, $price, $description, $category_id, $stock_quantity, $status, $id);
    }

    if ($stmt->execute()) {
        $_SESSION['message'] = "✅ Product updated successfully!";
        header("Location: products.php");
        exit();
    } else {
        $_SESSION['message'] = "❌ Error updating product.";
        header("Location: products.php");
        exit();
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Product</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-4">
    <h2>Edit Product</h2>

    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">Product Name</label>
            <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($product['name']); ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Price</label>
            <input type="number" step="0.01" name="price" class="form-control" value="<?php echo $product['price']; ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Stock Quantity</label>
            <input type="number" name="stock_quantity" class="form-control" value="<?php echo $product['stock_quantity']; ?>" min="0" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-control" required>
                <option value="Active" <?php if ($product['status'] === 'Active') echo 'selected'; ?>>Active</option>
                <option value="Inactive" <?php if ($product['status'] === 'Inactive') echo 'selected'; ?>>Inactive</option>
                <option value="Archived" <?php if ($product['status'] === 'Archived') echo 'selected'; ?>>Archived</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control"><?php echo htmlspecialchars($product['description']); ?></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Category</label>
            <select name="category" class="form-control">
                <?php while ($category = $categories_result->fetch_assoc()) { ?>
                    <option value="<?php echo $category['id']; ?>" <?php echo ($category['id'] == $product['category_id']) ? 'selected' : ''; ?>>
                        <?php echo $category['name']; ?>
                    </option>
                <?php } ?>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Product Image</label>
            <input type="file" name="image" class="form-control">
            <?php if (!empty($product['image'])): ?>
                <img src="uploads/<?php echo $product['image']; ?>" width="100" class="mt-2">
            <?php endif; ?>
        </div>
        <button type="submit" class="btn btn-primary">Update Product</button>
    </form>
</div>
</body>
</html>
