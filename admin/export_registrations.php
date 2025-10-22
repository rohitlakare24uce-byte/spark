<?php
session_start();
if(!isset($_SESSION['admin_logged_in'])) {
    exit('Unauthorized');
}

require_once '../config/database.php';

$format = $_GET['format'] ?? 'excel';
$event_filter = $_GET['event_id'] ?? '';

$db = new Database();
$conn = $db->getConnection();

if($event_filter) {
    $stmt = $conn->prepare("SELECT er.id, s.first_name, s.middle_name, s.last_name, s.prn, s.email, s.contact_no, s.department, s.year,
                            e.title as event_title, er.payment_status, er.registration_date
                            FROM event_registrations er
                            JOIN events e ON er.event_id = e.id
                            JOIN students s ON er.student_id = s.id
                            WHERE er.event_id = ?
                            ORDER BY er.registration_date DESC");
    $stmt->bind_param("i", $event_filter);
    $stmt->execute();
    $registrations = $stmt->get_result();

    $event_query = $conn->prepare("SELECT title FROM events WHERE id = ?");
    $event_query->bind_param("i", $event_filter);
    $event_query->execute();
    $event_result = $event_query->get_result();
    $event_name = $event_result->fetch_assoc()['title'] ?? 'Event';
    $filename_prefix = preg_replace('/[^a-zA-Z0-9_]/', '_', $event_name);
} else {
    $registrations = $conn->query("SELECT er.id, s.first_name, s.middle_name, s.last_name, s.prn, s.email, s.contact_no, s.department, s.year,
                                    e.title as event_title, er.payment_status, er.registration_date
                                    FROM event_registrations er
                                    JOIN events e ON er.event_id = e.id
                                    JOIN students s ON er.student_id = s.id
                                    ORDER BY er.registration_date DESC");
    $filename_prefix = 'all_registrations';
}

if($format == 'excel') {
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename="' . $filename_prefix . '_' . date('Y-m-d') . '.xls"');
    
    echo '<table border="1">';
    echo '<tr>
            <th>ID</th>
            <th>Student Name</th>
            <th>PRN</th>
            <th>Email</th>
            <th>Contact</th>
            <th>Department</th>
            <th>Year</th>
            <th>Event</th>
            <th>Payment Status</th>
            <th>Registration Date</th>
          </tr>';
    
    while($reg = $registrations->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . $reg['id'] . '</td>';
        echo '<td>' . htmlspecialchars($reg['first_name'] . ' ' . ($reg['middle_name'] ? $reg['middle_name'] . ' ' : '') . $reg['last_name']) . '</td>';
        echo '<td>' . htmlspecialchars($reg['prn']) . '</td>';
        echo '<td>' . htmlspecialchars($reg['email']) . '</td>';
        echo '<td>' . htmlspecialchars($reg['contact_no']) . '</td>';
        echo '<td>' . $reg['department'] . '</td>';
        echo '<td>' . $reg['year'] . '</td>';
        echo '<td>' . htmlspecialchars($reg['event_title']) . '</td>';
        echo '<td>' . ucfirst($reg['payment_status']) . '</td>';
        echo '<td>' . date('d M Y H:i', strtotime($reg['registration_date'])) . '</td>';
        echo '</tr>';
    }
    
    echo '</table>';
} else {
    echo '<h1>PDF Export</h1>';
    echo '<p>PDF export requires FPDF or TCPDF library. Install via Composer: composer require setasign/fpdf</p>';
    echo '<p>For now, download as Excel format.</p>';
}

$conn->close();
?>
