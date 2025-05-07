<?php
session_start();
require_once('../app/db.php');
include '../includes/header.php';

// Allow only admin or lab tech
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['admin', 'labTech'])) {
    header("Location: ../login.php");
    exit;
}

if (!isset($conn)) {
    die("Database connection failed.");
}

// Fetch lab requests with doctor and patient names
$query = "SELECT lr.request_id, p.full_name AS patient_name, p.patients_id, 
                 u.full_name AS doctor_name, lr.test_name, lr.requested_at, lr.status
          FROM lab_requests lr
          JOIN patients p ON lr.patient_id = p.patients_id
          JOIN users u ON lr.doctor_id = u.user_id
          ORDER BY lr.requested_at DESC";

$stmt = $conn->prepare($query);
$stmt->execute();
$requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Lab Test Requests</title>
    <link rel="stylesheet" href="../css/dashboard-style.css">
    <link rel="stylesheet" href="../css/lists_lab.css">
</head>
<body>

<div class="body">
    <?php include '../includes/sidebar.php'; ?>

    <div class="content">
        <h2>All Lab Test Requests</h2>

        <?php if (!$requests): ?>
            <div class="alert-message">No lab test requests found.</div>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Patient ID</th>
                        <th>Patient Name</th>
                        <th>Test Name</th>
                        <th>Doctor</th>
                        <th>Requested Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($requests as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['patients_id']) ?></td>
                        <td><?= htmlspecialchars($row['patient_name']) ?></td>
                        <td><?= htmlspecialchars($row['test_name']) ?></td>
                        <td><?= htmlspecialchars($row['doctor_name']) ?></td>
                        <td><?= htmlspecialchars(date('Y-m-d', strtotime($row['requested_at']))) ?></td>
                        <td><?= htmlspecialchars($row['status']) ?></td>
                        <td>
                        <?php if (isset($row['status'], $row['request_id']) && strtolower(trim($row['status'])) !== 'completed'): ?>
    <a class="btn" href="send_results.php?request_id=<?= htmlspecialchars($row['request_id']) ?>" title="Send lab results to doctor">Send Results</a>
<?php else: ?>

                                <span class="status-completed">Sent</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
