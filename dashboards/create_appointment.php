<?php
session_start();
require_once '../app/db.php';
include '../includes/header.php';

// Check if the user is a receptionist or admin
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['admin', 'receptionist', 'doctor'])) {
    header("Location: ../dashboard/index.php");
    exit;
}

// Get the patient_id from the URL parameter
$patient_id = $_GET['patient_id'] ?? null;
$patientInfo = null;

if ($patient_id) {
    // Fetch patient details from the database
    $stmt = $conn->prepare("SELECT full_name FROM patients WHERE patients_id = ?");
    $stmt->execute([$patient_id]);
    $patientInfo = $stmt->fetch(PDO::FETCH_ASSOC);
} else {
    $_SESSION['message'] = "Patient not found.";
    header("Location: patients.php"); // Redirect back to patients list if no patient_id
    exit;
}

// Fetch all doctors from the database
$doctorsQuery = $conn->prepare("SELECT doctor_id, full_name FROM doctors");
$doctorsQuery->execute();
$doctors = $doctorsQuery->fetchAll(PDO::FETCH_ASSOC);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $doctor_id = $_POST['doctor_id'] ?? null;
    $appointment_date = $_POST['appointment_date'] ?? null;
    $status = $_POST['status'] ?? 'pending';
    $reason = $_POST['reason'] ?? null;
    $consultation_fee = $_POST['consultation_fee'] ?? 0.00;

    if ($doctor_id && $appointment_date) {
        $stmt = $conn->prepare("INSERT INTO appointments (patients_id, doctor_id, appointment_date, status, reason, consultation_fee) 
                               VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$patient_id, $doctor_id, $appointment_date, $status, $reason, $consultation_fee]);

        $_SESSION['message'] = "Appointment successfully created!";
        header("Location:list_of_appointment.php"); // Redirect to the appointments list page
        exit;
    } else {
        $_SESSION['message'] = "Please fill in all required fields.";
    }
}

$notifTitle = "New Appointment Booked";
$notifMessage = "A new appointment has been scheduled for you with patient $patient_name on $appointment_date.";
$notifStmt = $conn->prepare("INSERT INTO notifications (title, message, status, recipient_id, created_at) VALUES (:title, :message, 'unread', :recipient_id, NOW())");
$notifStmt->execute([
    'title' => $notifTitle,
    'message' => $notifMessage,
    'recipient_id' => $doctor_id // or $assigned_to
]);

?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Create Appointment</title>
    <link rel="stylesheet" href="../css/style-pages.css">
    <link rel="stylesheet" href="../css/dashboard-style.css">
<style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            color: #333;
        }
    .content{
    
    width: 100%;
    margin: 80px auto; /* Center horizontally */
    border-radius: 10px;
    display: flex;
    flex-direction: column;
    align-items: center; /* Center inner content if needed */
    justify-content: center;
        }
        .form-container {
            background: #fff;
            padding: 30px;
            margin: 40px auto;
            max-width: 650px;
            border-radius: 10px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.1);
            transition: all 0.3s ease;    
        }

        .form-container h2 {
            color: #2a7da2;
            margin-bottom: 20px;
            font-size: 28px;
            text-align: center;
        }

        .form-group {
            margin-bottom: 10px;
        }

        .form-group label {
            font-weight: bold;
            display: block;
            margin-bottom: 8px;
            color: #555;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 16px;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }

        .form-actions {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }

        .form-actions button,
        .form-actions .cancel-btn {
            padding: 12px 25px;
            border-radius: 6px;
            font-size: 16px;
            width: 48%;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .form-actions button {
            background-color: #2a7da2;
            color: #fff;
            border: none;
        }

        .form-actions button:hover {
            background-color: #1d6a7b;
        }

        .form-actions .cancel-btn {
            background-color: #ccc;
            color: #333;
            text-align: center;
            text-decoration: none;
        }

        .form-actions .cancel-btn:hover {
            background-color: #bbb;
        }

        .alert-message {
            background-color: #dff0d8;
            color: #3c763d;
            padding: 14px;
            border-radius: 6px;
            margin-bottom: 20px;
            border: 1px solid #d6e9c6;
            font-size: 16px;
        }

        .alert-message.error {
            background-color: #f2dede;
            color: #a94442;
            border: 1px solid #ebccd1;
        }

        @media (max-width: 768px) {
            .form-container {
                padding: 20px;
                width: 90%;
            }

            .form-actions {
                flex-direction: column;
                gap: 10px;
            }

            .form-actions button,
            .form-actions .cancel-btn {
                width: 100%;
            }
        }
</style>
</head>
<body>
    <div class="body">
        <?php include '../includes/sidebar.php'; ?>
        <div class="content">
            <div class="container">
                <div class="form-container">
                    <h2>Create Appointment For : <?= htmlspecialchars($patientInfo['full_name']) ?></h2>

                    <?php if (isset($_SESSION['message'])): ?>
                        <div class="alert-message"><?= $_SESSION['message']; unset($_SESSION['message']); ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <input type="hidden" name="patient_id" value="<?= $patient_id ?>">

                        <div class="form-group">
                            <label for="doctor_id">Select Doctor</label>
                            <select name="doctor_id" required>
                                <option value="">-- Select Doctor --</option>
                                <?php foreach ($doctors as $doctor): ?>
                                    <option value="<?= $doctor['doctor_id']; ?>"><?= $doctor['full_name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="appointment_date">Appointment Date</label>
                            <input type="datetime-local" name="appointment_date" required>
                        </div>

                        <div class="form-group">
                            <label for="status">Status</label>
                            <select name="status">
                                <option value="pending">Pending</option>
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="reason">Reason</label>
                            <textarea name="reason"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="consultation_fee">Consultation Fee (RWF)</label>
                            <input type="number" step="0.01" name="consultation_fee" value="0.00">
                        </div>

                        <div class="form-actions">
                            <button type="submit">Create Appointment</button>
                            <a href="patients.php" class="cancel-btn">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
