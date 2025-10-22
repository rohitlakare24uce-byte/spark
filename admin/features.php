<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

include '../config/database.php';
$page_title = "Manage Features";
include 'includes/admin_header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_feature'])) {
        $icon = mysqli_real_escape_string($conn, $_POST['icon']);
        $title = mysqli_real_escape_string($conn, $_POST['title']);
        $description = mysqli_real_escape_string($conn, $_POST['description']);
        $display_order = mysqli_real_escape_string($conn, $_POST['display_order']);

        $insert_query = "INSERT INTO features (icon, title, description, display_order) VALUES ('$icon', '$title', '$description', $display_order)";

        if (mysqli_query($conn, $insert_query)) {
            $success_message = "Feature added successfully!";
        } else {
            $error_message = "Error: " . mysqli_error($conn);
        }
    } elseif (isset($_POST['update_feature'])) {
        $id = mysqli_real_escape_string($conn, $_POST['id']);
        $icon = mysqli_real_escape_string($conn, $_POST['icon']);
        $title = mysqli_real_escape_string($conn, $_POST['title']);
        $description = mysqli_real_escape_string($conn, $_POST['description']);
        $display_order = mysqli_real_escape_string($conn, $_POST['display_order']);
        $is_active = isset($_POST['is_active']) ? 1 : 0;

        $update_query = "UPDATE features SET icon = '$icon', title = '$title', description = '$description', display_order = $display_order, is_active = $is_active WHERE id = $id";

        if (mysqli_query($conn, $update_query)) {
            $success_message = "Feature updated successfully!";
        } else {
            $error_message = "Error: " . mysqli_error($conn);
        }
    } elseif (isset($_POST['delete_feature'])) {
        $id = mysqli_real_escape_string($conn, $_POST['id']);
        $delete_query = "DELETE FROM features WHERE id = $id";

        if (mysqli_query($conn, $delete_query)) {
            $success_message = "Feature deleted successfully!";
        } else {
            $error_message = "Error: " . mysqli_error($conn);
        }
    }
}

$query = "SELECT * FROM features ORDER BY display_order";
$result = mysqli_query($conn, $query);
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-star"></i> Manage Features (Why Join SPARK)</h2>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addFeatureModal">
                    <i class="fas fa-plus"></i> Add Feature
                </button>
            </div>

            <?php if (isset($success_message)): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo $success_message; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (isset($error_message)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo $error_message; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Order</th>
                                    <th>Icon</th>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($result && mysqli_num_rows($result) > 0): ?>
                                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                        <tr>
                                            <td><?php echo $row['display_order']; ?></td>
                                            <td><i class="fas <?php echo htmlspecialchars($row['icon']); ?> fa-2x"></i></td>
                                            <td><?php echo htmlspecialchars($row['title']); ?></td>
                                            <td><?php echo substr(htmlspecialchars($row['description']), 0, 50) . '...'; ?></td>
                                            <td>
                                                <?php if ($row['is_active']): ?>
                                                    <span class="badge bg-success">Active</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">Inactive</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editFeatureModal<?php echo $row['id']; ?>">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteFeatureModal<?php echo $row['id']; ?>">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>

                                        <div class="modal fade" id="editFeatureModal<?php echo $row['id']; ?>" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Edit Feature</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <form method="POST">
                                                        <div class="modal-body">
                                                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                                            <input type="hidden" name="update_feature" value="1">

                                                            <div class="mb-3">
                                                                <label class="form-label">Icon Class (Font Awesome)</label>
                                                                <input type="text" class="form-control" name="icon" value="<?php echo htmlspecialchars($row['icon']); ?>" placeholder="e.g., fa-users" required>
                                                                <small class="text-muted">Visit <a href="https://fontawesome.com/icons" target="_blank">FontAwesome</a> for icons</small>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label class="form-label">Title</label>
                                                                <input type="text" class="form-control" name="title" value="<?php echo htmlspecialchars($row['title']); ?>" required>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label class="form-label">Description</label>
                                                                <textarea class="form-control" name="description" rows="3" required><?php echo htmlspecialchars($row['description']); ?></textarea>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label class="form-label">Display Order</label>
                                                                <input type="number" class="form-control" name="display_order" value="<?php echo $row['display_order']; ?>" required>
                                                            </div>

                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" name="is_active" id="active_<?php echo $row['id']; ?>" <?php echo $row['is_active'] ? 'checked' : ''; ?>>
                                                                <label class="form-check-label" for="active_<?php echo $row['id']; ?>">
                                                                    Active
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-primary">Update</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal fade" id="deleteFeatureModal<?php echo $row['id']; ?>" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-danger text-white">
                                                        <h5 class="modal-title">Delete Feature</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <form method="POST">
                                                        <div class="modal-body">
                                                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                                            <input type="hidden" name="delete_feature" value="1">
                                                            <p>Are you sure you want to delete <strong><?php echo htmlspecialchars($row['title']); ?></strong>?</p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-danger">Delete</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">No features available.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addFeatureModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Feature</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="add_feature" value="1">

                    <div class="mb-3">
                        <label class="form-label">Icon Class (Font Awesome)</label>
                        <input type="text" class="form-control" name="icon" placeholder="e.g., fa-users" required>
                        <small class="text-muted">Visit <a href="https://fontawesome.com/icons" target="_blank">FontAwesome</a> for icons</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" class="form-control" name="title" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" rows="3" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Display Order</label>
                        <input type="number" class="form-control" name="display_order" value="0" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Feature</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/admin_footer.php'; ?>
