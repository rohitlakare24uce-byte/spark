<?php
$page_title = "Registrations Management";
include 'includes/admin_header.php';
require_once '../config/database.php';

$db = new Database();
$conn = $db->getConnection();

$event_filter = $_GET['event_id'] ?? '';

$events = $conn->query("SELECT id, title FROM events ORDER BY title ASC");

if($event_filter) {
    $stmt = $conn->prepare("SELECT er.*, e.title as event_title, s.first_name, s.middle_name, s.last_name, s.prn, s.email, s.contact_no, s.department, s.year
                            FROM event_registrations er
                            JOIN events e ON er.event_id = e.id
                            JOIN students s ON er.student_id = s.id
                            WHERE er.event_id = ?
                            ORDER BY er.registration_date DESC");
    $stmt->bind_param("i", $event_filter);
    $stmt->execute();
    $registrations = $stmt->get_result();
} else {
    $registrations = $conn->query("SELECT er.*, e.title as event_title, s.first_name, s.middle_name, s.last_name, s.prn, s.email, s.contact_no, s.department, s.year
                                    FROM event_registrations er
                                    JOIN events e ON er.event_id = e.id
                                    JOIN students s ON er.student_id = s.id
                                    ORDER BY er.registration_date DESC");
}
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-users"></i> Registrations Management</h1>
    <div>
        <a href="export_registrations.php?format=pdf<?php echo $event_filter ? '&event_id=' . $event_filter : ''; ?>" class="btn btn-danger">
            <i class="fas fa-file-pdf"></i> Export PDF
        </a>
        <a href="export_registrations.php?format=excel<?php echo $event_filter ? '&event_id=' . $event_filter : ''; ?>" class="btn btn-success">
            <i class="fas fa-file-excel"></i> Export Excel
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm mb-3">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-8">
                <label for="event_id" class="form-label">Filter by Event</label>
                <select name="event_id" id="event_id" class="form-select">
                    <option value="">All Events</option>
                    <?php while($event = $events->fetch_assoc()): ?>
                        <option value="<?php echo $event['id']; ?>" <?php echo $event_filter == $event['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($event['title']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary me-2">
                    <i class="fas fa-filter"></i> Filter
                </button>
                <a href="registrations.php" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Clear
                </a>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-sm">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Student</th>
                        <th>PRN</th>
                        <th>Event</th>
                        <th>Department</th>
                        <th>Year</th>
                        <th>Contact</th>
                        <th>Email</th>
                        <th>Payment</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($registrations && $registrations->num_rows > 0): ?>
                        <?php while($reg = $registrations->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $reg['id']; ?></td>
                                <td><?php echo htmlspecialchars($reg['first_name'] . ' ' . ($reg['middle_name'] ? $reg['middle_name'] . ' ' : '') . $reg['last_name']); ?></td>
                                <td><?php echo htmlspecialchars($reg['prn']); ?></td>
                                <td><?php echo htmlspecialchars($reg['event_title']); ?></td>
                                <td><span class="badge bg-info"><?php echo $reg['department']; ?></span></td>
                                <td><?php echo $reg['year']; ?></td>
                                <td><?php echo htmlspecialchars($reg['contact_no']); ?></td>
                                <td><?php echo htmlspecialchars($reg['email']); ?></td>
                                <td><span class="badge bg-<?php echo $reg['payment_status'] == 'completed' ? 'success' : 'warning'; ?>">
                                    <?php echo ucfirst($reg['payment_status']); ?>
                                </span></td>
                                <td><?php echo date('d M Y', strtotime($reg['registration_date'])); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="10" class="text-center">No registrations found</td>
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
