<?php
session_start();
require_once '../app/db.php';

// Insert notification for the assigned doctor
$notificationTitle = "New Patient Assigned";
$notificationMessage = "A new patient, $full_name, has been assigned to you. Please check your dashboard for details.";

$insertNote = $conn->prepare("INSERT INTO notifications (user_id, title, message, status, created_at)
                              VALUES (:user_id, :title, :message, 'unread', NOW())");

$insertNote->execute([
    'user_id' => $assigned_to,
    'title' => $notificationTitle,
    'message' => $notificationMessage
]);

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the posted values
    $full_name = trim($_POST['full_name']);
    $gender = trim($_POST['gender']);
    $dob = trim($_POST['dob']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);
    $address = trim($_POST['address']);
    $insures = trim($_POST['insures']);
    $assigned_to = trim($_POST['assigned_to']);

    // Check if any of the fields are empty
    if (
        empty($full_name) || empty($gender) || empty($dob) || empty($phone) ||
        empty($email) || empty($address) || empty($insures) || empty($assigned_to)
    ) {
        $_SESSION['message'] = "All fields are required.";
        header('Location: insert_patients.php');
        exit();
    }

    try {
        // Insert into the patients table
        $query = "INSERT INTO patients 
                  (full_name, gender, dob, phone, email, address, insures, assigned_to, created_at) 
                  VALUES 
                  (:full_name, :gender, :dob, :phone, :email, :address, :insures, :assigned_to, NOW())";

        $stmt = $conn->prepare($query);
        $stmt->bindParam(':full_name', $full_name);
        $stmt->bindParam(':gender', $gender);
        $stmt->bindParam(':dob', $dob);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':insures', $insures);
        $stmt->bindParam(':assigned_to', $assigned_to);
        $stmt->execute();

        // Get the new patient ID
        $patients_id = $conn->lastInsertId();

        // Insert into the medical_records table
        $insert_record = $conn->prepare("INSERT INTO medical_records 
            (patients_id, doctor_id, visit_date, status) 
            VALUES (:patients_id, :doctor_id, CURDATE(), 'Inpatient')");

        $insert_record->execute([
            'patients_id' => $patients_id,
            'doctor_id' => $assigned_to
        ]);

        $_SESSION['message'] = "Patient added successfully!";
        header('Location: ../dashboards/patients.php');
        exit();
    } catch (PDOException $e) {
        $_SESSION['message'] = "Error: " . $e->getMessage();
        header('Location: ../dashboards/patients.php');
        exit();
    }
}
?>
