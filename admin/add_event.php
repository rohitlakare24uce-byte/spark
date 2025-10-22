<?php
session_start();
if(!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

require_once '../config/database.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $event_date = $_POST['event_date'];
    $event_time = $_POST['event_time'];
    $venue = $_POST['venue'];
    $registration_fee = $_POST['registration_fee'];
    $max_participants = $_POST['max_participants'];
    $image = $_POST['image'];
    $status = $_POST['status'];
    
    $db = new Database();
    $conn = $db->getConnection();
    
    $stmt = $conn->prepare("INSERT INTO events (title, description, event_date, event_time, venue, registration_fee, max_participants, image, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssdss", $title, $description, $event_date, $event_time, $venue, $registration_fee, $max_participants, $image, $status);
    
    if($stmt->execute()) {
        header("Location: events.php?success=added");
    } else {
        header("Location: events.php?error=failed");
    }
    
    $stmt->close();
    $conn->close();
}

header("Location: events.php");
exit();
?>
