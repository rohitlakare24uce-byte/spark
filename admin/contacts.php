<?php 
$page_title = "Contact Messages";
include 'includes/admin_header.php'; 
require_once '../config/database.php';

$db = new Database();
$conn = $db->getConnection();

if(isset($_GET['mark_read'])) {
    $id = $_GET['mark_read'];
    $stmt = $conn->prepare("UPDATE contact_submissions SET status = 'read' WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

if(isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM contact_submissions WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

$contacts = $conn->query("SELECT * FROM contact_submissions ORDER BY submitted_at DESC");
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-envelope"></i> Contact Messages</h1>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Subject</th>
                        <th>Message</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($contacts && $contacts->num_rows > 0): ?>
                        <?php while($contact = $contacts->fetch_assoc()): ?>
                            <tr class="<?php echo $contact['status'] == 'new' ? 'table-warning' : ''; ?>">
                                <td><?php echo $contact['id']; ?></td>
                                <td><?php echo htmlspecialchars($contact['name']); ?></td>
                                <td><?php echo htmlspecialchars($contact['email']); ?></td>
                                <td><?php echo htmlspecialchars($contact['subject']); ?></td>
                                <td><?php echo substr(htmlspecialchars($contact['message']), 0, 50) . '...'; ?></td>
                                <td>
                                    <span class="badge bg-<?php echo $contact['status'] == 'new' ? 'warning' : ($contact['status'] == 'read' ? 'info' : 'success'); ?>">
                                        <?php echo ucfirst($contact['status']); ?>
                                    </span>
                                </td>
                                <td><?php echo date('d M Y', strtotime($contact['submitted_at'])); ?></td>
                                <td>
                                    <?php if($contact['status'] == 'new'): ?>
                                        <a href="?mark_read=<?php echo $contact['id']; ?>" class="btn btn-sm btn-info">
                                            <i class="fas fa-check"></i>
                                        </a>
                                    <?php endif; ?>
                                    <a href="mailto:<?php echo $contact['email']; ?>?subject=Re: <?php echo urlencode($contact['subject']); ?>" class="btn btn-sm btn-primary">
                                        <i class="fas fa-reply"></i>
                                    </a>
                                    <a href="?delete=<?php echo $contact['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirmDelete();">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center">No contact messages found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php 
$conn->close();
include 'includes/admin_footer.php'; 
?>
