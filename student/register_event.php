<?php
require_once '../config/database.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $event_id = $_POST['event_id'];
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'];
    $last_name = $_POST['last_name'];
    $prn = $_POST['prn'];
    $email = $_POST['email'];
    $contact_no = $_POST['contact_no'];
    $department = $_POST['department'];
    $year = $_POST['year'];
    
    $db = new Database();
    $conn = $db->getConnection();
    
    $conn->begin_transaction();
    
    try {
        $stmt = $conn->prepare("SELECT id FROM students WHERE prn = ?");
        $stmt->bind_param("s", $prn);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if($result->num_rows > 0) {
            $student = $result->fetch_assoc();
            $student_id = $student['id'];
            
            $update_stmt = $conn->prepare("UPDATE students SET first_name=?, middle_name=?, last_name=?, email=?, contact_no=?, department=?, year=? WHERE id=?");
            $update_stmt->bind_param("sssssssi", $first_name, $middle_name, $last_name, $email, $contact_no, $department, $year, $student_id);
            $update_stmt->execute();
            $update_stmt->close();
        } else {
            $insert_stmt = $conn->prepare("INSERT INTO students (prn, first_name, middle_name, last_name, email, contact_no, department, year) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $insert_stmt->bind_param("ssssssss", $prn, $first_name, $middle_name, $last_name, $email, $contact_no, $department, $year);
            $insert_stmt->execute();
            $student_id = $insert_stmt->insert_id;
            $insert_stmt->close();
        }
        $stmt->close();
        
        $check_reg = $conn->prepare("SELECT id FROM event_registrations WHERE event_id = ? AND student_id = ?");
        $check_reg->bind_param("ii", $event_id, $student_id);
        $check_reg->execute();
        $reg_result = $check_reg->get_result();
        
        if($reg_result->num_rows > 0) {
            $check_reg->close();
            $conn->rollback();
            header("Location: events.php?error=already_registered");
            exit();
        }
        $check_reg->close();
        
        $reg_stmt = $conn->prepare("INSERT INTO event_registrations (event_id, student_id, payment_status) VALUES (?, ?, 'pending')");
        $reg_stmt->bind_param("ii", $event_id, $student_id);
        $reg_stmt->execute();
        $registration_id = $reg_stmt->insert_id;
        $reg_stmt->close();
        
        $event_query = $conn->prepare("SELECT registration_fee FROM events WHERE id = ?");
        $event_query->bind_param("i", $event_id);
        $event_query->execute();
        $event_result = $event_query->get_result();
        $event = $event_result->fetch_assoc();
        $event_query->close();
        
        if($event['registration_fee'] > 0) {
            $payment_stmt = $conn->prepare("INSERT INTO payments (registration_id, amount, status) VALUES (?, ?, 'pending')");
            $payment_stmt->bind_param("id", $registration_id, $event['registration_fee']);
            $payment_stmt->execute();
            $payment_stmt->close();
            
            $conn->commit();
            header("Location: payment.php?registration_id=" . $registration_id);
            exit();
        } else {
            $update_reg = $conn->prepare("UPDATE event_registrations SET payment_status = 'completed' WHERE id = ?");
            $update_reg->bind_param("i", $registration_id);
            $update_reg->execute();
            $update_reg->close();
            
            $conn->commit();
            header("Location: events.php?success=1");
            exit();
        }
        
    } catch(Exception $e) {
        $conn->rollback();
        header("Location: events.php?error=registration_failed");
        exit();
    }
    
    $conn->close();
} else {
    header("Location: events.php");
    exit();
}
?>
