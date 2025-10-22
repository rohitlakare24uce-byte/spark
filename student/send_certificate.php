<?php
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $certificate_code = $_POST['certificate_code'];
    $recipient_email = $_POST['recipient_email'];
    $user_message = $_POST['message'];
    
    $subject = "Certificate from SPARK - Sanjivani University";
    $message = "Hello,\n\n";
    
    if($user_message) {
        $message .= $user_message . "\n\n";
    }
    
    $message .= "Please find your certificate details below:\n";
    $message .= "Certificate Code: " . $certificate_code . "\n\n";
    $message .= "You can verify and download your certificate at:\n";
    $message .= "http://" . $_SERVER['HTTP_HOST'] . "/student/certificate.php\n\n";
    $message .= "Best regards,\n";
    $message .= "SPARK Team\n";
    $message .= "Sanjivani University";
    
    $headers = "From: spark@sanjivani.edu.in\r\n";
    $headers .= "Reply-To: spark@sanjivani.edu.in\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();
    
    if(mail($recipient_email, $subject, $message, $headers)) {
        header("Location: certificate.php?share_success=1");
    } else {
        header("Location: certificate.php?share_error=1");
    }
} else {
    header("Location: certificate.php");
}
exit();
?>
