<?php 
$page_title = "Team Management";
include 'includes/admin_header.php'; 
require_once '../config/database.php';

$db = new Database();
$conn = $db->getConnection();

if(isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM team_members WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $stmt->close();
}

$members = $conn->query("SELECT * FROM team_members ORDER BY display_order ASC");
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-user-friends"></i> Team Management</h1>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMemberModal">
        <i class="fas fa-plus"></i> Add Team Member
    </button>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Photo</th>
                        <th>Name</th>
                        <th>Post</th>
                        <th>Email</th>
                        <th>LinkedIn</th>
                        <th>GitHub</th>
                        <th>Order</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($members && $members->num_rows > 0): ?>
                        <?php while($member = $members->fetch_assoc()): ?>
                            <tr>
                                <td>
                                    <?php if($member['photo']): ?>
                                        <img src="<?php echo $member['photo']; ?>" width="50" height="50" class="rounded-circle">
                                    <?php else: ?>
                                        <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($member['name']); ?>&size=50" class="rounded-circle">
                                    <?php endif; ?>
                                </td>
                                <td><?php echo htmlspecialchars($member['name']); ?></td>
                                <td><?php echo htmlspecialchars($member['post']); ?></td>
                                <td><?php echo htmlspecialchars($member['email']); ?></td>
                                <td><?php echo $member['linkedin'] ? '<i class="fas fa-check text-success"></i>' : '-'; ?></td>
                                <td><?php echo $member['github'] ? '<i class="fas fa-check text-success"></i>' : '-'; ?></td>
                                <td><?php echo $member['display_order']; ?></td>
                                <td>
                                    <a href="edit_team.php?id=<?php echo $member['id']; ?>" class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="?delete=<?php echo $member['id']; ?>" class="btn btn-sm btn-danger" 
                                       onclick="return confirmDelete();">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center">No team members found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="addMemberModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Add Team Member</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="add_team.php" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Name *</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Post/Position *</label>
                        <input type="text" class="form-control" name="post" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Photo URL</label>
                        <input type="url" class="form-control" name="photo">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">LinkedIn URL</label>
                        <input type="url" class="form-control" name="linkedin">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">GitHub URL</label>
                        <input type="url" class="form-control" name="github">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Bio</label>
                        <textarea class="form-control" name="bio" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Display Order</label>
                        <input type="number" class="form-control" name="display_order" value="0">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Member</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php 
$conn->close();
include 'includes/admin_footer.php'; 
?>
