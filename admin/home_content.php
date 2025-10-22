<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

include '../config/database.php';
$page_title = "Manage Home Content";
include 'includes/admin_header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_content'])) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $section_title = mysqli_real_escape_string($conn, $_POST['section_title']);
    $section_content = mysqli_real_escape_string($conn, $_POST['section_content']);
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    $update_query = "UPDATE home_content SET section_title = '$section_title', section_content = '$section_content', is_active = $is_active WHERE id = $id";

    if (mysqli_query($conn, $update_query)) {
        $success_message = "Content updated successfully!";
    } else {
        $error_message = "Error updating content: " . mysqli_error($conn);
    }
}

$query = "SELECT * FROM home_content ORDER BY section_order";
$result = mysqli_query($conn, $query);
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-home"></i> Manage Home Page Content</h2>
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
                    <?php if ($result && mysqli_num_rows($result) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <form method="POST" class="border-bottom pb-4 mb-4">
                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                <input type="hidden" name="update_content" value="1">

                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label fw-bold">
                                            Section: <?php echo strtoupper(str_replace('_', ' ', $row['section_key'])); ?>
                                        </label>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Section Title</label>
                                        <input type="text" class="form-control" name="section_title" value="<?php echo htmlspecialchars($row['section_title']); ?>" required>
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Section Content</label>
                                        <textarea class="form-control" name="section_content" rows="4" required><?php echo htmlspecialchars($row['section_content']); ?></textarea>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="is_active" id="active_<?php echo $row['id']; ?>" <?php echo $row['is_active'] ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="active_<?php echo $row['id']; ?>">
                                                Active
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save"></i> Update Content
                                        </button>
                                    </div>
                                </div>
                            </form>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p class="text-center text-muted">No content available. Please run the migration script.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/admin_footer.php'; ?>
