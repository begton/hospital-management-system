<?php
session_start();
require_once '../app/db.php';

// Ensure doctor_id is set
$doctor_id = $_SESSION['doctor_id']; // This should be set during doctor login
if (!isset($doctor_id)) {
    echo "Doctor ID not found in session.";
    exit();
}

// Get search query from form input (if any)
$search_query = isset($_POST['search']) ? $_POST['search'] : '';

// Get all medical records for the doctor, for all patients assigned
$sql = "SELECT p.patients_id, p.full_name, p.gender, p.phone, p.email, mr.diagnosis, mr.treatment, mr.visit_date
        FROM patients p
        JOIN medical_records mr ON p.patients_id = mr.patients_id
        WHERE mr.doctor_id = :doctor_id 
        AND (p.full_name LIKE :search_query OR p.phone LIKE :search_query OR p.email LIKE :search_query)
        ORDER BY mr.visit_date DESC";

$stmt = $conn->prepare($sql);
$stmt->execute([
    'doctor_id' => $doctor_id,
    'search_query' => '%' . $search_query . '%'
]);

$patient_history = [];
while ($record = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $patient_history[] = $record; // Add each patient's history record
}
$searchInput = isset($_POST['search']) ? $_POST['search'] : '';
$isSearching = !empty($searchInput);
if ($isSearching) {
    $searchInput = htmlspecialchars($searchInput);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Patient History</title>
    <link rel="stylesheet" href="../css/style-pages.css">
    <link rel="stylesheet" href="../css/dashboard-style.css">
    <style>

.search-form {
    display: flex;
    align-items: center;
    gap: 10px;
}

.search-form input[type="text"] {
    padding: 10px;
    font-size: 16px;
    border-radius: 5px;
    border: 1px solid #ccc;
    width: 250px;
}

.search-form button {
    padding: 10px 15px;
    background-color: #0d6efd;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.search-form button:hover {
    background-color: #0056b3;
}

.cancel-btn {
    padding: 8px 12px;
    background-color: #f0f0f0;
    color: #333;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.cancel-btn:hover {
    background-color: #e0e0e0;
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
        .no-data {
            padding: 20px;
            background-color: #ffe5e5;
            color: #d10000;
            border-radius: 5px;
        }
        .back-btn {
            padding: 10px 20px;
            background-color: #0d6efd;
            color: white;
            border: none;
            text-decoration: none;
            border-radius: 5px;
            margin-bottom: 20px;
            display: inline-block;
        }
        .back-btn:hover {
            background-color: #0056b3;
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
.search-form .search-btn i {
    margin-right: 5px;
    font-size: 16px;

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
</style>
</head>
<body>

<?php include '../includes/header.php'; ?>
<div class="body">
    <?php include '../includes/sidebar.php'; ?>

    <div class="content">
        <div class="container">   
         <h2>Patient History</h2>  
           <!-- Search Bar -->
            <form method="POST" class="search-form">
                 <input type="text" name="search" placeholder="Search by name or phone..." value="<?= htmlspecialchars($searchInput) ?>">
                 <button type="submit">Search</button>

                 <?php if ($isSearching): ?>
                    <a href="../dashboards/history.php">
                    <button type="button" class="cancel-btn">Clear</button>
                     </a>
                <?php endif; ?>
            </form>
            <!-- Back Button -->
            <a href="my_patients.php" class="back-btn">Back to My Patients</a>

            

    

            <?php if (isset($_SESSION['message'])): ?>
                <div class="alert-message"><?= $_SESSION['message']; unset($_SESSION['message']); ?></div>
            <?php endif; ?>

            <?php if (!empty($patient_history)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Full Name</th>
                            <th>Gender</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Visit Date</th>
                            <th>Diagnosis & Treatment</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($patient_history as $record): ?>
                            <tr>
                                <td><?= htmlspecialchars($record['full_name']) ?></td>
                                <td><?= ucfirst($record['gender']) ?></td>
                                <td><?= htmlspecialchars($record['phone']) ?></td>
                                <td><?= htmlspecialchars($record['email']) ?></td>
                                <td><?= htmlspecialchars($record['visit_date']) ?></td>
                                <td><?= htmlspecialchars($record['diagnosis']) ?><br><strong>Treatment:</strong> <?= htmlspecialchars($record['treatment']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="no-data">No patient history available.</div>
            <?php endif; ?>
        </div>
    </div>
</div>

</body>
</html>
