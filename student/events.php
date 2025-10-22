<?php 
$page_title = "Events";
include '../includes/header.php'; 
require_once '../config/database.php';

$db = new Database();
$conn = $db->getConnection();

$query = "SELECT * FROM events WHERE status != 'completed' ORDER BY event_date ASC";
$result = $conn->query($query);
?>

<main class="container my-5">
    <div class="text-center mb-5">
        <h1 class="display-4 fw-bold">Upcoming Events</h1>
        <p class="lead">Explore our exciting lineup of workshops, hackathons, and seminars</p>
    </div>

    <?php if(isset($_GET['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Registration successful! Please complete the payment to confirm your spot.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row g-4">
        <?php if($result && $result->num_rows > 0): ?>
            <?php while($event = $result->fetch_assoc()): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card event-card h-100">
                        <?php if($event['image']): ?>
                            <img src="<?php echo $event['image']; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($event['title']); ?>">
                        <?php else: ?>
                            <img src="https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=400&h=200&fit=crop" class="card-img-top" alt="Event">
                        <?php endif; ?>
                        
                        <div class="card-body">
                            <h5 class="card-title fw-bold"><?php echo htmlspecialchars($event['title']); ?></h5>
                            <p class="card-text"><?php echo substr(htmlspecialchars($event['description']), 0, 100) . '...'; ?></p>
                            
                            <div class="mb-2">
                                <small class="text-muted">
                                    <i class="fas fa-calendar"></i> <?php echo date('d M Y', strtotime($event['event_date'])); ?>
                                </small><br>
                                <small class="text-muted">
                                    <i class="fas fa-clock"></i> <?php echo date('h:i A', strtotime($event['event_time'])); ?>
                                </small><br>
                                <small class="text-muted">
                                    <i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($event['venue']); ?>
                                </small>
                            </div>
                            
                            <?php if($event['registration_fee'] > 0): ?>
                                <p class="fw-bold text-success mb-2">
                                    <i class="fas fa-rupee-sign"></i> <?php echo number_format($event['registration_fee'], 2); ?>
                                </p>
                            <?php else: ?>
                                <p class="fw-bold text-success mb-2">Free Event</p>
                            <?php endif; ?>
                            
                            <span class="badge bg-<?php echo $event['status'] == 'upcoming' ? 'primary' : 'warning'; ?> mb-3">
                                <?php echo ucfirst($event['status']); ?>
                            </span>
                            
                            <div class="d-grid gap-2">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#registerModal<?php echo $event['id']; ?>">
                                    <i class="fas fa-user-plus"></i> Register Now
                                </button>
                                <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#detailsModal<?php echo $event['id']; ?>">
                                    <i class="fas fa-info-circle"></i> View Details
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="detailsModal<?php echo $event['id']; ?>" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title"><?php echo htmlspecialchars($event['title']); ?></h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <?php if($event['image']): ?>
                                    <img src="<?php echo $event['image']; ?>" class="img-fluid mb-3 rounded" alt="Event">
                                <?php endif; ?>
                                <h6><i class="fas fa-align-left"></i> Description</h6>
                                <p><?php echo nl2br(htmlspecialchars($event['description'])); ?></p>
                                
                                <hr>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong><i class="fas fa-calendar"></i> Date:</strong> <?php echo date('d M Y', strtotime($event['event_date'])); ?></p>
                                        <p><strong><i class="fas fa-clock"></i> Time:</strong> <?php echo date('h:i A', strtotime($event['event_time'])); ?></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong><i class="fas fa-map-marker-alt"></i> Venue:</strong> <?php echo htmlspecialchars($event['venue']); ?></p>
                                        <p><strong><i class="fas fa-users"></i> Max Participants:</strong> <?php echo $event['max_participants']; ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#registerModal<?php echo $event['id']; ?>">
                                    Register Now
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="registerModal<?php echo $event['id']; ?>" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Register for <?php echo htmlspecialchars($event['title']); ?></h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <form action="register_event.php" method="POST">
                                <div class="modal-body">
                                    <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">
                                    
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label class="form-label">First Name *</label>
                                            <input type="text" class="form-control" name="first_name" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Middle Name</label>
                                            <input type="text" class="form-control" name="middle_name">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Last Name *</label>
                                            <input type="text" class="form-control" name="last_name" required>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">PRN *</label>
                                        <input type="text" class="form-control" name="prn" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Email *</label>
                                        <input type="email" class="form-control" name="email" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Contact Number *</label>
                                        <input type="tel" class="form-control" name="contact_no" pattern="[0-9]{10}" required>
                                    </div>
                                    
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Department *</label>
                                            <select class="form-select" name="department" required>
                                                <option value="">Select Department</option>
                                                <option value="CSE">CSE</option>
                                                <option value="CY">CY</option>
                                                <option value="AIML">AIML</option>
                                                <option value="ALDS">ALDS</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Year *</label>
                                            <select class="form-select" name="year" required>
                                                <option value="">Select Year</option>
                                                <option value="FY">First Year</option>
                                                <option value="SY">Second Year</option>
                                                <option value="TY">Third Year</option>
                                                <option value="FINAL YEAR">Final Year</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <?php if($event['registration_fee'] > 0): ?>
                                        <div class="alert alert-info">
                                            <i class="fas fa-info-circle"></i> Registration Fee: ₹<?php echo number_format($event['registration_fee'], 2); ?>
                                            <br><small>You will be redirected to payment page after registration.</small>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary">
                                        <?php echo $event['registration_fee'] > 0 ? 'Proceed to Payment' : 'Register'; ?>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle fa-3x mb-3"></i>
                    <h4>No Events Available</h4>
                    <p>Stay tuned for upcoming events and workshops!</p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php 
$conn->close();
include '../includes/footer.php'; 
?>
