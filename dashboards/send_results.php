<?php
session_start();
require_once '../app/db.php';
include '../includes/header.php';

$successMessage = '';
$errorMessage = '';

// Handle submitted results
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_results'])) {
    try {
        $labRequestId = $_POST['request_id'];
        $testResults = $_POST['test_results'];

        foreach ($testResults as $testName => $result) {
            $sanitizedResult = strip_tags(trim($result));

            $sql = "UPDATE lab_requests 
                    SET result = :result, status = 'completed' 
                    WHERE request_id = :request_id AND test_name = :test_name AND status = 'pending'";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':result', $sanitizedResult);
            $stmt->bindParam(':request_id', $labRequestId);
            $stmt->bindParam(':test_name', $testName);
            $stmt->execute();
        }

        $successMessage = 'Test results successfully sent to the doctor.';
    } catch (PDOException $e) {
        $errorMessage = 'Error: ' . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Send Lab Test Results</title>
    <link rel="stylesheet" href="../css/style-pages.css">
    <link rel="stylesheet" href="../css/dashboard-style.css">
    <link rel="stylesheet" href="../css/send_results-style.css">
    
</head>
<body>

<div class="body">
    <?php include '../includes/sidebar.php'; ?>

    <section class="section-send-results">
        <h2>Send Lab Test Results</h2>

        <?php if ($successMessage): ?>
            <div class="alert-message"><?= htmlspecialchars($successMessage) ?></div>
        <?php endif; ?>

        <?php if ($errorMessage): ?>
            <div class="alert-message" style="background-color: #f8d7da; color: #721c24;">
                <?= htmlspecialchars($errorMessage) ?>
            </div>
        <?php endif; ?>

        <?php
        $labRequestId = $_GET['request_id'] ?? null;

        if ($labRequestId) {
            try {
                $sql = "
                    SELECT lr.request_id, lr.test_name, lr.result, lr.status, lr.requested_at,
                           d.full_name AS doctor_name, d.specialization,
                           p.full_name AS patient_name
                    FROM lab_requests lr
                    JOIN doctors d ON lr.doctor_id = d.doctor_id
                    JOIN patients p ON lr.patient_id = p.patients_id
                    WHERE lr.request_id = :request_id
                ";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':request_id', $labRequestId);
                $stmt->execute();
                $labTests = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if (!$labTests) {
                    echo "<p>No lab test requests found for this ID.</p>";
                } else {
                    $doctorName = $labTests[0]['doctor_name'];
                    $specialization = $labTests[0]['specialization'];
                    $patientName = $labTests[0]['patient_name'];
                    $requestedAt = $labTests[0]['requested_at'];

                    echo "<h3>Patient: " . htmlspecialchars($patientName) . "</h3>";
                    echo "<h3>Doctor: " . htmlspecialchars($doctorName) . " (" . htmlspecialchars($specialization) . ")</h3>";
                    echo "<p>Request Date: " . htmlspecialchars($requestedAt) . "</p>";

                    echo "<form method='POST' onsubmit=\"return confirm('Are you sure you want to send the results?');\">";
                    echo "<input type='hidden' name='request_id' value='" . htmlspecialchars($labRequestId) . "'>";

                    $hasPending = false;

                    foreach ($labTests as $test) {
                        $testName = htmlspecialchars($test['test_name']);
                        $status = $test['status'];
                        $result = htmlspecialchars($test['result']);

                        echo "<div class='test-item'>";
                        echo "<label>$testName</label>";

                        if ($status === 'completed') {
                            echo "<textarea rows='4' readonly style='background-color: #f0f0f0;'>$result</textarea>";
                            echo "<p style='color: green;'>Already completed</p>";
                        } else {
                            echo "<textarea name='test_results[" . htmlspecialchars($test['test_name']) . "]' rows='4' placeholder='Enter result for $testName' required></textarea>";
                            $hasPending = true;
                        }

                        echo "</div>";
                    }

                    if ($hasPending) {
                        echo "<button type='submit' name='send_results' class='send-results-btn'>Send Results</button>";
                    } else {
                        echo "<p style='color: #888;'>All tests for this request have already been completed.</p>";
                    }

                    echo "</form>";
                }
            } catch (PDOException $e) {
                echo "<p>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
            }
        } else {
            echo "<p>Invalid or missing lab request ID.</p>";
        }
        ?>
    </section>
</div>

<script>
    setTimeout(() => {
        const alertBox = document.querySelector('.alert-message');
        if (alertBox) {
            alertBox.style.opacity = '0';
            alertBox.style.transform = 'translateY(-10px)';
            setTimeout(() => alertBox.remove(), 500);
        }
    }, 4000);
</script>

</body>
</html>
