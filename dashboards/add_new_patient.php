<?php
if (!isset($_SESSION)) session_start();
require_once '../app/db.php';

// Get current user's role
$role = $_SESSION['role'] ?? null;

if (!in_array($role, ['admin', 'receptionist'])) {
    header('Location: ../dashboard/index.php');
    exit;
}

// Fetch doctors for dropdown
$doctorStmt = $conn->prepare("SELECT doctor_id, full_name, specialization FROM doctors ORDER BY full_name ASC");
$doctorStmt->execute();
$doctors = $doctorStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add New Patient</title>
  <link rel="stylesheet" href="../css/style-pages.css">
  <link rel="stylesheet" href="../css/dashboard-style.css">
  <link rel="stylesheet" href="../css/add_new_patient.css">
</head>
<body>

<?php include '../includes/header.php'; ?>

<div class="body">
  <?php include '../includes/sidebar.php'; ?>

  <div class="form-container">
    <h2>Add New Patient</h2>

    <?php if (isset($_SESSION['message'])): ?>
      <div class="alert-message"><?= htmlspecialchars($_SESSION['message']); unset($_SESSION['message']); ?></div>
    <?php endif; ?>

    <form action="../app/insert_patient.php" method="POST" class="grid-form">
      <div class="form-group">
        <label>Full Name:</label>
        <input type="text" name="full_name" value="<?= htmlspecialchars($_POST['full_name'] ?? '') ?>" required>
      </div>

      <div class="form-group">
        <label>Gender:</label>
        <select name="gender" required>
          <option value="">--Select--</option>
          <option value="male" <?= ($_POST['gender'] ?? '') === 'male' ? 'selected' : '' ?>>Male</option>
          <option value="female" <?= ($_POST['gender'] ?? '') === 'female' ? 'selected' : '' ?>>Female</option>
        </select>
      </div>

      <div class="form-group">
        <label>Date of Birth:</label>
        <input type="date" name="dob" max="<?= date('Y-m-d') ?>" value="<?= htmlspecialchars($_POST['dob'] ?? '') ?>" required>
      </div>

      <div class="form-group">
        <label>Phone Number:</label>
        <input type="text" name="phone" value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>" required>
      </div>

      <div class="form-group">
        <label>Email:</label>
        <input type="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
      </div>

      <div class="form-group">
        <label>Address:</label>
        <input type="text" name="address" value="<?= htmlspecialchars($_POST['address'] ?? '') ?>" required>
      </div>

      <div class="form-group">
        <label>Has Insurance?</label>
        <select name="insures">
          <option value="">--Select--</option>
          <option value="RSSB" <?= ($_POST['insures'] ?? '') === 'RSSB' ? 'selected' : '' ?>>RSSB</option>
          <option value="MUTUEL" <?= ($_POST['insures'] ?? '') === 'MUTUEL' ? 'selected' : '' ?>>MUTUEL</option>
          <option value="100%" <?= ($_POST['insures'] ?? '') === '100%' ? 'selected' : '' ?>>100%</option>
        </select>
      </div>

      <div class="form-group">
        <label for="assigned_to">Assign to Doctor:</label>
        <select name="assigned_to" id="assigned_to" required>
          <option value="">--Select Doctor--</option>
          <?php foreach ($doctors as $doctor): ?>
            <option value="<?= $doctor['doctor_id'] ?>" <?= ($_POST['assigned_to'] ?? '') == $doctor['doctor_id'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($doctor['full_name']) ?> - <?= htmlspecialchars($doctor['specialization']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="form-actions">
        <button type="submit">Add Patient</button>
        <a href="patients.php" class="cancel-btn">Cancel</a>
      </div>
    </form>
  </div>
</div>

</body>
</html>
