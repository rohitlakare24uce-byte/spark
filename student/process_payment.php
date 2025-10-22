<?php
require_once '../config/database.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $registration_id = $_POST['registration_id'];
    $amount = $_POST['amount'];
    $razorpay_payment_id = $_POST['razorpay_payment_id'] ?? null;
    $razorpay_order_id = $_POST['razorpay_order_id'] ?? null;
    $razorpay_signature = $_POST['razorpay_signature'] ?? null;

    $db = new Database();
    $conn = $db->getConnection();

    $conn->begin_transaction();

    try {
        if($razorpay_payment_id) {
            $payment_id = $razorpay_payment_id;
            $order_id = $razorpay_order_id;
        } else {
            $payment_id = "pay_test_" . time() . rand(1000, 9999);
            $order_id = "order_test_" . time();
        }

        $stmt = $conn->prepare("UPDATE payments SET razorpay_payment_id = ?, razorpay_order_id = ?, status = 'success', payment_date = NOW() WHERE registration_id = ?");
        $stmt->bind_param("ssi", $payment_id, $order_id, $registration_id);
        $stmt->execute();
        $stmt->close();

        $reg_stmt = $conn->prepare("UPDATE event_registrations SET payment_status = 'completed', payment_id = ? WHERE id = ?");
        $reg_stmt->bind_param("si", $payment_id, $registration_id);
        $reg_stmt->execute();
        $reg_stmt->close();

        $conn->commit();
        header("Location: events.php?success=1");
        exit();

    } catch(Exception $e) {
        $conn->rollback();
        header("Location: events.php?error=payment_failed");
        exit();
    }

    $conn->close();
} else {
    header("Location: events.php");
    exit();
}
?>
