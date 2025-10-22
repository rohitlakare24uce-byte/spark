<?php 
$page_title = "Payments Management";
include 'includes/admin_header.php'; 
require_once '../config/database.php';

$db = new Database();
$conn = $db->getConnection();

$payments = $conn->query("SELECT p.*, er.id as reg_id, e.title as event_title, s.first_name, s.last_name, s.prn 
                           FROM payments p 
                           JOIN event_registrations er ON p.registration_id = er.id 
                           JOIN events e ON er.event_id = e.id 
                           JOIN students s ON er.student_id = s.id 
                           ORDER BY p.payment_date DESC");

$total_revenue = $conn->query("SELECT SUM(amount) as total FROM payments WHERE status = 'success'")->fetch_assoc()['total'];
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-credit-card"></i> Payments Management</h1>
    <div class="text-muted">
        <strong>Total Revenue:</strong> ₹<?php echo number_format($total_revenue ?: 0, 2); ?>
    </div>
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
                        <th>Event</th>
                        <th>Amount</th>
                        <th>Payment ID</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($payments && $payments->num_rows > 0): ?>
                        <?php while($payment = $payments->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $payment['id']; ?></td>
                                <td><?php echo htmlspecialchars($payment['first_name'] . ' ' . $payment['last_name']); ?></td>
                                <td><?php echo htmlspecialchars($payment['prn']); ?></td>
                                <td><?php echo htmlspecialchars($payment['event_title']); ?></td>
                                <td class="fw-bold">₹<?php echo number_format($payment['amount'], 2); ?></td>
                                <td><code><?php echo $payment['razorpay_payment_id'] ?: 'N/A'; ?></code></td>
                                <td>
                                    <span class="badge bg-<?php echo $payment['status'] == 'success' ? 'success' : ($payment['status'] == 'pending' ? 'warning' : 'danger'); ?>">
                                        <?php echo ucfirst($payment['status']); ?>
                                    </span>
                                </td>
                                <td><?php echo date('d M Y H:i', strtotime($payment['payment_date'])); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center">No payments found</td>
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
