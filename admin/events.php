<?php 
$page_title = "Events Management";
include 'includes/admin_header.php'; 
require_once '../config/database.php';

$db = new Database();
$conn = $db->getConnection();

if(isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM events WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    if($stmt->execute()) {
        $success = "Event deleted successfully";
    }
    $stmt->close();
}

$events = $conn->query("SELECT * FROM events ORDER BY event_date DESC");
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-calendar-alt"></i> Events Management</h1>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEventModal">
        <i class="fas fa-plus"></i> Add New Event
    </button>
</div>

<?php if(isset($success)): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle"></i> <?php echo $success; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Date</th>
                        <th>Venue</th>
                        <th>Fee</th>
                        <th>Status</th>
                        <th>Registrations</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($events && $events->num_rows > 0): ?>
                        <?php while($event = $events->fetch_assoc()): ?>
                            <?php
                            $reg_count = $conn->query("SELECT COUNT(*) as count FROM event_registrations WHERE event_id = " . $event['id'])->fetch_assoc()['count'];
                            ?>
                            <tr>
                                <td><?php echo $event['id']; ?></td>
                                <td><?php echo htmlspecialchars($event['title']); ?></td>
                                <td><?php echo date('d M Y', strtotime($event['event_date'])); ?></td>
                                <td><?php echo htmlspecialchars($event['venue']); ?></td>
                                <td>₹<?php echo number_format($event['registration_fee'], 2); ?></td>
                                <td><span class="badge bg-<?php echo $event['status'] == 'upcoming' ? 'primary' : ($event['status'] == 'ongoing' ? 'warning' : 'success'); ?>">
                                    <?php echo ucfirst($event['status']); ?>
                                </span></td>
                                <td><?php echo $reg_count; ?>/<?php echo $event['max_participants']; ?></td>
                                <td>
                                    <a href="edit_event.php?id=<?php echo $event['id']; ?>" class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="?delete=<?php echo $event['id']; ?>" class="btn btn-sm btn-danger" 
                                       onclick="return confirmDelete('Delete this event and all registrations?');">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center">No events found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="addEventModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Add New Event</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="add_event.php" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Event Title *</label>
                        <input type="text" class="form-control" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description *</label>
                        <textarea class="form-control" name="description" rows="3" required></textarea>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Event Date *</label>
                            <input type="date" class="form-control" name="event_date" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Event Time *</label>
                            <input type="time" class="form-control" name="event_time" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Venue *</label>
                        <input type="text" class="form-control" name="venue" required>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Registration Fee *</label>
                            <input type="number" class="form-control" name="registration_fee" min="0" step="0.01" value="0" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Max Participants *</label>
                            <input type="number" class="form-control" name="max_participants" min="1" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Event Image URL</label>
                        <input type="url" class="form-control" name="image">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status *</label>
                        <select class="form-select" name="status" required>
                            <option value="upcoming">Upcoming</option>
                            <option value="ongoing">Ongoing</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Event</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php 
$conn->close();
include 'includes/admin_footer.php'; 
?>
