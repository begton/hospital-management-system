<?php
session_start();
require_once '../app/db.php';

// Only admin or receptionist can access
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['admin', 'receptionist'])) {
    header("Location: ../login.php");
    exit;
}

// Fetch discharged patients with completed lab requests and not yet billed
$patients = $conn->query("
    SELECT DISTINCT p.patients_id, p.full_name 
    FROM patients p
    JOIN medical_records m ON p.patients_id = m.patients_id
    JOIN lab_requests r ON p.patients_id = r.patient_id
    WHERE m.status = 'Discharged' 
      AND r.status = 'completed'
      AND p.patients_id NOT IN (SELECT patient_id FROM billing)
    ORDER BY p.full_name
")->fetchAll(PDO::FETCH_ASSOC);

// Handle billing submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patient_id = $_POST['patient_id'];
    $paid = floatval($_POST['paid_amount']);
    $method = $_POST['payment_method'];

    // Fetch total lab cost
    $labStmt = $conn->prepare("
        SELECT SUM(t.cost) 
        FROM lab_requests r 
        JOIN lab_tests t ON r.test_id = t.test_id 
        WHERE r.patient_id = ? AND r.status = 'completed'
    ");
    $labStmt->execute([$patient_id]);
    $labTotal = floatval($labStmt->fetchColumn());

    // Fetch total doctor consultation fees
    $doctorStmt = $conn->prepare("
        SELECT SUM(f.consultation_fee) 
        FROM appointments a
        JOIN doctor_fees f ON a.doctor_id = f.doctor_id
        WHERE a.patient_id = ?
    ");
    $doctorStmt->execute([$patient_id]);
    $doctorTotal = floatval($doctorStmt->fetchColumn());

    $totalAmount = $labTotal + $doctorTotal;

    // Double-check if already billed
    $check = $conn->prepare("SELECT COUNT(*) FROM billing WHERE patient_id = ?");
    $check->execute([$patient_id]);
    if ($check->fetchColumn() > 0) {
        $_SESSION['message'] = "This patient has already been billed.";
        header("Location: billing.php");
        exit;
    }

    $status = ($paid >= $totalAmount) ? 'paid' : (($paid > 0) ? 'partial' : 'unpaid');

    $stmt = $conn->prepare("
        INSERT INTO billing (patient_id, total_amount, paid_amount, payment_method, status)
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->execute([$patient_id, $totalAmount, $paid, $method, $status]);

    $_SESSION['message'] = "Billing successfully submitted.";
    header("Location: billing.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Billing & Payment</title>
    <link rel="stylesheet" href="../css/dashboard-style.css">
    <link rel="stylesheet" href="../css/add_new_patient.css">
    <link rel="stylesheet" href="../css/styles-patient.css">
</head>
<body>
<?php include '../includes/header.php'; ?>
<div class="body">
    <?php include '../includes/sidebar.php'; ?>

    <div class="form-container">
        <h2>Patient Billing</h2>

        <!-- Refresh Button -->
        <form method="get" action="billing.php" style="margin-bottom: 15px;">
            <button type="submit" class="cancel-btn" style="background-color: #e3f2fd; color: #0d47a1;">
                🔄 Reload Patient List
            </button>
        </form>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert-message"><?= $_SESSION['message']; unset($_SESSION['message']); ?></div>
        <?php endif; ?>

        <?php if (empty($patients)): ?>
            <p style="color: red;">No discharged patients with completed lab tests left to bill.</p>
        <?php else: ?>
            <form method="post" action="billing.php" class="grid-form">
                <div class="form-group">
                    <label for="patient_id">Select Patient</label>
                    <select name="patient_id" required>
                        <option value="">-- Choose Patient --</option>
                        <?php foreach ($patients as $row): ?>
                            <option value="<?= $row['patients_id'] ?>"><?= htmlspecialchars($row['full_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Doctor Fee</label>
                    <div id="doctor_total">Rwf 0</div>
                </div>

                <div class="form-group">
                    <label>Lab Tests Fee</label>
                    <div id="lab_total">Rwf 0</div>
                    <ul id="lab_tests_list" style="margin-top: 10px; padding-left: 20px; color: #555;"></ul>
                </div>

                <div class="form-group">
                    <label>Total Amount (Rwf)</label>
                    <input type="text" id="display_total" disabled>
                </div>

                <div class="form-group">
                    <label for="paid_amount">Amount Paid (Rwf)</label>
                    <input type="number" name="paid_amount" required>
                </div>

                <div class="form-group">
                    <label for="payment_method">Payment Method</label>
                    <select name="payment_method" required>
                        <option value="">-- Select Method --</option>
                        <option value="cash">Cash</option>
                        <option value="mobile_money">Mobile Money</option>
                        <option value="credit_card">Credit Card</option>
                    </select>
                </div>

                <div class="form-actions">
                    <button type="submit">Submit Billing</button>
                    <a href="patients.php" class="cancel-btn">Cancel</a>
                </div>
            </form>
        <?php endif; ?>
    </div>
</div>

<script>
document.querySelector('select[name="patient_id"]').addEventListener('change', function () {
    const patientId = this.value;
    if (!patientId) return;

    fetch(`../app/get_total_amount.php?patient_id=${patientId}`)
    .then(response => response.json())
    .then(data => {
        console.log(data); // ← Debug log

        document.getElementById('lab_total').textContent = `Rwf ${data.lab_total.toLocaleString()}`;
        document.getElementById('doctor_total').textContent = `Rwf ${data.doctor_total.toLocaleString()}`;
        document.getElementById('display_total').value = `Rwf ${data.total.toLocaleString()}`;

        const list = document.getElementById('lab_tests_list');
        list.innerHTML = "";
        if (data.lab_tests && data.lab_tests.length > 0) {
            data.lab_tests.forEach(test => {
                const item = document.createElement('li');
                item.textContent = `${test.test_name} - Rwf ${parseInt(test.cost).toLocaleString()}`;
                list.appendChild(item);
            });
        } else {
            const item = document.createElement('li');
            item.textContent = "No lab tests found";
            list.appendChild(item);
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });

});
</script>
</body>
</html>
