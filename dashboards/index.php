<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php?error=" . urlencode("Please log in first."));
    exit();
}

$user = $_SESSION['role'];
$username = $_SESSION['username'];
$userId = $_SESSION['user_id']; // Assuming user ID is stored in session

require_once '../app/db.php'; 
include_once '../includes/header.php';


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../css/dashboard-style.css">
</head>
<body>

<input type="checkbox" id="checkbox">

<div class="body">

    <?php include_once '../includes/sidebar.php'; ?>

    <?php if ($user === 'admin'): ?>
<?php
// Total Patients
$patientsQuery = $conn->query("SELECT COUNT(*) AS total_patients FROM patients");
$totalPatients = $patientsQuery->fetch(PDO::FETCH_ASSOC)['total_patients'];

// Total Appointments
$appointmentsQuery = $conn->query("SELECT COUNT(*) AS total_appointments FROM appointments");
$totalAppointments = $appointmentsQuery->fetch(PDO::FETCH_ASSOC)['total_appointments'];


// Total Lab Tests
$labTestsQuery = $conn->query("SELECT COUNT(*) AS total_lab_tests FROM laboratory_tests");
$totalLabTests = $labTestsQuery->fetch(PDO::FETCH_ASSOC)['total_lab_tests'];



// Total Doctors
$doctorsQuery = $conn->query("SELECT COUNT(*) AS total_doctors FROM doctors");
$totalDoctors = $doctorsQuery->fetch(PDO::FETCH_ASSOC)['total_doctors'];

// Total Billing
$billingQuery = $conn->query("SELECT SUM(total_amount) AS total_billing FROM billing");
$totalBilling = $billingQuery->fetch(PDO::FETCH_ASSOC)['total_billing'];


// Total Notifications
$notificationsQuery = $conn->query("SELECT COUNT(*) AS total_notifications FROM `notifications`");
$totalNotifications = $notificationsQuery->fetch(PDO::FETCH_ASSOC)['total_notifications'];

// Total Reports
$reportsQuery = $conn->query("SELECT COUNT(*) AS total_reports FROM reports");
$totalReports = $reportsQuery->fetch(PDO::FETCH_ASSOC)['total_reports'];
?>
        <section class="section-1">
            <h2>Welcome, <?php echo ucfirst(htmlspecialchars($username)); ?>!</h2>
            <p>You're logged in as <strong>Admin</strong>.</p>
        </section>

        <section class="section-2">
            <h2>Dashboard overview</h2>

            <div class="card">
                <h3>Patients</h3>
                <p><strong><?php echo $totalPatients; ?></strong></p>
            </div>
            <div class="card">
                <h3>Appointments</h3>
                <p><strong><?php echo $totalAppointments; ?></strong></p>
            </div>
            <div class="card">
                <h3>Lab Tests</h3>
                <p><strong><?php echo $totalLabTests; ?></strong></p>
            </div>
            <div class="card">
                <h3>Doctors</h3>
                <p><strong><?php echo $totalDoctors; ?></strong></p>
            </div>
            <div class="card">
                <h3>Total Billing</h3>
                <p><strong><?php echo number_format($totalBilling, 2); ?> RWF</strong></p>
            </div>
            <div class="card">
                <h3>Notifications</h3>
                <p><strong><?php echo $totalNotifications; ?></strong></p>
            </div>
            <div class="card">
                <h3>Reports</h3>
                <p><strong><?php echo $totalReports; ?></strong></p>
            </div>
        </section>

    <?php elseif ($user === 'receptionist'): ?>

<?php 
// Total Patients
$patientsQuery = $conn->query("SELECT COUNT(*) AS total_patients FROM patients");
$totalPatients = $patientsQuery->fetch(PDO::FETCH_ASSOC)['total_patients'];

// Total Appointments
$appointmentsQuery = $conn->query("SELECT COUNT(*) AS total_appointments FROM appointments");
$totalAppointments = $appointmentsQuery->fetch(PDO::FETCH_ASSOC)['total_appointments'];


// Total Lab Tests
$labTestsQuery = $conn->query("SELECT COUNT(*) AS total_lab_tests FROM laboratory_tests");
$totalLabTests = $labTestsQuery->fetch(PDO::FETCH_ASSOC)['total_lab_tests'];



// Total Doctors
$doctorsQuery = $conn->query("SELECT COUNT(*) AS total_doctors FROM doctors");
$totalDoctors = $doctorsQuery->fetch(PDO::FETCH_ASSOC)['total_doctors'];

// Total Billing
$billingQuery = $conn->query("SELECT SUM(total_amount) AS total_billing FROM billing");
$totalBilling = $billingQuery->fetch(PDO::FETCH_ASSOC)['total_billing'];


// Total Notifications
$notificationsQuery = $conn->prepare("SELECT COUNT(*) AS total_notifications FROM notifications WHERE user_type = :role");
$notificationsQuery->execute(['role' => 'Receptionist']);
$totalNotifications = $notificationsQuery->fetch(PDO::FETCH_ASSOC)['total_notifications'];

// Total Reports
$reportsQuery = $conn->query("SELECT COUNT(*) AS total_reports FROM reports");
$totalReports = $reportsQuery->fetch(PDO::FETCH_ASSOC)['total_reports'];
?>
        
        <section class="section-1">
            <h2>Welcome, <?php echo ucfirst(htmlspecialchars($username)); ?>!</h2>
            <p>You're logged in as <strong>Receptionist</strong>.</p>
        </section>

        <section class="section-2">
            <h2>Dashboard overview</h2>

            <div class="card">
                <h3>Patients</h3>
                <p><strong><?php echo $totalPatients; ?></strong></p>
            </div>
            <div class="card">
                <h3>Appointments</h3>
                <p><strong><?php echo $totalAppointments; ?></strong></p>
            </div>
            <div class="card">
                <h3>Total Billing</h3>
                <p><strong><?php echo number_format($totalBilling, 2); ?> RWF</strong></p>
            </div>
            <div class="card">
                <h3>Notifications</h3>
                <p><strong><?php echo $totalNotifications; ?></strong></p>
            </div>
        </section>

    <?php elseif ($user === 'doctor'): ?>

