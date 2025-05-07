<?php
session_start();
require_once '../app/db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        $stmt = $conn->prepare("DELETE FROM patients WHERE patients_id = :id");
        $stmt->bindParam(':id', $id);

      if ($stmt->execute()) {  
            $_SESSION['message'] = "Patient deleted successfully.";
        } else {
            $_SESSION['message'] = "Failed to delete patient.";
        }
    } catch (PDOException $e) {
        $_SESSION['message'] = "Error: " . $e->getMessage();
    }
}
$stmt = $conn->prepare("SELECT full_name, assigned_to FROM patients WHERE patients_id = :id");
$stmt->execute(['id' => $patients_id]);
$patient = $stmt->fetch(PDO::FETCH_ASSOC);

$notifTitle = "Patient Record Deleted";
$notifMessage = "The record for patient {$patient['full_name']} assigned to you has been deleted from the system.";
$notifStmt = $conn->prepare("INSERT INTO notifications (title, message, status, recipient_id, created_at) VALUES (:title, :message, 'unread', :recipient_id, NOW())");
$notifStmt->execute([
    'title' => $notifTitle,
    'message' => $notifMessage,
    'recipient_id' => $patient['assigned_to']
]);


header("Location: patients.php");
exit;
?>
