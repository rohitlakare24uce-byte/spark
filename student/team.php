<?php 
$page_title = "Team";
include '../includes/header.php'; 
require_once '../config/database.php';

$db = new Database();
$conn = $db->getConnection();

$query = "SELECT * FROM team_members ORDER BY display_order ASC, id ASC";
$result = $conn->query($query);
?>

<main class="container my-5">
    <div class="text-center mb-5">
        <h1 class="display-4 fw-bold">Our Team</h1>
        <p class="lead">Meet the passionate individuals driving SPARK forward</p>
    </div>

    <div class="row g-4">
        <?php if($result && $result->num_rows > 0): ?>
            <?php while($member = $result->fetch_assoc()): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card team-card" onclick="showTeamModal(<?php echo $member['id']; ?>)">
                        <?php if($member['photo']): ?>
                            <img src="<?php echo $member['photo']; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($member['name']); ?>">
                        <?php else: ?>
                            <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($member['name']); ?>&size=250&background=667eea&color=fff" class="card-img-top" alt="<?php echo htmlspecialchars($member['name']); ?>">
                        <?php endif; ?>
                        
                        <div class="card-body text-center">
                            <h5 class="card-title fw-bold mb-1"><?php echo htmlspecialchars($member['name']); ?></h5>
                            <p class="text-muted mb-3"><?php echo htmlspecialchars($member['post']); ?></p>
                            
                            <div class="social-links">
                                <?php if($member['linkedin']): ?>
                                    <a href="<?php echo htmlspecialchars($member['linkedin']); ?>" target="_blank" onclick="event.stopPropagation();">
                                        <i class="fab fa-linkedin"></i>
                                    </a>
                                <?php endif; ?>
                                <?php if($member['github']): ?>
                                    <a href="<?php echo htmlspecialchars($member['github']); ?>" target="_blank" onclick="event.stopPropagation();">
                                        <i class="fab fa-github"></i>
                                    </a>
                                <?php endif; ?>
                                <?php if($member['email']): ?>
                                    <a href="mailto:<?php echo htmlspecialchars($member['email']); ?>" onclick="event.stopPropagation();">
                                        <i class="fas fa-envelope"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="teamModal<?php echo $member['id']; ?>" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Team Member Profile</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body text-center">
                                <?php if($member['photo']): ?>
                                    <img src="<?php echo $member['photo']; ?>" class="img-fluid rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;" alt="<?php echo htmlspecialchars($member['name']); ?>">
                                <?php else: ?>
                                    <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($member['name']); ?>&size=150&background=667eea&color=fff" class="img-fluid rounded-circle mb-3" alt="<?php echo htmlspecialchars($member['name']); ?>">
                                <?php endif; ?>
                                
                                <h4 class="fw-bold"><?php echo htmlspecialchars($member['name']); ?></h4>
                                <p class="text-muted mb-3"><?php echo htmlspecialchars($member['post']); ?></p>
                                
                                <?php if($member['bio']): ?>
                                    <p class="text-start"><?php echo nl2br(htmlspecialchars($member['bio'])); ?></p>
                                <?php endif; ?>
                                
                                <hr>
                                
                                <div class="d-flex justify-content-center gap-3">
                                    <?php if($member['linkedin']): ?>
                                        <a href="<?php echo htmlspecialchars($member['linkedin']); ?>" target="_blank" class="btn btn-primary">
                                            <i class="fab fa-linkedin"></i> LinkedIn
                                        </a>
                                    <?php endif; ?>
                                    <?php if($member['github']): ?>
                                        <a href="<?php echo htmlspecialchars($member['github']); ?>" target="_blank" class="btn btn-dark">
                                            <i class="fab fa-github"></i> GitHub
                                        </a>
                                    <?php endif; ?>
                                    <?php if($member['email']): ?>
                                        <a href="mailto:<?php echo htmlspecialchars($member['email']); ?>" class="btn btn-success">
                                            <i class="fas fa-envelope"></i> Email
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-info text-center">
                    <i class="fas fa-users fa-3x mb-3"></i>
                    <h4>Team Information Coming Soon</h4>
                    <p>We're building our amazing team. Check back soon!</p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php 
$conn->close();
include '../includes/footer.php'; 
?>
