<?php
session_start(); // ✅ Important to use $_SESSION

include '../includes/header.php'; // ✅ Include only ONCE here

// 🔥 Define $doctorId
$doctorId = $_SESSION['doctor_id']; // ✅ ASSUMING you stored doctor_id when doctor logs in

// Get today's date in 'YYYY-MM-DD' format
$today = date('Y-m-d');

// Prepare the SQL query to fetch lab requests for today's date
$stmt = $conn->prepare("
    SELECT p.patients_id, p.full_name, p.gender, p.dob, p.insures, l.status
    FROM lab_requests l
    JOIN patients p ON l.patient_id = p.patients_id
    WHERE p.assigned_to = ? AND DATE(l.requested_at) = ?
");
$stmt->execute([$doctorId, $today]);
$labRequests = $stmt->fetchAll(PDO::FETCH_ASSOC);



// Helper function to calculate age
function calculateAge($dob) {
    $dob = new DateTime($dob);
    $now = new DateTime();
    return $now->diff($dob)->y;
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Lab Patients</title>
    <link rel="stylesheet" href="../css/style-pages.css">
    <link rel="stylesheet" href="../css/dashboard-style.css">
    <link rel="stylesheet" href="../css/lists_lab.css">
</head>
<body>

<div class="body">
    <?php include '../includes/sidebar.php'; ?> 

    <div class="content">
        <h2>My Patients - Lab Requests (Today)</h2>

        <?php if (count($labRequests) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Patient ID</th>
                        <th>Full Name</th>
                        <th>Gender</th>
                        <th>Age</th>
                        <th>Insurance</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($labRequests as $request): ?>
                        <tr>
                            <td><?= htmlspecialchars($request['patients_id']) ?></td>
                            <td><?= htmlspecialchars($request['full_name']) ?></td>
                            <td><?= htmlspecialchars($request['gender']) ?></td>
                            <td><?= calculateAge($request['dob']) ?> yrs</td>
                            <td><?= htmlspecialchars($request['insures']) ?></td>
                            <td><?= htmlspecialchars($request['status']) ?></td>
                            <td>
                                <a class="btn" href="my_view_results.php?patient_id=<?= $request['patients_id'] ?>">View Results</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No patients sent to the lab today.</p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
