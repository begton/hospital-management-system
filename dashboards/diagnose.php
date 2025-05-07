<?php
session_start();
require_once '../app/db.php';
include '../includes/header.php';

// Ensure only doctors can access
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'doctor') {
    header("Location: ../dashboard/index.php");
    exit;
}

$doctor_id = $_SESSION['doctor_id'];
$patient_id = $_GET['patient_id'] ?? null;

if (!$patient_id) {
    header("Location: my_patients.php");
    exit;
}

// Fetch patient info
$patient = null;
$stmt = $conn->prepare("SELECT full_name, gender, dob FROM patients WHERE patients_id = ?");
$stmt->execute([$patient_id]);
$patient = $stmt->fetch(PDO::FETCH_ASSOC);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $diagnosis = trim($_POST['diagnosis'] ?? '');
    $treatment = trim($_POST['treatment'] ?? '');
    $notes = trim($_POST['notes'] ?? '');

    if (!empty($diagnosis) && !empty($treatment)) {
        try {
            $stmt = $conn->prepare("INSERT INTO medical_records 
                (patients_id, doctor_id, diagnosis, treatment, visit_date, notes, status) 
                VALUES (?, ?, ?, ?, NOW(), ?, 'Diagnosed')");
            $stmt->execute([$patient_id, $doctor_id, $diagnosis, $treatment, $notes]);

            // Optional: Create notification for admins/receptionists
            /*
            $patientName = $patient['full_name'];
            $doctorName = $_SESSION['name'] ?? 'Doctor'; // Set in login session
            $notifTitle = "Diagnosis Completed";
            $notifMessage = "A new diagnosis has been submitted for patient {$patientName} by Dr. {$doctorName}.";

            $adminStmt = $conn->prepare("SELECT user_id FROM users WHERE role IN ('admin', 'receptionist')");
            $adminStmt->execute();
            $admins = $adminStmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($admins as $admin) {
                $notifInsert = $conn->prepare("INSERT INTO notifications 
                    (title, message, status, recipient_id, created_at) 
                    VALUES (?, ?, 'unread', ?, NOW())");
                $notifInsert->execute([$notifTitle, $notifMessage, $admin['user_id']]);
            }
            */

            $_SESSION['message'] = "Diagnosis saved successfully.";
            header("Location: my_patients.php");
            exit;
        } catch (PDOException $e) {
            $_SESSION['error'] = "Database error: " . $e->getMessage();
        }
    } else {
        $_SESSION['error'] = "Please fill in both diagnosis and treatment.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Diagnose Patient</title>
    <link rel="stylesheet" href="../css/style-pages.css">
    <link rel="stylesheet" href="../css/dashboard-style.css">
    <style>
        .form-container {
            background: #fff;
            padding: 30px;
            margin: 40px auto;
            max-width: 700px;
            border-radius: 10px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.1);
        }
        .form-container h2 {
            text-align: center;
            color: #2a7da2;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        .form-group textarea {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }
        .form-actions {
            display: flex;
            justify-content: space-between;
        }
        .form-actions button,
        .form-actions .cancel-btn {
            padding: 12px 25px;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            width: 48%;
        }
        .form-actions button {
            background-color: #2a7da2;
            color: #fff;
            border: none;
        }
        .form-actions .cancel-btn {
            background-color: #ccc;
            color: #333;
            text-align: center;
            text-decoration: none;
        }
        .alert-message {
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 6px;
        }
        .alert-message.success { background-color: #dff0d8; color: #3c763d; }
        .alert-message.error { background-color: #f2dede; color: #a94442; }
        .patient-info {
            background-color: #f8f9fa;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
        }
    </style>
</head>
<body>
<div class="body">
<?php include '../includes/sidebar.php'; ?>
    <div class="form-container">
        <h2>Patient Diagnosis</h2>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert-message success">
                <?= htmlspecialchars($_SESSION['message']); unset($_SESSION['message']); ?>
            </div>
        <?php elseif (isset($_SESSION['error'])): ?>
            <div class="alert-message error">
                <?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <?php if ($patient): ?>
        <div class="patient-info">
            <strong>Name:</strong> <?= htmlspecialchars($patient['full_name']) ?><br>
            <strong>Gender:</strong> <?= htmlspecialchars($patient['gender']) ?><br>
            <strong>DOB:</strong> <?= htmlspecialchars($patient['dob']) ?><br>
        </div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>Diagnosis:</label>
                <textarea name="diagnosis" rows="4" required></textarea>
            </div>
            <div class="form-group">
                <label>Treatment:</label>
                <textarea name="treatment" rows="4" required></textarea>
            </div>
            <div class="form-group">
                <label>Additional Notes:</label>
                <textarea name="notes" rows="3"></textarea>
            </div>
            <div class="form-actions">
                <button type="submit">Save Diagnosis</button>
                <a href="request_lab.php?patient_id=<?= urlencode($patient_id) ?>" class="cancel-btn">Request Lab</a>
            </div>
        </form>
    </div>
</div>
</body>
</html>
