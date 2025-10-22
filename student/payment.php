<?php 
$page_title = "Payment";
include '../includes/header.php'; 
require_once '../config/database.php';

if(!isset($_GET['registration_id'])) {
    header("Location: events.php");
    exit();
}

$registration_id = $_GET['registration_id'];

$db = new Database();
$conn = $db->getConnection();

$stmt = $conn->prepare("SELECT er.*, e.title, e.registration_fee, s.first_name, s.last_name, s.email, s.contact_no, p.amount
                        FROM event_registrations er 
                        JOIN events e ON er.event_id = e.id 
                        JOIN students s ON er.student_id = s.id 
                        JOIN payments p ON p.registration_id = er.id 
                        WHERE er.id = ?");
$stmt->bind_param("i", $registration_id);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows == 0) {
    header("Location: events.php");
    exit();
}

$registration = $result->fetch_assoc();
$stmt->close();
$conn->close();
?>

<main class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white text-center py-3">
                    <h4 class="mb-0"><i class="fas fa-credit-card"></i> Payment</h4>
                </div>
                <div class="card-body p-4">
                    <div class="alert alert-info">
                        <h5><i class="fas fa-info-circle"></i> Payment Information</h5>
                        <p class="mb-0">Complete your payment to confirm your registration</p>
                    </div>
                    
                    <h5 class="fw-bold mb-3">Event Details</h5>
                    <p><strong>Event:</strong> <?php echo htmlspecialchars($registration['title']); ?></p>
                    <p><strong>Student Name:</strong> <?php echo htmlspecialchars($registration['first_name'] . ' ' . $registration['last_name']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($registration['email']); ?></p>
                    <p><strong>Contact:</strong> <?php echo htmlspecialchars($registration['contact_no']); ?></p>
                    
                    <hr>
                    
                    <h5 class="fw-bold mb-3">Payment Amount</h5>
                    <h2 class="text-success mb-4">₹<?php echo number_format($registration['amount'], 2); ?></h2>
                    
                    <button id="rzp-button" class="btn btn-success btn-lg w-100">
                        <i class="fas fa-credit-card"></i> Pay with Razorpay
                    </button>

                    <form id="payment-form" action="process_payment.php" method="POST" style="display:none;">
                        <input type="hidden" name="registration_id" value="<?php echo $registration_id; ?>">
                        <input type="hidden" name="amount" value="<?php echo $registration['amount']; ?>">
                        <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
                        <input type="hidden" name="razorpay_order_id" id="razorpay_order_id">
                        <input type="hidden" name="razorpay_signature" id="razorpay_signature">
                    </form>

                    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
                    <script>
                    document.getElementById('rzp-button').onclick = function(e) {
                        e.preventDefault();

                        var options = {
                            "key": "YOUR_RAZORPAY_KEY_ID",
                            "amount": "<?php echo $registration['amount'] * 100; ?>",
                            "currency": "INR",
                            "name": "SPARK",
                            "description": "<?php echo htmlspecialchars($registration['title']); ?>",
                            "image": "",
                            "handler": function (response) {
                                document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;
                                document.getElementById('razorpay_order_id').value = response.razorpay_order_id || '';
                                document.getElementById('razorpay_signature').value = response.razorpay_signature || '';
                                document.getElementById('payment-form').submit();
                            },
                            "prefill": {
                                "name": "<?php echo htmlspecialchars($registration['first_name'] . ' ' . $registration['last_name']); ?>",
                                "email": "<?php echo htmlspecialchars($registration['email']); ?>",
                                "contact": "<?php echo htmlspecialchars($registration['contact_no']); ?>"
                            },
                            "theme": {
                                "color": "#667eea"
                            }
                        };

                        var rzp = new Razorpay(options);
                        rzp.open();
                    };
                    </script>
                    
                    <a href="events.php" class="btn btn-outline-secondary w-100 mt-2">
                        <i class="fas fa-arrow-left"></i> Back to Events
                    </a>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
