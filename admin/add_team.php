<?php
session_start();
if(!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

require_once '../config/database.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $post = $_POST['post'];
    $photo = $_POST['photo'];
    $email = $_POST['email'];
    $linkedin = $_POST['linkedin'];
    $github = $_POST['github'];
    $bio = $_POST['bio'];
    $display_order = $_POST['display_order'];
    
    $db = new Database();
    $conn = $db->getConnection();
    
    $stmt = $conn->prepare("INSERT INTO team_members (name, post, photo, email, linkedin, github, bio, display_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssi", $name, $post, $photo, $email, $linkedin, $github, $bio, $display_order);
    
    $stmt->execute();
    $stmt->close();
    $conn->close();
}

header("Location: team.php");
exit();
?>