<?php
        // Get today's date in YYYY-MM-DD format
$today = date('Y-m-d');

// Total Patients (only assigned to doctor today)
$patientsQuery = $conn->prepare("SELECT COUNT(*) AS total_patients_today FROM patients WHERE assigned_to = ? AND DATE(created_at) = ?");
$patientsQuery->execute([$userId, $today]);
$totalPatientsToday = $patientsQuery->fetch(PDO::FETCH_ASSOC)['total_patients_today'] ?? 0;


// Total Appointments
$appointmentsQuery = $conn->query("SELECT COUNT(*) AS total_appointments FROM appointments");
$totalAppointments = $appointmentsQuery->fetch(PDO::FETCH_ASSOC)['total_appointments'];

// Total Lab Tests
$labTestsQuery = $conn->query("SELECT COUNT(*) AS total_lab_tests FROM laboratory_tests");
$totalLabTests = $labTestsQuery->fetch(PDO::FETCH_ASSOC)['total_lab_tests'];

// Total Doctors
$doctorsQuery = $conn->query("SELECT COUNT(*) AS total_doctors FROM doctors");
$totalDoctors = $doctorsQuery->fetch(PDO::FETCH_ASSOC)['total_doctors'];

// Total Billing
$billingQuery = $conn->query("SELECT SUM(total_amount) AS total_billing FROM billing");
$totalBilling = $billingQuery->fetch(PDO::FETCH_ASSOC)['total_billing'] ?? 0;

$notificationsQuery = $conn->prepare("SELECT COUNT(*) AS total_notifications FROM notifications WHERE user_type = :role");
$notificationsQuery->execute(['role' => 'Doctor']);
$totalNotifications = $notificationsQuery->fetch(PDO::FETCH_ASSOC)['total_notifications'];

// Total Reports
$reportsQuery = $conn->query("SELECT COUNT(*) AS total_reports FROM reports");
$totalReports = $reportsQuery->fetch(PDO::FETCH_ASSOC)['total_reports'];    
 ?>
        <section class="section-1">
            <h2>Welcome, <?php echo ucfirst(htmlspecialchars($username)); ?>!</h2>
            <p>You're logged in as <strong>Doctor</strong>.</p>
        </section>

        <section class="section-2">
            <h2>Dashboard overview</h2>

            <div class="card">
                <h3>My Patients (Today)</h3>
                <p><strong><?php echo $totalPatientsToday; ?></strong></p>
            </div>
            <div class="card">
                <h3>Appointments</h3>
                <p><strong><?php echo $totalAppointments; ?></strong></p>
            </div>
            <div class="card">
                <h3>Lab Tests</h3>
                <p><strong><?php echo $totalLabTests; ?></strong></p>
            </div>
            <div class="card">
                <h3>Notifications</h3>
                <p><strong><?php echo $totalNotifications; ?></strong></p>
            </div>
        </section>

        <?php elseif ($user === 'labTech'): ?>

<?php
// Count of distinct patients who have lab requests
$totalPatientsQuery = $conn->query("SELECT COUNT(DISTINCT patient_id) AS total_patients FROM lab_requests");

$totalPatients = $totalPatientsQuery->fetch(PDO::FETCH_ASSOC)['total_patients'];

// Count of pending lab requests
$labRequestsQuery = $conn->prepare("SELECT COUNT(*) AS total_lab_requests FROM lab_requests WHERE status = 'pending'");
$labRequestsQuery->execute();
$totalLabRequests = $labRequestsQuery->fetch(PDO::FETCH_ASSOC)['total_lab_requests'];

// Count of patients waiting for lab tests
$waitingPatientsQuery = $conn->prepare("SELECT COUNT(*) AS total_waiting_patients FROM patients WHERE status = 'Waiting for Lab Test'");
$waitingPatientsQuery->execute();
$totalWaitingPatients = $waitingPatientsQuery->fetch(PDO::FETCH_ASSOC)['total_waiting_patients'];

$notificationsQuery = $conn->prepare("SELECT COUNT(*) AS total_notifications FROM notifications WHERE user_type = :role");
$notificationsQuery->execute(['role' => 'labTech']);
$totalNotifications = $notificationsQuery->fetch(PDO::FETCH_ASSOC)['total_notifications'];

?>

<section class="section-1">
    <h2>Welcome, <?php echo ucfirst(htmlspecialchars($username)); ?>!</h2>
    <p>You're logged in as <strong>Lab Technician</strong>.</p>
</section>

<section class="section-2">
    <h2>Dashboard Overview</h2>

    <div class="card">
    <h3>Lab Tests</h3>
    <p><strong><?php echo $totalLabRequests; ?></strong> pending lab test requests</p>
</div>
    
<div class="card">
    <h3>Patients Waiting</h3>
    <p><strong><?php echo $totalWaitingPatients; ?></strong></p>
</div>

<div class="card">
    <h3>Notifications</h3>
    <p><strong><?php echo $totalNotifications; ?></strong></p>
</div>
</section>





<?php endif; ?>


   

</div>

<footer class="footer">
    <!-- Optional Footer -->
</footer>

</body>
</html>
