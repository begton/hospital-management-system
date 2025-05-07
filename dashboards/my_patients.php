<?php
session_start();
require_once '../app/db.php';

// Get doctor ID from session
$doctor_id = $_SESSION['doctor_id']; // Replace with actual session variable for logged-in doctor

// Ensure doctor_id exists in session
if (!isset($doctor_id)) {
    echo "Doctor ID not found in session.";
    exit();
}

// Get today's date
$today = date('Y-m-d');

// Handle search functionality
$searchInput = isset($_POST['search']) ? $_POST['search'] : '';
$isSearching = !empty($searchInput);
if ($isSearching) {
    $searchInput = htmlspecialchars($searchInput);
}

// SQL query to fetch patients assigned to the doctor for today with search functionality
$sql = "SELECT p.patients_id, p.full_name, p.gender, p.phone, p.email, mr.status
        FROM patients p
        JOIN medical_records mr ON p.patients_id = mr.patients_id
        WHERE mr.doctor_id = :doctor_id 
        AND mr.visit_date = :today
        AND (p.full_name LIKE :searchInput OR p.phone LIKE :searchInput)";  // Add search functionality

$stmt_today = $conn->prepare($sql);
$stmt_today->execute([
    'doctor_id' => $doctor_id,
    'today' => $today,
    'searchInput' => "%$searchInput%"  // Using LIKE operator with wildcards for flexible search
]);

// Store today's patients in the $patients_today variable (for testing)
$patients_today = [];
while ($patient = $stmt_today->fetch(PDO::FETCH_ASSOC)) {
    $patients_today[] = $patient; // Add today's patients to the array
}

if (empty($patients_today)) {
    echo "";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Patients</title>
    <link rel="stylesheet" href="../css/style-pages.css">
    <link rel="stylesheet" href="../css/dashboard-style.css">
    <style>

       body {
    font-family: 'Segoe UI', sans-serif;
    background-color: #f4f8fb;
    margin: 0;
    padding: 0;
}
        .container {
            margin: 40px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }
        th, td {
            padding: 12px 15px;
            border-bottom: 1px solid #eee;
            text-align: left;
        }
        th {
            background-color: #0d6efd;
            color: white;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
        .actions a {
            color: #0d6efd;
            text-decoration: none;
            margin-right: 10px;
        }
        .no-data {
            padding: 20px;
            background-color: #ffe5e5;
            color: #d10000;
            border-radius: 5px;
        }
        .search-form {
            margin-bottom: 20px;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 10px;
        }
        .search-form input[type="text"] {
            padding: 8px 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
            width: 250px;
        }
        .search-form .search-btn {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .search-form .search-btn:hover {
            background-color: #2980b9;
        }
        .search-form .cancel-btn {
            display: inline-block;
            padding: 10px 15px;
            background-color: #ccc;
            color: #000;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s;
            font-weight: 500;
        }
        .search-form .cancel-btn:hover {
            background-color: #bbb;
        }
        .container h2 {
        color: #2a7da2;
        margin-bottom: 20px;
        font-size: 28px;
        text-align: center;
        }
        .My-All-History {
            padding: 10px 20px;
            background-color: #0d6efd;
            color: white;
            border: none;
            text-decoration: none;
            border-radius: 5px;
            margin-bottom: 20px;
            display: inline-block;
        }
        .My-All-History:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<?php include '../includes/header.php'; ?>
<div class="body">
    <?php include '../includes/sidebar.php'; ?>

    <div class="content">
        <div class="container">
            <h2>My Patients for Today</h2>
            
            <form method="POST" class="search-form">
                 <input type="text" name="search" placeholder="Search by name or phone..." value="<?= htmlspecialchars($searchInput) ?>">
                 <button type="submit" class="search-btn">Search</button>

                 <?php if ($isSearching): ?>
                    <a href="../dashboards/my_patients.php">
                    <button type="button" class="cancel-btn">Clear</button>
                     </a>
                 <?php endif; ?>
            </form>
            <a href="history.php" class="My-All-History">My All History</a>

            <?php if (!empty($patients_today)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Full Name</th>
                            <th>Gender</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($patients_today as $patient): ?>
                            <tr>
                                <td><?= htmlspecialchars($patient['full_name']) ?></td>
                                <td><?= ucfirst($patient['gender'])?></td>
                                <td><?= htmlspecialchars($patient['phone']) ?></td>
                                <td><?= htmlspecialchars($patient['email']) ?></td>
                                <td><?= htmlspecialchars($patient['status']) ?></td>
                                <td class="actions">
                                    <a href="diagnose.php?patient_id=<?= $patient['patients_id'] ?>">Diagnose</a>
                                    <a href="request_lab.php?patient_id=<?= $patient['patients_id'] ?>" class="cancel-btn">Request Lab</a>
                                    <a href="update_status.php?id=<?= $patient['patients_id'] ?>">Update Status</a>
                                    
                                    <a href='create_appointment.php?patient_id=<?= $patient['patients_id'] ?>  class='delete-btn'>
                                           Create Appointment
                                        </a>
                                    <a href="per_history.php?patient_id=<?= $patient['patients_id'] ?>">View History</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="no-data">No patients assigned or diagnosed today.</div>
            <?php endif; ?>
        </div>
    </div>
</div>

</body>
</html>
