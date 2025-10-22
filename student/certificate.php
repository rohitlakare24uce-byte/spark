<?php 
$page_title = "Certificate Verification";
include '../includes/header.php'; 
require_once '../config/database.php';

$certificate = null;
$error = null;

if(isset($_POST['certificate_code'])) {
    $code = $_POST['certificate_code'];
    
    $db = new Database();
    $conn = $db->getConnection();
    
    $stmt = $conn->prepare("SELECT c.*, s.first_name, s.middle_name, s.last_name, s.prn, e.title as event_title 
                            FROM certificates c 
                            JOIN students s ON c.student_id = s.id 
                            LEFT JOIN events e ON c.event_id = e.id 
                            WHERE c.certificate_code = ?");
    $stmt->bind_param("s", $code);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if($result->num_rows > 0) {
        $certificate = $result->fetch_assoc();
    } else {
        $error = "Invalid certificate code. Please check and try again.";
    }
    
    $stmt->close();
    $conn->close();
}
?>

<main class="container my-5">
    <div class="certificate-section py-5">
        <div class="text-center mb-5">
            <h1 class="display-4 fw-bold">Certificate Verification</h1>
            <p class="lead">Enter your certificate code to view and download your certificate</p>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-lg border-0">
                    <div class="card-body p-5">
                        <form method="POST">
                            <div class="mb-4">
                                <label class="form-label fw-bold">Certificate Code</label>
                                <input type="text" class="form-control form-control-lg" name="certificate_code" 
                                       placeholder="Enter your certificate code" required>
                                <small class="text-muted">The certificate code was sent to your email</small>
                            </div>
                            
                            <button type="submit" class="btn btn-primary btn-lg w-100">
                                <i class="fas fa-search"></i> Verify Certificate
                            </button>
                        </form>
                        
                        <?php if($error): ?>
                            <div class="alert alert-danger mt-4 mb-0">
                                <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <?php if($certificate): ?>
            <div class="row justify-content-center mt-5">
                <div class="col-lg-8">
                    <div class="card shadow-lg border-0">
                        <div class="card-header bg-success text-white text-center py-3">
                            <h4 class="mb-0"><i class="fas fa-check-circle"></i> Certificate Verified Successfully</h4>
                        </div>
                        <div class="card-body p-5">
                            <div class="text-center mb-4">
                                <i class="fas fa-certificate fa-5x text-warning mb-3"></i>
                                <h3 class="fw-bold"><?php echo htmlspecialchars($certificate['certificate_title']); ?></h3>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <p><strong>Student Name:</strong><br>
                                    <?php echo htmlspecialchars($certificate['first_name'] . ' ' . 
                                              ($certificate['middle_name'] ? $certificate['middle_name'] . ' ' : '') . 
                                              $certificate['last_name']); ?></p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>PRN:</strong><br>
                                    <?php echo htmlspecialchars($certificate['prn']); ?></p>
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <?php if($certificate['event_title']): ?>
                                <div class="col-md-6">
                                    <p><strong>Event:</strong><br>
                                    <?php echo htmlspecialchars($certificate['event_title']); ?></p>
                                </div>
                                <?php endif; ?>
                                <div class="col-md-6">
                                    <p><strong>Issue Date:</strong><br>
                                    <?php echo date('d F Y', strtotime($certificate['issue_date'])); ?></p>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <p><strong>Certificate Code:</strong><br>
                                <code class="fs-5"><?php echo htmlspecialchars($certificate['certificate_code']); ?></code></p>
                            </div>
                            
                            <div class="d-grid gap-2">
                                <?php if($certificate['certificate_file']): ?>
                                    <a href="<?php echo $certificate['certificate_file']; ?>" class="btn btn-success btn-lg" download>
                                        <i class="fas fa-download"></i> Download Certificate
                                    </a>
                                <?php endif; ?>
                                <a href="share_certificate.php?code=<?php echo urlencode($certificate['certificate_code']); ?>" class="btn btn-primary">
                                    <i class="fas fa-share"></i> Share via Email
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
