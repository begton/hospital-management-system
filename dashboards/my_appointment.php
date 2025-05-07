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
$message = $_SESSION['message'] ?? '';
unset($_SESSION['message']);

$searchInput = $_POST['search'] ?? '';
$isSearching = !empty($searchInput);

if (!empty($_POST['clear'])) {
    header("Location: my_appointments.php");
    exit;
}

$sql = "
    SELECT a.appointment_id, a.appointment_date, a.appointment_time, a.status, 
           p.full_name AS patient_name, p.phone
    FROM appointments a
    JOIN patients p ON a.patients_id = p.patients_id
    WHERE a.doctor_id = :doctor_id
";

if ($isSearching) {
    $sql .= " AND p.full_name LIKE :search ";
}

$sql .= " ORDER BY a.appointment_date DESC, a.appointment_time DESC";

$stmt = $conn->prepare($sql);

$params = ['doctor_id' => $doctor_id];
if ($isSearching) {
    $params['search'] = '%' . trim($searchInput) . '%';
}

$stmt->execute($params);
$appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Appointments</title>
    <link rel="stylesheet" href="../css/dashboard-style.css">
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>

<div class="body">
    <?php include '../includes/sidebar.php'; ?>

    <section class="section-patients">
        <h2>My Appointments</h2>

        <?php if ($message): ?>
            <div class="alert-message"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <form method="POST" class="search-form">
            <input type="text" name="search" placeholder="Search by patient name..." value="<?= htmlspecialchars($searchInput) ?>">
            <button type="submit">Search</button>
            <?php if ($isSearching): ?>
                <button type="submit" name="clear" class="cancel-btn">Clear</button>
            <?php endif; ?>
        </form>

       
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Appointment Date</th>
                    <th>Time</th>
                    <th>Patient</th>
                    <th>Phone</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($appointments): ?>
                    <?php foreach ($appointments as $index => $appt): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= htmlspecialchars($appt['appointment_date']) ?></td>
                            <td><?= htmlspecialchars($appt['appointment_time']) ?></td>
                            <td><?= htmlspecialchars($appt['patient_name']) ?></td>
                            <td><?= htmlspecialchars($appt['phone']) ?></td>
                            <td><?= htmlspecialchars(ucfirst($appt['status'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="6">No appointments found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
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
