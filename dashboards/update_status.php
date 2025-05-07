<?php
session_start();
require_once '../app/db.php';

// Get doctor ID from session
$doctorId = $_SESSION['doctor_id'] ?? null;
$patientId = $_GET['id'] ?? null; // Get patient ID from URL parameter

if (!$doctorId || !$patientId) {
    // Redirect if doctor ID or patient ID is not available
    header('Location: my_patients.php');
    exit;
}

// Fetch the patient details
$sql = "SELECT p.patients_id, p.full_name, p.gender, p.phone, p.email, mr.status
        FROM patients p
        JOIN medical_records mr ON p.patients_id = mr.patients_id
        WHERE mr.doctor_id = ? AND p.patients_id = ?";

$stmt = $conn->prepare($sql);
$stmt->execute([$doctorId, $patientId]);
$patient = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$patient) {
    // Redirect if the patient is not found or not assigned to the doctor
    header('Location: my_patients.php');
    exit;
}

// Check if form was submitted to update status
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newStatus = $_POST['status'];

    // Update the status in the database
    $updateStmt = $conn->prepare("UPDATE medical_records SET status = ? WHERE patients_id = ? AND doctor_id = ?");
    $result = $updateStmt->execute([$newStatus, $patientId, $doctorId]);

    // Check if the update was successful
    if ($result) {
        $_SESSION['message'] = "Patient status updated successfully.";
    } else {
        $_SESSION['message'] = "Failed to update patient status. Please try again.";
    }

    // Redirect to the doctor’s patient list page
    header('Location: my_patients.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Patient Status</title>
    <link rel="stylesheet" href="../css/style-pages.css">
    <link rel="stylesheet" href="../css/dashboard-style.css">
    <style>
        body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f5f5f5;
        color: #333;
    }

        .container {
            margin: 40px;
        }
        .form-container {
        background: #fff;
        padding: 30px;
        margin: 40px auto;
        max-width: 650px;
        border-radius: 10px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
        }
        .form-container h2 {
            color: #2a7da2;
             margin-bottom: 20px;
          font-size: 28px;
          text-align: center;
    }
        select, button {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 8px;
            margin-top:5px;
            

        }
        select {
        width: 100%;
        height: 50px;
        border: 1px solid #ccc;
        border-radius: 8px;
        padding: 12px;
        font-size: 15px;
        color: #333;
        background-color: #f8f8f8;
        }


        .cancel-btn,button {
        background-color:#2a7da2;
        color: white;
        padding: 12px 25px;
        border-radius: 6px;
        font-size: 16px;
        text-align: center;
        cursor: pointer;
        text-decoration: none;
        transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #1d6a7b;
        }
        .cancel-btn {
            display: inline-block;
            padding: 12px 25px;
            background-color: #f5f5f5;
            color: #333;
            margin-top: 10px;
            width: 100%;
            transition: background-color 0.3s ease;
        }
        .cancel-btn:hover {
            background-color: #ddd;
        }
    </style>
</head>
<body>

<?php include '../includes/header.php'; ?>
<div class="body">
    <?php include '../includes/sidebar.php'; ?>

    <div class="content">
        <div class="container">
           

            <?php if (isset($_SESSION['message'])): ?>
                <div class="alert-message"><?= $_SESSION['message']; unset($_SESSION['message']); ?></div>
            <?php endif; ?>

            <div class="form-container"> 
                <h2>Update Status for <?= htmlspecialchars($patient['full_name']) ?></h2>
                <form action="update_status.php?id=<?= $patient['patients_id'] ?>" method="POST" onsubmit="return confirmStatusChange();">
                    <div class="form-group">
                        <label for="status">Select Status:</label>
                        <select name="status" required>
                            <option value="Inpatient" <?= $patient['status'] == 'Inpatient' ? 'selected' : '' ?>>Inpatient</option>
                            <option value="Discharged" <?= $patient['status'] == 'Discharged' ? 'selected' : '' ?>>Discharged</option>
                            <option value="Waiting for Lab Test" <?= $patient['status'] == 'Waiting for Lab Test' ? 'selected' : '' ?>>Waiting for Lab Test</option>
                        </select>
                    </div>

                    <button type="submit">Update Status</button>
                    <a href="my_patients.php" class="cancel-btn">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Confirmation popup before submitting the form
    function confirmStatusChange() {
        return confirm('Are you sure you want to update the status? This action cannot be undone.');
    }
</script>

</body>
</html>
