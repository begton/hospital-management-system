<?php
session_start();
require_once '../app/db.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

$from = $_GET['from'] ?? date('Y-m-01');
$to = $_GET['to'] ?? date('Y-m-d');
$roleFilter = $_GET['role'] ?? '';

$totalAppointments = $conn->query("SELECT COUNT(*) FROM appointments WHERE appointment_date BETWEEN '$from' AND '$to'")->fetchColumn();
$totalBilling = $conn->query("SELECT SUM(total_amount) FROM billing WHERE created_at BETWEEN '$from' AND '$to'")->fetchColumn() ?: 0;
$totalPatients = $conn->query("SELECT COUNT(*) FROM patients WHERE created_at BETWEEN '$from' AND '$to'")->fetchColumn();

$userLogs = [];
if ($roleFilter === 'doctor') {
    $stmt = $conn->prepare("SELECT d.full_name, COUNT(mr.record_id) AS diagnoses 
        FROM doctors d 
        LEFT JOIN medical_records mr ON d.doctor_id = mr.doctor_id 
        WHERE mr.visit_date BETWEEN ? AND ? 
        GROUP BY d.full_name");
    $stmt->execute([$from, $to]);
    $userLogs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} elseif ($roleFilter === 'labtech') {
    $stmt = $conn->prepare("SELECT u.full_name, COUNT(lr.request_id) AS tests_handled 
        FROM users u 
        JOIN lab_requests lr ON u.user_id = lr.doctor_id 
        WHERE lr.requested_at BETWEEN ? AND ? 
        GROUP BY u.full_name");
    $stmt->execute([$from, $to]);
    $userLogs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} elseif ($roleFilter === 'receptionist') {
    $stmt = $conn->prepare("SELECT u.full_name, 
        (SELECT COUNT(*) FROM patients p WHERE p.created_by = u.user_id AND p.created_at BETWEEN ? AND ?) AS patients_registered,
        (SELECT COUNT(*) FROM appointments a WHERE a.created_by = u.user_id AND a.appointment_date BETWEEN ? AND ?) AS appointments_booked,
        (SELECT COUNT(*) FROM billing b WHERE b.created_by = u.user_id AND b.created_at BETWEEN ? AND ?) AS payments_recorded
        FROM users u 
        WHERE u.role = 'receptionist'");
    $stmt->execute([$from, $to, $from, $to, $from, $to]);
    $userLogs = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Reports</title>
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

            <h2>Admin Reports</h2>

            <!-- Filter Form -->
            <div class="filter-section">
                <form method="get" class="filter-form">
                    <label>From:</label>
                    <input type="date" name="from" value="<?= $from ?>">

                    <label>To:</label>
                    <input type="date" name="to" value="<?= $to ?>">

                    <label>Role:</label>
                    <select name="role">
                        <option value="">All</option>
                        <option value="doctor" <?= $roleFilter == 'doctor' ? 'selected' : '' ?>>Doctor</option>
                        <option value="labtech" <?= $roleFilter == 'labtech' ? 'selected' : '' ?>>Lab Tech</option>
                        <option value="receptionist" <?= $roleFilter == 'receptionist' ? 'selected' : '' ?>>Receptionist</option>
                    </select>

                    <button type="submit" class="btn">Filter</button>
                </form>
            </div>

            <!-- Summary Statistics -->
            <div class="section-title">Summary Statistics</div>
            <p><strong>Total Appointments:</strong> <?= $totalAppointments ?></p>
            <p><strong>Total Billing:</strong> RWF <?= number_format($totalBilling, 2) ?></p>
            <p><strong>Total Patients:</strong> <?= $totalPatients ?></p>

            <!-- Role-Specific Logs -->
            <div class="section-title">Role-specific Logs</div>
            <?php if (!empty($userLogs)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Full Name</th>
                            <th>Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($userLogs as $log): ?>
                            <tr>
                                <td><?= htmlspecialchars($log['full_name']) ?></td>
                                <td>
                                    <?php
                                    if (isset($log['diagnoses'])) {
                                        echo $log['diagnoses'] . ' Diagnoses';
                                    } elseif (isset($log['tests_handled'])) {
                                        echo $log['tests_handled'] . ' Tests Handled';
                                    } else {
                                        echo "{$log['patients_registered']} Patients, {$log['appointments_booked']} Appointments, {$log['payments_recorded']} Payments";
                                    }
                                    ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="no-data">No records available for this role and date range.</div>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>
