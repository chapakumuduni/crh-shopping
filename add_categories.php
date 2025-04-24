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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    
    // Handle Image Upload
    $image_name = NULL;
    if (!empty($_FILES['image']['name'])) {
        $image_name = time() . '_' . basename($_FILES['image']['name']);
        $image_path = $upload_dir . $image_name;
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $image_path)) {
            $message = "Error uploading file.";
        }
    }
    
    // Insert category details into the database
    $query = "INSERT INTO categories (name, description, image) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $name, $description, $image_name);
    
    if ($stmt->execute()) {
        $message = "Category added successfully!";
    } else {
        $message = "Error adding category.";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Category</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/js/all.min.js"></script>
    <style>
        body {
            background-color: #e3f2fd; /* Light Blue */
            color: #333;
        }
        .container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            margin: auto;
            margin-top: 50px;
        }
        .btn-custom {
            background-color: #42a5f5; /* Light Blue */
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 8px;
            transition: 0.3s ease-in-out;
        }
        .btn-custom:hover {
            background-color: #1e88e5; /* Darker Blue */
        }
        .form-control {
            border-radius: 8px;
        }
        .alert {
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="text-center mb-4">Add Category</h2>
        
        <?php if ($message): ?>
            <div class="alert alert-info text-center"> <?php echo $message; ?> </div>
        <?php endif; ?>
        
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Category Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Category Description</label>
                <textarea name="description" class="form-control"></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Category Image</label>
                <input type="file" name="image" class="form-control">
            </div>
            <button type="submit" class="btn btn-custom w-100"><i class="fas fa-plus"></i> Add Category</button>
        </form>

        <!-- Back to Categories Button -->
        <div class="text-center mt-3">
            <a href="categories.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back to Categories</a>
        </div>
    </div>

    <!-- Bootstrap Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
