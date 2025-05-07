<?php
session_start();
require_once '../app/db.php';
include '../includes/header.php';

// Only Lab Technicians can access
if (!isset($_SESSION['role']) || $_SESSION['role'] == 'lab_tech') {
    header("Location: ../dashboards/index.php");
    exit;
}

// Fetch pending lab requests
try {
    $stmt = $conn->prepare("
        SELECT lr.request_id, p.full_name AS patient_name, u.full_name AS doctor_name, 
               lr.test_name, lr.requested_at, lr.status
        FROM lab_requests lr
        INNER JOIN patients p ON lr.patient_id = p.patients_id
        INNER JOIN users u ON lr.doctor_id = u.user_id
        WHERE lr.status = 'pending'
        ORDER BY lr.requested_at DESC
    ");
    $stmt->execute();
    $lab_requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching lab requests: " . $e->getMessage());
}

// Handle form submission (mark test as completed)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['request_id'])) {
    $request_id = $_POST['request_id'];
    $result = $_POST['result'] ?? '';

    if (!empty($result)) {
        $updateStmt = $conn->prepare("
            UPDATE lab_requests 
            SET status = 'completed', result = ?, completed_at = NOW()
            WHERE request_id = ?
        ");
        $updateStmt->execute([$result, $request_id]);
        $_SESSION['message'] = "Lab test marked as completed!";
        header("Location: lab_requests.php");
        exit;
    } else {
        $_SESSION['error'] = "Result cannot be empty.";
    }
}
/*
$notifTitle = "New Lab Test Requested";
$notifMessage = "Dr. {$doctorName} has requested lab tests for patient {$patientName}.";

// Get all lab techs
$techs = $conn->query("SELECT user_id FROM users WHERE role = 'labtech'")->fetchAll(PDO::FETCH_ASSOC);

foreach ($techs as $tech) {
    $notifInsert = $conn->prepare("INSERT INTO notifications (title, message, status, recipient_id, created_at) VALUES (:title, :message, 'unread', :recipient_id, NOW())");
    $notifInsert->execute([
        'title' => $notifTitle,
        'message' => $notifMessage,
        'recipient_id' => $tech['user_id']
    ]);
}
*/
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Pending Lab Requests</title>
  <link rel="stylesheet" href="../css/style-pages.css">
  <link rel="stylesheet" href="../css/dashboard-style.css">
  <style>
    .table-container {
        margin: 40px auto;
        max-width: 1200px;
        padding: 20px;
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.1);
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    table, th, td {
        border: 1px solid #ddd;
    }

    th, td {
        padding: 12px 15px;
        text-align: left;
    }

    th {
        background-color: #2a7da2;
        color: white;
    }

    tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    .form-actions button {
        background-color: #2a7da2;
        color: #fff;
        padding: 8px 15px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
    }

    .form-actions button:hover {
        background-color: #1d6a7b;
    }

    textarea {
        width: 100%;
        height: 80px;
        margin-top: 10px;
        padding: 8px;
        border-radius: 6px;
        border: 1px solid #ccc;
    }
  </style>
</head>

<body>
<div class="body">
  <?php include '../includes/sidebar.php'; ?>

  <div class="table-container">
    <h2 style="text-align: center; color: #2a7da2;">Pending Lab Requests</h2>

    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert-message info">
            <?= $_SESSION['message']; unset($_SESSION['message']); ?>
        </div>
    <?php elseif (isset($_SESSION['error'])): ?>
        <div class="alert-message error">
            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <table>
      <thead>
        <tr>
          <th>Patient Name</th>
          <th>Test Name</th>
          <th>Doctor Name</th>
          <th>Requested At</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php if (count($lab_requests) > 0): ?>
            <?php foreach ($lab_requests as $request): ?>
                <tr>
                    <td><?= htmlspecialchars($request['patient_name']); ?></td>
                    <td><?= htmlspecialchars($request['test_name']); ?></td>
                    <td><?= htmlspecialchars($request['doctor_name']); ?></td>
                    <td><?= htmlspecialchars(date('d M Y, H:i', strtotime($request['requested_at']))); ?></td>
                    <td>
                        <form method="POST" style="margin:0;">
                            <input type="hidden" name="request_id" value="<?= $request['request_id']; ?>">
                            <textarea name="result" placeholder="Enter test result..." required></textarea>
                            <div class="form-actions" style="margin-top:10px;">
                                <button type="submit">Submit Result</button>
                            </div>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
              <td colspan="5" style="text-align:center;">No pending lab requests found.</td>
            </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
</body>
</html>
