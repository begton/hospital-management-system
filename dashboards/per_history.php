<?php
require_once '../app/db.php';
session_start();

// Validate patient ID
if (!isset($_GET['patient_id'])) {
    echo "Patient ID is missing.";
    exit();
}

$patient_id = $_GET['patient_id'];

// Fetch patient basic info
$patientQuery = $conn->prepare("SELECT full_name, gender, dob, phone, email FROM patients WHERE patients_id = :id");
$patientQuery->execute(['id' => $patient_id]);
$patientInfo = $patientQuery->fetch(PDO::FETCH_ASSOC);

// Fetch medical records with doctor name and specialization
$sql = "SELECT mr.*, d.full_name AS doctor_name, d.specialization 
        FROM medical_records mr 
        JOIN doctors d ON mr.doctor_id = d.doctor_id 
        WHERE mr.patients_id = :id 
        ORDER BY mr.visit_date DESC";

$stmt = $conn->prepare($sql);
$stmt->execute(['id' => $patient_id]);
$records = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch lab test results
// Fetch lab results
$labSql = "SELECT lr.*, d.full_name AS doctor_name, d.specialization 
           FROM lab_requests lr
           JOIN doctors d ON lr.doctor_id = d.doctor_id
           WHERE lr.patient_id = :id
           ORDER BY lr.requested_at DESC";

$labStmt = $conn->prepare($labSql);
$labStmt->execute(['id' => $patient_id]);
$lab_results = $labStmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Patient History</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/reports-history.css">
    <link rel="stylesheet" href="../css/dashboard-style.css">
</head>
<body>
<?php include '../includes/header.php'; ?>
<div class="body">
    <?php include '../includes/sidebar.php'; ?>

    <div class="content">
        <div class="container">
            <a href="my_patients.php" class="back-btn">Back to My Patients</a>

            <h2>Patient History: <?= htmlspecialchars($patientInfo['full_name']) ?></h2>
            <p>
                <strong>Gender:</strong> <?= ucfirst($patientInfo['gender']) ?> |
                <strong>DOB:</strong> <?= htmlspecialchars($patientInfo['dob']) ?> |
                <strong>Phone:</strong> <?= htmlspecialchars($patientInfo['phone']) ?> |
                <strong>Email:</strong> <?= htmlspecialchars($patientInfo['email']) ?>
            </p>

            <!-- Medical Records -->
            <div class="section-title">Medical Records</div>
            <?php if (count($records) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Doctor</th>
                            <th>Specialization</th>
                            <th>Diagnosis</th>
                            <th>Treatment</th>
                            <th>Status</th>
                            <th>Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($records as $rec): ?>
                            <tr>
                                <td><?= $rec['visit_date'] ?></td>
                                <td><?= htmlspecialchars($rec['doctor_name']) ?></td>
                                <td><?= isset($rec['specialization']) ? htmlspecialchars($rec['specialization']) : 'N/A' ?></td>



                                <td><?= htmlspecialchars($rec['diagnosis']) ?></td>
                                <td><?= htmlspecialchars($rec['treatment']) ?></td>
                                <td><?= htmlspecialchars($rec['status']) ?></td>
                                <td><?= htmlspecialchars($rec['notes']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="no-data">No medical records found for this patient.</div>
            <?php endif; ?>

            <!-- Lab Test History -->
            <div class="section-title">Lab Test History</div>
            <?php if ($lab_results): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Date Requested</th>
                            <th>Doctor</th>
                            <th>Test Name</th>
                            <th>Status</th>
                            <th>Result</th>
                            <th>Cost (RWF)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($lab_results as $lab): ?>
                            <tr>
                                <td><?= htmlspecialchars($lab['requested_at']) ?></td>
                                <td><?= htmlspecialchars($lab['doctor_name']) ?></td>
                                <td><?= htmlspecialchars($lab['test_name']) ?></td>
                                <td><?= ucfirst($lab['status']) ?></td>
                                <td><?= nl2br(htmlspecialchars($lab['result'])) ?></td>
                                <td><?= number_format($lab['cost'], 2) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="no-data">No lab test results available for this patient.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>


</html>
