<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../app/db.php';

$user_id = $_SESSION['user_id'] ?? null;
$username = $_SESSION['username'] ?? 'Unknown';
$role = $_SESSION['role'] ?? null;

$notifications = [];
$unread_count = 0;

try {
    if ($role === 'admin') {
        $stmt = $conn->query("SELECT * FROM notifications ORDER BY created_at DESC LIMIT 20");
        $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $countStmt = $conn->query("SELECT COUNT(*) FROM notifications WHERE status = 'unread'");
        $unread_count = $countStmt->fetchColumn();
    } elseif ($user_id) {
        $stmt = $conn->prepare("SELECT * FROM notifications WHERE user_id = :uid ORDER BY created_at DESC LIMIT 20");
        $stmt->bindParam(':uid', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $countStmt = $conn->prepare("SELECT COUNT(*) FROM notifications WHERE user_id = :uid AND status = 'unread'");
        $countStmt->bindParam(':uid', $user_id, PDO::PARAM_INT);
        $countStmt->execute();
        $unread_count = $countStmt->fetchColumn();
    }
} catch (PDOException $e) {
    $notifications = [];
    $unread_count = 0;
}
?>

<nav class="side-bar">
    <div class="user-p">
        <img src="img/prof.png" alt="profile">
        <h4>@<?php echo htmlspecialchars($username); ?></h4>
    </div>

    <?php if ($role === 'receptionist'): ?>
        <ul>
            <li><a href="index.php"><i class="fa fa-tachometer"></i><span>Dashboard</span></a></li>
            <li><a href="../dashboards/patients.php"><i class="fa fa-user"></i><span>Patients Management</span></a></li>
            <li><a href="../dashboards/list_of_appointment.php"><i class="fa fa-calendar"></i><span>Appointments</span></a></li>
            <li><a href="../dashboards/doctors.php"><i class="fa fa-user-md"></i><span>Doctors</span></a></li>
            <li><a href="../dashboards/billing.php"><i class="fa fa-credit-card"></i><span>Billing / Payments</span></a></li>
            <li>
                <a href="../dashboards/notifications.php">
                    <i class="fa fa-bell"></i>
                    <span>Notifications</span>
                    <?php if ($unread_count > 0): ?>
                        <span style="color: red; font-size: 16px; margin-left: 5px;">●</span>
                    <?php endif; ?>
                </a>
            </li>
            <li><a href="../app/logout.php"><i class="fa fa-sign-out"></i><span>Logout</span></a></li>
        </ul>

    <?php elseif ($role === 'doctor'): ?>
        <ul>
            <li><a href="index.php"><i class="fa fa-tachometer"></i><span>Dashboard</span></a></li>
            <li><a href="../dashboards/my_patients.php?history=1" class="dashboard-link"><i class="fa fa-user"></i><span>View My Patients</span></a></li>
            <li><a href="../dashboards/my_appointment.php"><i class="fa fa-calendar"></i><span>Appointments</span></a></li>
            <li><a href="../dashboards/my_lab_patients.php"><i class="fa fa-flask"></i><span>Laboratory</span></a></li>
            <li>
                <a href="../dashboards/notifications.php">
                    <i class="fa fa-bell"></i>
                    <span>Notifications</span>
                    <?php if ($unread_count > 0): ?>
                        <span style="color: red; font-size: 16px; margin-left: 5px;">●</span>
                    <?php endif; ?>
                </a>
            </li>
            <li><a href="../app/logout.php"><i class="fa fa-sign-out"></i><span>Logout</span></a></li>
        </ul>

    <?php elseif ($role === 'labTech'): ?>
        <ul>
            <li><a href="index.php"><i class="fa fa-tachometer"></i><span>Dashboard</span></a></li>
            <li><a href="../dashboards/view_lab_resquests.php"><i class="fa fa-user"></i><span>Patients Requests</span></a></li>
            <li><a href="../dashboards/lab_requests.php"><i class="fa fa-flask"></i><span>Laboratory</span></a></li>
            <li>
                <a href="../dashboards/notifications.php">
                    <i class="fa fa-bell"></i>
                    <span>Notifications</span>
                    <?php if ($unread_count > 0): ?>
                        <span style="color: red; font-size: 16px; margin-left: 5px;">●</span>
                    <?php endif; ?>
                </a>
            </li>
            <li><a href="../app/logout.php"><i class="fa fa-sign-out"></i><span>Logout</span></a></li>
        </ul>

    <?php elseif ($role === 'admin'): ?>
        <ul>
            <li><a href="index.php"><i class="fa fa-tachometer"></i><span>Dashboard</span></a></li>
            <li><a href="../dashboards/patients.php"><i class="fa fa-user"></i><span>Patients Management</span></a></li>
            <li><a href="../dashboards/list_of_appointment.php"><i class="fa fa-calendar"></i><span>Appointments</span></a></li>
            <li><a href="../dashboards/doctors.php"><i class="fa fa-user-md"></i><span>Doctors & Staffs</span></a></li>
            <li><a href="../dashboards/billing.php"><i class="fa fa-credit-card"></i><span>Billing / Payments</span></a></li>
            <li><a href="../dashboards/view_lab_resquests.php"><i class="fa fa-flask"></i><span>Laboratory</span></a></li>
            <li><a href="../dashboards/admin_reports.php"><i class="fa fa-bar-chart"></i><span>Reports & Analytics</span></a></li>
            <li>
                <a href="../dashboards/notifications.php">
                    <i class="fa fa-bell"></i>
                    <span>Notifications</span>
                    <?php if ($unread_count > 0): ?>
                        <span style="color: red; font-size: 16px; margin-left: 5px;">●</span>
                    <?php endif; ?>
                </a>
            </li>
            <li><a href="../dashboards/settings.php"><i class="fa fa-cog"></i><span>Settings</span></a></li>
            <li><a href="../app/logout.php"><i class="fa fa-sign-out"></i><span>Logout</span></a></li>
        </ul>

    <?php else: ?>
        <p>Unknown role. <a href="../app/logout.php">Logout</a></p>
    <?php endif; ?>
</nav>
