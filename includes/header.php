
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<?php
if (!isset($_SESSION)) session_start();
require_once '../app/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php?error=" . urlencode("Please log in first."));
    exit();
}

$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Unknown';
$role = isset($_SESSION['role']) ? $_SESSION['role'] : 'guest';
$user_id = $_SESSION['user_id']; // Add this to ensure $user_id is defined

date_default_timezone_set('Africa/Kigali');
$currentTime = date("l, F j, Y - H:i:s");

$notifications = [];
$unread_count = 0;

try {
    if ($role === 'admin') {
        $stmt = $conn->query("SELECT * FROM notifications ORDER BY created_at DESC LIMIT 20");
        $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $countStmt = $conn->query("SELECT COUNT(*) FROM notifications WHERE status = 'unread'");
        $unread_count = $countStmt->fetchColumn();
    } elseif (isset($user_id)) {
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


<input type="checkbox" id="checkbox">
<header class="header">
    <h2 class="u-name">
        GAB <b>Hospital</b>
        <label for="checkbox">
            <i id="navbtn" class="fa fa-bars" aria-hidden="true"></i>
        </label>
    </h2>

    <div class="top-bar-right">
        <span class="datetime"><?php echo $currentTime; ?></span>
        <span class="user-info"><?php echo ucfirst($role); ?>: <strong><?php echo htmlspecialchars($username); ?></strong></span>
            <li>
                <a href="../dashboards/notifications.php">
                    <i class="fa fa-bell"></i>
                    <?php if ($unread_count > 0): ?>
                        <span style="color: red; font-size: 16px; margin-right: px;">●</span>
                    <?php endif; ?>
                </a>
            </li>
        
        <a href="../app/logout.php" title="Logout">
            <i class="fa fa-sign-out" aria-hidden="true"></i>
        </a>
    </div>
</header>
