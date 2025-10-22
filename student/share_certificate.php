<?php 
$page_title = "Share Certificate";
include '../includes/header.php'; 

if(!isset($_GET['code'])) {
    header("Location: certificate.php");
    exit();
}

$certificate_code = $_GET['code'];
?>

<main class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white text-center py-3">
                    <h4 class="mb-0"><i class="fas fa-share"></i> Share Certificate</h4>
                </div>
                <div class="card-body p-4">
                    <form action="send_certificate.php" method="POST">
                        <input type="hidden" name="certificate_code" value="<?php echo htmlspecialchars($certificate_code); ?>">
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Recipient Email *</label>
                            <input type="email" class="form-control" name="recipient_email" required>
                            <small class="text-muted">Enter the email address to share your certificate</small>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold">Message (Optional)</label>
                            <textarea class="form-control" name="message" rows="3" placeholder="Add a personal message..."></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-paper-plane"></i> Send Certificate
                        </button>
                        
                        <a href="certificate.php" class="btn btn-outline-secondary w-100 mt-2">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
