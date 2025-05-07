<?php
session_start();
require_once '../app/db.php';
include '../includes/header.php'; 

// Check user role
$username = $_SESSION['username'] ?? 'Unknown';
$role = $_SESSION['role'] ?? null;

if (!in_array($role, ['admin', 'receptionist'])) {
    header('Location: ../dashboard/index.php');
    exit;
}

// Get patient ID
$id = isset($_GET['id']) ? intval($_GET['id']) : null;
if (!$id) {
    echo "No patient selected.";
    exit;
}

// Fetch patient data
$stmt = $conn->prepare("SELECT * FROM patients WHERE patients_id = ?");
$stmt->execute([$id]);
$patient = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$patient) {
    echo "Patient not found.";
    exit;
}

// Fetch doctors for dropdown
$doctorStmt = $conn->prepare("SELECT doctor_id, full_name, specialization FROM doctors ORDER BY full_name ASC");
$doctorStmt->execute();
$doctors = $doctorStmt->fetchAll(PDO::FETCH_ASSOC);


$notifTitle = "Patient Information Updated";
$notifMessage = "Information for patient {$patientName} has been updated by {$staffRole}.";

// Fetch user_id of the assigned doctor
$stmt = $conn->prepare("SELECT user_id FROM users WHERE user_id = :doctor_id");
$stmt->execute(['doctor_id' => $assigned_to]);
$doctor = $stmt->fetch(PDO::FETCH_ASSOC);

if ($doctor) {
    $notifInsert = $conn->prepare("INSERT INTO notifications (title, message, status, recipient_id, created_at) VALUES (:title, :message, 'unread', :recipient_id, NOW())");
    $notifInsert->execute([
        'title' => $notifTitle,
        'message' => $notifMessage,
        'recipient_id' => $doctor['user_id']
    ]);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Patient</title>
  <link rel="stylesheet" href="../css/style-pages.css">
  <link rel="stylesheet" href="../css/dashboard-style.css">
  <link rel="stylesheet" href="../css/add_new_patient.css">
  
</head>

<body>



<div class="body">

<?php include '../includes/sidebar.php'; ?>

<div class="form-container">
  <h2>Edit Patient</h2>

  <?php if (isset($_SESSION['message'])): ?>
    <div class="message alert-message"><?= $_SESSION['message']; unset($_SESSION['message']); ?></div>
  <?php endif; ?>

  <form action="../app/update_patient.php" method="POST" class="grid-form">
    <input type="hidden" name="id" value="<?= $patient['patients_id'] ?>">

    <div class="form-group">
      <label>Full Name:</label>
      <input type="text" name="full_name" value="<?= htmlspecialchars($patient['full_name']) ?>" required>
    </div>

    <div class="form-group">
      <label>Gender:</label>
      <select name="gender" required>
        <option value="male" <?= $patient['gender'] === 'male' ? 'selected' : '' ?>>Male</option>
        <option value="female" <?= $patient['gender'] === 'female' ? 'selected' : '' ?>>Female</option>
      </select>
    </div>

    <div class="form-group">
      <label>Date of Birth:</label>
      <input type="date" name="dob" value="<?= htmlspecialchars($patient['dob']) ?>" required>
    </div>

    <div class="form-group">
      <label>Phone:</label>
      <input type="text" name="phone" value="<?= htmlspecialchars($patient['phone']) ?>" required>
    </div>

    <div class="form-group">
      <label>Email:</label>
      <input type="email" name="email" value="<?= htmlspecialchars($patient['email']) ?>">
    </div>

    <div class="form-group">
      <label>Address:</label>
      <input type="text" name="address" value="<?= htmlspecialchars($patient['address']) ?>" required>
    </div>

    <div class="form-group">
      <label>Insurance:</label>
      <select name="insures">
        <option value="">--Select--</option>
        <option value="RSSB" <?= $patient['insures'] === 'RSSB' ? 'selected' : '' ?>>RSSB</option>
        <option value="MUTUEL" <?= $patient['insures'] === 'MUTUEL' ? 'selected' : '' ?>>MUTUEL</option>
        <option value="100%" <?= $patient['insures'] === '100%' ? 'selected' : '' ?>>100%</option>
      </select>
    </div>

    <div class="form-group">
      <label>Assigned Doctor:</label>
      <select name="assigned_to" required>
        <option value="">--Select Doctor--</option>
        <?php foreach ($doctors as $doctor): ?>
          <option value="<?= $doctor['doctor_id'] ?>" <?= $patient['assigned_to'] == $doctor['doctor_id'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($doctor['full_name']) ?> - <?= htmlspecialchars($doctor['specialization']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="form-actions">
      <button type="submit">Update Patient</button>
      <a href="../dashboards/patients.php" class="cancel-btn">Cancel</a>
    </div>
  </form>
</div>

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
