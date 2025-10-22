<?php 
$page_title = "Contact Us";
include '../includes/header.php'; 
?>

<main class="container my-5">
    <div class="text-center mb-5">
        <h1 class="display-4 fw-bold">Contact Us</h1>
        <p class="lead">Have questions? We'd love to hear from you!</p>
    </div>

    <?php if(isset($_GET['success']) && $_GET['success'] == 1): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> Thank you for contacting us! We'll get back to you soon.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body p-4">
                    <h3 class="fw-bold mb-4">Send us a Message</h3>
                    <form action="submit_contact.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Name *</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Email *</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Subject *</label>
                            <input type="text" class="form-control" name="subject" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Message *</label>
                            <textarea class="form-control" name="message" rows="5" required></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-paper-plane"></i> Send Message
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-4">
                    <h3 class="fw-bold mb-4">Get in Touch</h3>
                    
                    <div class="mb-4">
                        <h5><i class="fas fa-map-marker-alt text-primary"></i> Address</h5>
                        <p class="ms-4">Sanjivani University<br>
                        Kopargaon, Maharashtra, India</p>
                    </div>
                    
                    <div class="mb-4">
                        <h5><i class="fas fa-envelope text-primary"></i> Email</h5>
                        <p class="ms-4">spark@sanjivani.edu.in</p>
                    </div>
                    
                    <div class="mb-4">
                        <h5><i class="fas fa-phone text-primary"></i> Phone</h5>
                        <p class="ms-4">+91 XXX XXX XXXX</p>
                    </div>
                    
                    <div>
                        <h5><i class="fas fa-share-alt text-primary"></i> Follow Us</h5>
                        <div class="social-links ms-4">
                            <a href="#" target="_blank"><i class="fab fa-facebook"></i></a>
                            <a href="#" target="_blank"><i class="fab fa-twitter"></i></a>
                            <a href="#" target="_blank"><i class="fab fa-instagram"></i></a>
                            <a href="#" target="_blank"><i class="fab fa-linkedin"></i></a>
                            <a href="#" target="_blank"><i class="fab fa-youtube"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card shadow-sm border-0">
                <div class="card-body p-4 bg-light">
                    <h5 class="fw-bold mb-3">Office Hours</h5>
                    <p class="mb-2"><i class="fas fa-clock text-primary"></i> Monday - Friday: 9:00 AM - 5:00 PM</p>
                    <p class="mb-0"><i class="fas fa-clock text-primary"></i> Saturday: 9:00 AM - 1:00 PM</p>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
