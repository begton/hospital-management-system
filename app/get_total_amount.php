<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once '../app/db.php';

header('Content-Type: application/json');

if (!isset($_GET['patient_id']) || empty($_GET['patient_id'])) {
    echo json_encode(['error' => 'Missing patient_id']);
    exit;
}

$patient_id = $_GET['patient_id'];

// Fetch total lab cost
$labStmt = $conn->prepare("
    SELECT t.test_name, t.cost
    FROM lab_requests r
    JOIN lab_tests t ON r.test_id = t.test_id
    WHERE r.patient_id = ? AND r.status = 'completed'
");
$labStmt->execute([$patient_id]);
$labTests = $labStmt->fetchAll(PDO::FETCH_ASSOC);

// Calculate lab total
$labTotal = array_sum(array_column($labTests, 'cost'));

// Fetch total doctor consultation fees
$doctorStmt = $conn->prepare("
    SELECT SUM(f.consultation_fee) as total
    FROM appointments a
    JOIN doctor_fees f ON a.doctor_id = f.doctor_id
    WHERE a.patient_id = ?
");
$doctorStmt->execute([$patient_id]);
$doctorTotal = floatval($doctorStmt->fetchColumn());

// Send JSON response
echo json_encode([
    'lab_total' => $labTotal,
    'doctor_total' => $doctorTotal,
    'total' => $labTotal + $doctorTotal,
    'lab_tests' => $labTests
]);
