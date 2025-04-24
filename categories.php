<?php
session_start();
require_once('db.php');

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

$categories_query = "SELECT * FROM categories";
$categories_result = $conn->query($categories_query);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Categories</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/js/all.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body { background-color: #e3f2fd; color: #333; }
        .container {
            background-color: white; padding: 20px; border-radius: 10px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }
        .btn { border-radius: 8px; }
        .table-striped tbody tr:nth-of-type(odd) { background-color: #f1f8ff; }
        #alertBox {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1055;
        }
    </style>
</head>
<body>
<div class="container mt-4">
    <h2 class="mb-3">Manage Categories</h2>
    <div class="d-flex mb-3">
        <a href="dashboard.php" class="btn btn-secondary me-2"><i class="fas fa-home"></i> Dashboard</a>
        <a href="products.php" class="btn btn-primary me-2"><i class="fas fa-box"></i> Products</a>
        <a href="add_categories.php" class="btn btn-success"><i class="fas fa-plus"></i> Add Category</a>
    </div>

    <div class="mb-3">
        <input type="text" id="categorySearch" class="form-control" placeholder="🔍 Search categories by name...">
    </div>

    <div id="alertBox"></div>

    <table class="table table-striped">
        <thead>
        <tr>
            <th>ID</th><th>Name</th><th>Description</th><th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($category = $categories_result->fetch_assoc()) { ?>
            <tr>
                <td><?= $category['id'] ?></td>
                <td><?= htmlspecialchars($category['name']) ?></td>
                <td><?= htmlspecialchars($category['description']) ?></td>
                <td>
                    <button class="btn btn-primary btn-sm edit-btn"
                            data-id="<?= $category['id'] ?>"
                            data-name="<?= htmlspecialchars($category['name']) ?>"
                            data-description="<?= htmlspecialchars($category['description']) ?>"
                            data-bs-toggle="modal" data-bs-target="#editCategoryModal">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                    <button class="btn btn-danger btn-sm delete-btn"
                            data-id="<?= $category['id'] ?>"
                            data-bs-toggle="modal" data-bs-target="#deleteCategoryModal">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>

<!-- DELETE MODAL -->
<div class="modal fade" id="deleteCategoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="deleteCategoryForm" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this category?
                <input type="hidden" name="delete_id" id="delete_category_id">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-danger">Yes, Delete</button>
            </div>
        </form>
    </div>
</div>

<!-- EDIT MODAL -->
<div class="modal fade" id="editCategoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="editCategoryForm" class="modal-content">
            <div class="modal-header">
                <h5>Edit Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="edit_category_id" name="id">
                <div class="mb-3">
                    <label>Name</label>
                    <input type="text" id="edit_name" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Description</label>
                    <textarea id="edit_description" name="description" class="form-control"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>
    </div>
</div>

<!-- JS Scripts -->
<script>
    $(document).ready(function () {
        function showAlert(message, type = 'success') {
            const alertHTML = `
                <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>`;
            $('#alertBox').html(alertHTML);
            setTimeout(() => $('.alert').alert('close'), 4000);
        }

        // Live Search Filter
        $('#categorySearch').on('keyup', function () {
            const value = $(this).val().toLowerCase();
            $('table tbody tr').filter(function () {
                $(this).toggle($(this).find('td:nth-child(2)').text().toLowerCase().indexOf(value) > -1);
            });
        });

        // Delete
        $('.delete-btn').click(function () {
            $('#delete_category_id').val($(this).data('id'));
        });

        $('#deleteCategoryForm').submit(function (e) {
            e.preventDefault();
            $.post('delete_category.php', $(this).serialize(), function () {
                $('#deleteCategoryModal').modal('hide');
                showAlert('✅ Category deleted successfully!');
                setTimeout(() => location.reload(), 1500);
            }).fail(function () {
                showAlert('❌ Error deleting category.', 'danger');
            });
        });

        // Edit
        $('.edit-btn').click(function () {
            $('#edit_category_id').val($(this).data('id'));
            $('#edit_name').val($(this).data('name'));
            $('#edit_description').val($(this).data('description'));
        });

        $('#editCategoryForm').submit(function (e) {
            e.preventDefault();
            $.post('update_category.php', $(this).serialize(), function () {
                $('#editCategoryModal').modal('hide');
                showAlert('✅ Category updated successfully!');
                setTimeout(() => location.reload(), 1500);
            }).fail(function () {
                showAlert('❌ Error updating category.', 'danger');
            });
        });
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
