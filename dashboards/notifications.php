<?php
session_start();
require_once '../app/db.php';
include '../includes/header.php';

$user_id = $_SESSION['user_id'] ?? null;
$username = $_SESSION['username'] ?? 'Unknown';
$role = $_SESSION['role'] ?? null;

$notifications = [];

try {
    if ($role === 'admin') {
        $stmt = $conn->query("SELECT * FROM notifications ORDER BY created_at DESC");
    } elseif ($user_id) {
        $stmt = $conn->prepare("SELECT * FROM notifications WHERE user_id = :uid ORDER BY created_at DESC");
        $stmt->bindParam(':uid', $user_id, PDO::PARAM_INT);
        $stmt->execute();
    }

    if ($stmt) {
        $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} catch (PDOException $e) {
    $notifications = [];
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mark_as_read_id'])) {
    $notifId = (int) $_POST['mark_as_read_id'];

    try {
        $update = $conn->prepare("UPDATE notifications SET status = 'read' WHERE id = :id AND user_id = :uid");
        $update->bindParam(':id', $notifId, PDO::PARAM_INT);
        $update->bindParam(':uid', $user_id, PDO::PARAM_INT); // assuming $user_id is defined
        $update->execute();
    } catch (PDOException $e) {
        // Log or handle the error
    }

    header("Location: notifications.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Notifications</title>
    <link rel="stylesheet" href="../css/dashboard-style.css">
    <link rel="stylesheet" href="../css/styles.css">
    <style>
        .notifications-section {
            padding: 30px;
        }

        .notification-box {
            background: #fff;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            border-left: 5px solid #007BFF;
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.08);
            transition: 0.3s;
        }

        .notification-box.unread {
            border-left-color: #dc3545;
            background-color: #fff7f7;
        }

        .notification-box h4 {
            margin: 0 0 10px;
            font-size: 18px;
            color: #333;
        }

        .notification-box p {
            margin: 0 0 10px;
            color: #555;
        }

        .notification-box small {
            color: #888;
        }

        .no-notifications {
            font-size: 18px;
            color: #666;
            text-align: center;
            margin-top: 40px;
        }
        .mark-btn {
    background-color: #007bff;
    color: white;
    padding: 5px 10px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 13px;
}

.mark-btn:hover {
    background-color: #0056b3;
}

.badge-read {
    display: inline-block;
    background-color: #d4edda;
    color: #155724;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: bold;
    margin-top: 8px;
}
  .badge-read:hover {
    background-color: #c3e6cb;
    color: # 155724;
    cursor: default;
    }
    </style>
</head>
<body>
<div class="body">
    <?php include '../includes/sidebar.php'; ?>

    <section class="notifications-section">
        <h2>Notifications</h2>

        <?php if (count($notifications) > 0): ?>
    <?php foreach ($notifications as $note): ?>
        <div class="notification-box <?php echo $note['status'] === 'unread' ? 'unread' : ''; ?>">
            <h4><?php echo htmlspecialchars($note['title']); ?></h4>
            <p><?php echo nl2br(htmlspecialchars($note['message'])); ?></p>
            <small>Posted on <?php echo date('d M Y, H:i', strtotime($note['created_at'])); ?></small>

            <?php if ($note['status'] === 'unread'): ?>
                <form method="POST" action="notifications.php" style="margin-top: 8px;">
                    <input type="hidden" name="mark_as_read_id" value="<?php echo $note['id']; ?>">
                    <button type="submit" class="mark-btn">Mark as Read</button>
                </form>
            <?php else: ?>
                <span class="badge-read">✓ Read</span>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p class="no-notifications">No notifications available.</p>
<?php endif; ?>

    </section>
</div>
</body>
</html>
