<?php
session_start();
require_once 'db.php';






if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input
    $id = isset($_POST['id']) ? intval($_POST['id']) : null;
    $full_name = htmlspecialchars(trim($_POST['full_name']));
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];
    $phone = htmlspecialchars(trim($_POST['phone']));
    $email = htmlspecialchars(trim($_POST['email']));
    $address = htmlspecialchars(trim($_POST['address']));
    $insures = $_POST['insures'];
    $assigned_to = !empty($_POST['assigned_to']) ? $_POST['assigned_to'] : null;

    // Validate required fields
    if (!$id || !$full_name || !$gender || !$dob || !$phone || !$address) {
        $_SESSION['message'] = "Please fill all required fields.";
        header('Location: ../dashboards/patients.php');
        exit;
    }

    try {
        $stmt = $conn->prepare("UPDATE patients SET 
            full_name = :full_name, 
            gender = :gender, 
            dob = :dob, 
            phone = :phone, 
            email = :email, 
            address = :address, 
            insures = :insures, 
            assigned_to = :assigned_to 
            WHERE patients_id = :id");

        $stmt->bindParam(':full_name', $full_name);
        $stmt->bindParam(':gender', $gender);
        $stmt->bindParam(':dob', $dob);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':insures', $insures);
        $stmt->bindParam(':assigned_to', $assigned_to);
        $stmt->bindParam(':id', $id);

        $stmt->execute();

        $_SESSION['message'] = "Patient updated successfully.";
    } catch (PDOException $e) {
        $_SESSION['message'] = "Error updating patient: " . $e->getMessage();
    }

    header('Location: ../dashboards/patients.php');
    exit;
}
?>
