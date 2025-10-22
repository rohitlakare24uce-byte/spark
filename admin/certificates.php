<?php 
$page_title = "Certificates Management";
include 'includes/admin_header.php'; 
require_once '../config/database.php';

$db = new Database();
$conn = $db->getConnection();

if(isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM certificates WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $stmt->close();
}

$certificates = $conn->query("SELECT c.*, s.first_name, s.last_name, s.prn, e.title as event_title 
                               FROM certificates c 
                               JOIN students s ON c.student_id = s.id 
                               LEFT JOIN events e ON c.event_id = e.id 
                               ORDER BY c.created_at DESC");
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-certificate"></i> Certificates Management</h1>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCertificateModal">
        <i class="fas fa-plus"></i> Add Certificate
    </button>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Student</th>
                        <th>PRN</th>
                        <th>Certificate Title</th>
                        <th>Event</th>
                        <th>Code</th>
                        <th>Issue Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($certificates && $certificates->num_rows > 0): ?>
                        <?php while($cert = $certificates->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $cert['id']; ?></td>
                                <td><?php echo htmlspecialchars($cert['first_name'] . ' ' . $cert['last_name']); ?></td>
                                <td><?php echo htmlspecialchars($cert['prn']); ?></td>
                                <td><?php echo htmlspecialchars($cert['certificate_title']); ?></td>
                                <td><?php echo $cert['event_title'] ? htmlspecialchars($cert['event_title']) : '-'; ?></td>
                                <td><code><?php echo $cert['certificate_code']; ?></code></td>
                                <td><?php echo date('d M Y', strtotime($cert['issue_date'])); ?></td>
                                <td>
                                    <a href="?delete=<?php echo $cert['id']; ?>" class="btn btn-sm btn-danger" 
                                       onclick="return confirmDelete();">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center">No certificates found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="addCertificateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Add Certificate</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="add_certificate.php" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Student PRN *</label>
                        <input type="text" class="form-control" name="prn" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Certificate Title *</label>
                        <input type="text" class="form-control" name="certificate_title" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Event (Optional)</label>
                        <select class="form-select" name="event_id">
                            <option value="">None</option>
                            <?php 
                            $events = $conn->query("SELECT id, title FROM events ORDER BY event_date DESC");
                            while($event = $events->fetch_assoc()): 
                            ?>
                                <option value="<?php echo $event['id']; ?>"><?php echo htmlspecialchars($event['title']); ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Issue Date *</label>
                        <input type="date" class="form-control" name="issue_date" value="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Certificate File URL</label>
                        <input type="url" class="form-control" name="certificate_file">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Certificate</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php 
$conn->close();
include 'includes/admin_footer.php'; 
?>
