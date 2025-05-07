<?php
session_start();
require_once '../app/db.php';  // Make sure to include your database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patientId = $_POST['patient_id'];
    $doctorId = $_POST['doctor_id'];

    // Check if both patient and doctor IDs are provided
    if (!empty($patientId) && !empty($doctorId)) {
        try {
            // Check if the patient ID and doctor ID are valid
            $checkPatientStmt = $conn->prepare("SELECT * FROM patients WHERE patients_id = ?");
            $checkPatientStmt->execute([$patientId]);
            $checkDoctorStmt = $conn->prepare("SELECT * FROM doctors WHERE doctor_id = ?");
            $checkDoctorStmt->execute([$doctorId]);

            if ($checkPatientStmt->rowCount() > 0 && $checkDoctorStmt->rowCount() > 0) {
                // Update the patient assignment in the patients table
                $updateStmt = $conn->prepare("UPDATE patients SET assigned_to = ? WHERE patients_id = ?");
                $updateStmt->execute([$doctorId, $patientId]);
                
                $today = date('Y-m-d');
                $checkStmt = $conn->prepare("SELECT * FROM medical_records WHERE patients_id = ? AND doctor_id = ? AND visit_date = ?");
                $checkStmt->execute([$patientId, $doctorId, $today]);

                if ($checkStmt->rowCount() === 0) {
                    // Insert new medical record for today
                    $insertStmt = $conn->prepare("
                        INSERT INTO medical_records (patients_id, doctor_id, visit_date, diagnosis, treatment, notes)
                        VALUES (?, ?, ?, '', '', '')
                    ");
                    $insertStmt->execute([$patientId, $doctorId, $today]);
                }

                // Set a success message for the user
                $_SESSION['message'] = "Doctor assigned successfully.";
            } else {
                // Handle invalid patient or doctor
                $_SESSION['message'] = "Invalid Patient or Doctor ID.";
            }
        } catch (PDOException $e) {
            // Catch any database errors and display an error message
            $_SESSION['message'] = "Error: " . $e->getMessage();
        }
    } else {
        $_SESSION['message'] = "Please provide both Patient ID and Doctor ID.";
    }
}

// Redirect back to the patients page after the operation
header("Location: ../dashboards/patients.php");
exit;
?>
