<?php
session_start();
require_once '../app/db.php';
include '../includes/header.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'doctor') {
    header("Location: ../login.php");
    exit();
}

$doctorId = $_SESSION['user_id'];
$patientId = isset($_GET['patient_id']) ? intval($_GET['patient_id']) : 0;

if ($patientId <= 0) {
    echo "<p style='color:red; margin: 20px;'>Invalid or missing patient selected.</p>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Lab Test Results</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/dashboard-style.css">
    <link rel="stylesheet" href="../css/send_results-style.css">
</head>
<body>

<div class="body">
    <?php include '../includes/sidebar.php'; ?>

    <section class="section-send-results">
        <h2>Completed Lab Test Results</h2>

        <?php
        try {
            $sql = "
                SELECT lr.request_id, lr.test_name, lr.result, lr.status, lr.requested_at,
                       p.full_name AS patient_name
                FROM lab_requests lr
                JOIN patients p ON lr.patient_id = p.patients_id
                WHERE lr.doctor_id = :doctor_id
                  AND lr.patient_id = :patient_id
                  AND lr.status = 'completed'
                ORDER BY lr.requested_at DESC
            ";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':doctor_id', $doctorId, PDO::PARAM_INT);
            $stmt->bindParam(':patient_id', $patientId, PDO::PARAM_INT);
            $stmt->execute();
            $completedTests = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (!$completedTests) {
                echo "<p>No completed lab test results found for this patient.</p>";
            } else {
                foreach ($completedTests as $test) {
                    echo "<div class='test-item'>";
                    echo "<h3>Patient: <span style='color: #007bff;'>" . htmlspecialchars($test['patient_name']) . "</span></h3>";
                    echo "<p><strong>Test Name:</strong> " . htmlspecialchars($test['test_name']) . "</p>";
                    echo "<p><strong>Date Requested:</strong> " . htmlspecialchars($test['requested_at']) . "</p>";
                    echo "<label for='result_" . $test['request_id'] . "'><strong>Result:</strong></label>";
                    echo "<textarea id='result_" . $test['request_id'] . "' rows='4' readonly>" . htmlspecialchars($test['result']) . "</textarea>";
                    echo "</div>";
                }
            }
        } catch (PDOException $e) {
            echo "<div class='alert-message' style='background-color: #f8d7da; color: #721c24; padding: 10px;'>
                    Error fetching results: " . htmlspecialchars($e->getMessage()) . "
                  </div>";
        }
        ?>
    </section>
</div>

</body>
</html>
