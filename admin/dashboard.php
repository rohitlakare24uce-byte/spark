<?php 
$page_title = "Dashboard";
include 'includes/admin_header.php'; 
require_once '../config/database.php';

$db = new Database();
$conn = $db->getConnection();

$stats_query = "SELECT 
    (SELECT COUNT(*) FROM events) as total_events,
    (SELECT COUNT(*) FROM event_registrations) as total_registrations,
    (SELECT COUNT(*) FROM students) as total_students,
    (SELECT COUNT(*) FROM team_members) as total_team_members,
    (SELECT COUNT(*) FROM certificates) as total_certificates,
    (SELECT COUNT(*) FROM contact_submissions WHERE status = 'new') as new_contacts,
    (SELECT SUM(amount) FROM payments WHERE status = 'success') as total_revenue";
$stats_result = $conn->query($stats_query);
$stats = $stats_result->fetch_assoc();

$upcoming_events = $conn->query("SELECT * FROM events WHERE status = 'upcoming' ORDER BY event_date ASC LIMIT 5");
$recent_registrations = $conn->query("SELECT er.*, e.title, s.first_name, s.last_name, s.prn 
                                       FROM event_registrations er 
                                       JOIN events e ON er.event_id = e.id 
                                       JOIN students s ON er.student_id = s.id 
                                       ORDER BY er.registration_date DESC LIMIT 5");
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-tachometer-alt"></i> Dashboard</h1>
    <div class="text-muted">
        <i class="fas fa-calendar"></i> <?php echo date('l, F d, Y'); ?>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="mb-0">Total Events</h6>
                        <h2 class="mt-2 mb-0"><?php echo $stats['total_events']; ?></h2>
                    </div>
                    <div>
                        <i class="fas fa-calendar-alt fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card border-0 shadow-sm bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="mb-0">Registrations</h6>
                        <h2 class="mt-2 mb-0"><?php echo $stats['total_registrations']; ?></h2>
                    </div>
                    <div>
                        <i class="fas fa-users fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card border-0 shadow-sm bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="mb-0">Students</h6>
                        <h2 class="mt-2 mb-0"><?php echo $stats['total_students']; ?></h2>
                    </div>
                    <div>
                        <i class="fas fa-user-graduate fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card border-0 shadow-sm bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="mb-0">Revenue</h6>
                        <h2 class="mt-2 mb-0">₹<?php echo number_format($stats['total_revenue'] ?: 0, 2); ?></h2>
                    </div>
                    <div>
                        <i class="fas fa-rupee-sign fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-calendar-alt"></i> Upcoming Events</h5>
            </div>
            <div class="card-body">
                <?php if($upcoming_events && $upcoming_events->num_rows > 0): ?>
                    <div class="list-group list-group-flush">
                        <?php while($event = $upcoming_events->fetch_assoc()): ?>
                            <div class="list-group-item px-0">
                                <div class="d-flex justify-content-between">
                                    <h6 class="mb-1"><?php echo htmlspecialchars($event['title']); ?></h6>
                                    <small><?php echo date('d M', strtotime($event['event_date'])); ?></small>
                                </div>
                                <small class="text-muted">
                                    <i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($event['venue']); ?>
                                </small>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <p class="text-muted">No upcoming events</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-user-plus"></i> Recent Registrations</h5>
            </div>
            <div class="card-body">
                <?php if($recent_registrations && $recent_registrations->num_rows > 0): ?>
                    <div class="list-group list-group-flush">
                        <?php while($reg = $recent_registrations->fetch_assoc()): ?>
                            <div class="list-group-item px-0">
                                <div class="d-flex justify-content-between">
                                    <h6 class="mb-1"><?php echo htmlspecialchars($reg['first_name'] . ' ' . $reg['last_name']); ?></h6>
                                    <small><?php echo date('d M', strtotime($reg['registration_date'])); ?></small>
                                </div>
                                <small class="text-muted">
                                    <?php echo htmlspecialchars($reg['title']); ?>
                                    <span class="badge bg-<?php echo $reg['payment_status'] == 'completed' ? 'success' : 'warning'; ?> ms-2">
                                        <?php echo ucfirst($reg['payment_status']); ?>
                                    </span>
                                </small>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <p class="text-muted">No recent registrations</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mt-4">
    <div class="col-md-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h5><i class="fas fa-chart-bar"></i> Quick Stats</h5>
                <div class="row mt-3">
                    <div class="col-md-4">
                        <p class="mb-1 text-muted">Team Members</p>
                        <h4><?php echo $stats['total_team_members']; ?></h4>
                    </div>
                    <div class="col-md-4">
                        <p class="mb-1 text-muted">Certificates Issued</p>
                        <h4><?php echo $stats['total_certificates']; ?></h4>
                    </div>
                    <div class="col-md-4">
                        <p class="mb-1 text-muted">New Contact Messages</p>
                        <h4><?php echo $stats['new_contacts']; ?></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
$conn->close();
include 'includes/admin_footer.php'; 
?>
