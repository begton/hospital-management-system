<?php
session_start();
$username = $_SESSION['username'] ?? 'Unknown';
$role = $_SESSION['role'] ?? null;

if (!in_array($role, ['admin', 'receptionist'])) {
    header('Location: ../login.php');
    exit;
}

require_once '../app/db.php';
include '../includes/header.php';

$searchInput = $_GET['search'] ?? '';
$isSearching = !empty($searchInput);

if (!empty($_GET['clear'])) {
    header("Location: doctors.php");
    exit;
}

// Fetch doctors from DB
try {
    $query = "SELECT * FROM doctors";
    if ($isSearching) {
        $query .= " WHERE full_name LIKE :search OR specialization LIKE :search";
        $stmt = $conn->prepare($query);
        $stmt->execute(['search' => "%$searchInput%"]);
    } else {
        $stmt = $conn->prepare($query);
        $stmt->execute();
    }
    $doctors = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Error fetching doctors: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Doctors Management</title>
    <link rel="stylesheet" href="../css/dashboard-style.css">
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>

<div class="body">
    <?php include '../includes/sidebar.php'; ?>

    <section class="section-patients">
    <h2 style="font-size: 24px;">Doctors Management</h2>

        <?php if (isset($error)): ?>
            <div class="alert-message error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="GET" class="search-form">
            <input type="text" name="search" placeholder="Search by name or specialization..." value="<?= htmlspecialchars($searchInput) ?>">
            <button type="submit" class="search-btn">Search</button>
            <?php if ($isSearching): ?>
                <a href="doctors.php" class="cancel-btn">Clear</a>
            <?php endif; ?>
        </form>

        <a href="add_doctor.php" class="add-btn">
            <i class="fa fa-plus"></i> Add New Doctor
        </a>

        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Full Name</th>
                    <th>Specialization</th>
                    <th>Phone</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($doctors)): ?>
                    <tr><td colspan="5">No doctors found<?= $isSearching ? ' for "' . htmlspecialchars($searchInput) . '"' : '' ?>.</td></tr>
                <?php else: ?>
                    <?php foreach ($doctors as $index => $doctor): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= htmlspecialchars($doctor['full_name']) ?></td>
                            <td><?= htmlspecialchars($doctor['specialization']) ?></td>
                            <td><?= htmlspecialchars($doctor['phone']) ?></td>
                            <td>
                                <div class="action-buttons">
                                    <a href="edit_doctor.php?id=<?= htmlspecialchars($doctor['doctor_id']) ?>" class="edit-btn">
                                        <i class="fa fa-pencil"></i> Edit
                                    </a>
                                    <span class="separator">|</span>
                                    <a href="delete_doctor.php?id=<?= htmlspecialchars($doctor['doctor_id']) ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this doctor?');">
                                        <i class="fa fa-trash"></i> Delete
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
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
