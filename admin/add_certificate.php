<?php
session_start();
if(!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

require_once '../config/database.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $prn = $_POST['prn'];
    $certificate_title = $_POST['certificate_title'];
    $event_id = $_POST['event_id'] ?: null;
    $issue_date = $_POST['issue_date'];
    $certificate_file = $_POST['certificate_file'];
    
    $db = new Database();
    $conn = $db->getConnection();
    
    $student_query = $conn->prepare("SELECT id FROM students WHERE prn = ?");
    $student_query->bind_param("s", $prn);
    $student_query->execute();
    $student_result = $student_query->get_result();
    
    if($student_result->num_rows == 0) {
        header("Location: certificates.php?error=student_not_found");
        exit();
    }
    
    $student = $student_result->fetch_assoc();
    $student_id = $student['id'];
    $student_query->close();
    
    $certificate_code = 'SPARK-' . strtoupper(substr(md5(time() . $prn), 0, 10));
    
    $stmt = $conn->prepare("INSERT INTO certificates (student_id, event_id, certificate_code, certificate_title, issue_date, certificate_file) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iissss", $student_id, $event_id, $certificate_code, $certificate_title, $issue_date, $certificate_file);
    
    $stmt->execute();
    $stmt->close();
    $conn->close();
}

header("Location: certificates.php");
exit();
?>
