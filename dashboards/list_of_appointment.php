<?php
session_start();
require_once '../app/db.php';
include '../includes/header.php';

$message = $_SESSION['message'] ?? '';
unset($_SESSION['message']);

$searchInput = $_POST['search'] ?? '';
$isSearching = !empty($searchInput);

if (!empty($_POST['clear'])) {
    header("Location: appointments_list.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Appointments List</title>
    <link rel="stylesheet" href="../css/dashboard-style.css">
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>

<div class="body">
    <?php include '../includes/sidebar.php'; ?>

    <section class="section-patients">
        <h2>Appointments List</h2>

        <?php if ($message): ?>
            <div class="alert-message"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <form method="POST" class="search-form">
            <input type="text" name="search" placeholder="Search by patient name or doctor..." value="<?= htmlspecialchars($searchInput) ?>">
            <button type="submit">Search</button>
            <?php if ($isSearching): ?>
                <a href="list_of_appointment.php" class="cancel-btn"><button type="submit" name="clear" class="cancel-btn">Clear</button></a>
            <?php endif; ?>
        </form>

     

        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Patient</th>
                    <th>Doctor</th>
                    <th>Date & Time</th>
                    <th>Status</th>
                    <th>Reason</th>
                    <th>Fee</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                try {
                    $sql = "
                        SELECT a.*, p.full_name AS patient_name, d.full_name AS doctor_name
                        FROM appointments a
                        LEFT JOIN patients p ON a.patients_id = p.patients_id
                        LEFT JOIN doctors d ON a.doctor_id = d.doctor_id
                    ";

                    if ($isSearching) {
                        $sql .= " WHERE p.full_name LIKE :search OR d.full_name LIKE :search ";
                    }

                    $sql .= " ORDER BY a.appointment_date DESC";

                    $stmt = $conn->prepare($sql);

                    if ($isSearching) {
                        $searchTerm = '%' . trim($searchInput) . '%';
                        $stmt->bindParam(':search', $searchTerm);
                    }

                    $stmt->execute();
                    $appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    if ($appointments) {
                        foreach ($appointments as $index => $appt) {
                            echo "<tr>
                                <td>" . ($index + 1) . "</td>
                                <td>" . htmlspecialchars($appt['patient_name']) . "</td>
                                <td>" . htmlspecialchars($appt['doctor_name']) . "</td>
                                <td>" . date('Y-m-d H:i', strtotime($appt['appointment_date'])) . "</td>
                                <td>" . ucfirst($appt['status']) . "</td>
                                <td>" . htmlspecialchars($appt['reason']) . "</td>
                                <td>" . number_format($appt['consultation_fee'], 2) . "</td>
                                <td>" . date('Y-m-d H:i', strtotime($appt['created_at'])) . "</td>
                                <td>
                                    <div class='action-buttons'>
                                        <a href='edit_appointment.php?id=" . htmlspecialchars($appt['appointment_id']) . "' class='edit-btn'>
                                            <i class='fa fa-pencil'></i> Edit
                                        </a>
                                        <span class='separator'>|</span>
                                        <a href='cancel_appointment.php?id=" . htmlspecialchars($appt['appointment_id']) . "' class='delete-btn' onclick=\"return confirm('Are you sure you want to cancel this appointment?');\">
                                            <i class='fa fa-trash'></i> Cancel
                                        </a>
                                    </div>
                                </td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='9'>No appointments found.</td></tr>";
                    }
                } catch (PDOException $e) {
                    echo "<tr><td colspan='9'>Error: " . htmlspecialchars($e->getMessage()) . "</td></tr>";
                }
                ?>
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
