<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Patients Management</title>
    <link rel="stylesheet" href="../css/dashboard-style.css">
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>

<?php
session_start();
require_once '../app/db.php';
include '../includes/header.php';

$message = $_SESSION['message'] ?? '';
unset($_SESSION['message']);

$searchInput = $_POST['search'] ?? '';
$isSearching = !empty($searchInput);

if (!empty($_POST['clear'])) {
    header("Location: patients.php");
    exit;
}
?>

<div class="body">
    <?php include '../includes/sidebar.php'; ?>

    <section class="section-patients">
  <h2>Patients Management</h2>      

        <?php if ($message): ?>
            <div class="alert-message"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <form method="POST" class="search-form">
            <input type="text" name="search" placeholder="Search by name or phone..." value="<?= htmlspecialchars($searchInput) ?>">
            <button type="submit"  class="search-btn" >Search</button>
            <?php if ($isSearching): ?>
                <a href="../dashboards/patients.php"class="cancel-btn" ><button type="submit" name="clear" class="cancel-btn">Clear</button></a>
                
            <?php endif; ?>
        </form>

        <a href="add_new_patient.php" class="add-btn">
            <i class="fa fa-plus"></i> Add New Patient
        </a>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Full Name</th>
                    <th>Gender</th>
                    <th>Age</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Address</th>
                    <th>Insures</th>
                    <th>Doctor</th>
                    <th>Registered</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php
                try {
                    $sql = "
                        SELECT p.*, d.full_name AS doctor_name, d.specialization
                        FROM patients p
                        LEFT JOIN doctors d ON p.assigned_to = d.doctor_id
                    ";

                    if ($isSearching) {
                        $sql .= " WHERE p.full_name LIKE :search OR p.phone LIKE :search ";
                    }

                    $sql .= " ORDER BY p.created_at DESC";

                    $stmt = $conn->prepare($sql);

                    if ($isSearching) {
                        $searchTerm = '%' . trim($searchInput) . '%';
                        $stmt->bindParam(':search', $searchTerm);
                    }

                    $stmt->execute();
                    $patients = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    if ($patients) {
                        foreach ($patients as $row) {
                            $age = '';
                            if (!empty($row['dob'])) {
                                $dob = new DateTime($row['dob']);
                                $age = $dob->diff(new DateTime())->y;
                            }

                            echo "<tr>
                                <td>" . htmlspecialchars($row['patients_id']) . "</td>
                                <td>" . htmlspecialchars($row['full_name']) . "</td>
                                <td>" . htmlspecialchars($row['gender']) . "</td>
                                <td>" . htmlspecialchars($age) . "</td>
                                <td>" . htmlspecialchars($row['phone']) . "</td>
                                <td>" . htmlspecialchars($row['email']) . "</td>
                                <td>" . htmlspecialchars($row['address']) . "</td>
                                <td>" . htmlspecialchars($row['insures']) . "</td>
                                <td>" . ($row['doctor_name'] ? htmlspecialchars($row['doctor_name'] . " (" . $row['specialization'] . ")") : "<em>Unassigned</em>") . "</td>
                                <td>" . htmlspecialchars($row['created_at']) . "</td>
                                <td>
                                    <div class='action-buttons'>
                                        <a href='../dashboards/edit_patient.php?id=" . htmlspecialchars($row['patients_id']) . "' class='edit-btn'>
                                            <i class='fa fa-pencil'></i> Edit
                                        </a>
                                        <span class='separator'>|</span>
                                        <a href='delete_patient.php?id=" . htmlspecialchars($row['patients_id']) . "' class='delete-btn' onclick=\"return confirm('Are you sure you want to delete this patient?');\">
                                            <i class='fa fa-trash'></i> Delete
                                        </a>
                                        <span class='separator'>|</span>
                                         <a href='../dashboards/create_appointment.php?patient_id=" . htmlspecialchars($row['patients_id']) . "' class='Appointment-btn'>
                                           <i class='fa fa-calendar'></i> Create Appointment
                                        </a>
                                    </div>
                                </td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='11'>No patients found.</td></tr>";
                    }
                } catch (PDOException $e) {
                    echo "<tr><td colspan='11'>Error: " . htmlspecialchars($e->getMessage()) . "</td></tr>";
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
