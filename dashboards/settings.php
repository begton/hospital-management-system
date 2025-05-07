<?php
session_start();
require_once '../app/db.php';
include '../includes/header.php';

// Only allow admin to access this page
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$successMessage = '';
$errorMessage = '';

// Handle hospital info update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_settings'])) {
    try {
        foreach ($_POST as $key => $value) {
            if ($key === 'update_settings') continue;
            $stmt = $conn->prepare("UPDATE settings SET setting_value = :value WHERE setting_key = :key");
            $stmt->bindParam(':key', $key);
            $stmt->bindParam(':value', $value);
            $stmt->execute();
        }
        $successMessage = "Settings updated successfully.";
    } catch (PDOException $e) {
        $errorMessage = "Error: " . $e->getMessage();
    }
}

// Fetch current settings
$stmt = $conn->query("SELECT setting_key, setting_value FROM settings");
$settings = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

// Fetch users for user management
$users = $conn->query("SELECT * FROM users")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Settings</title>
    <link rel="stylesheet" href="../css/dashboard-style.css">
    
    <link rel="stylesheet" href="../css/setting.css">

    
</head>
<body>
<div class="body">
    <?php include '../includes/sidebar.php'; ?>

    <section class="section-patients"> <!-- Was: section-send-results -->
    <h2>System Settings</h2>

    <?php if ($successMessage): ?>
        <div class="alert-message"><?= htmlspecialchars($successMessage) ?></div>
    <?php endif; ?>
    <?php if ($errorMessage): ?>
        <div class="alert-message" style="background-color: #f8d7da; color: #721c24;">
            <?= htmlspecialchars($errorMessage) ?>
        </div>
    <?php endif; ?>

    <div class="tab-buttons">
        <button onclick="showTab('hospital-info')" class="search-btn">🏥 Hospital Info</button>
        <button onclick="showTab('manage-users')" class="search-btn">👥 Manage Users</button>
        <button onclick="showTab('add-user')" class="search-btn">➕ Add User</button>
        <button onclick="showTab('notifications')" class="search-btn">🔔 Notification Settings</button>
        <button onclick="showTab('backup')" class="search-btn">💾 Backup & Restore</button>
    </div>

    <!-- Hospital Info -->
    <div class="tab-content active" id="hospital-info">
        <form method="POST" class="search-form">
            <div class="form-group">
                <label>Hospital Name</label>
                <input type="text" name="hospital_name" value="<?= htmlspecialchars($settings['hospital_name'] ?? '') ?>" required>
            </div>

            <div class="form-group">
                <label>System Email</label>
                <input type="email" name="system_email" value="<?= htmlspecialchars($settings['system_email'] ?? '') ?>" required>
            </div>

            <div class="form-group">
                <label>Contact Number</label>
                <input type="text" name="contact_number" value="<?= htmlspecialchars($settings['contact_number'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label>Opening Hours</label>
                <input type="text" name="opening_hours" value="<?= htmlspecialchars($settings['opening_hours'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label>Address</label>
                <input type="text" name="hospital_address" value="<?= htmlspecialchars($settings['hospital_address'] ?? '') ?>">
            </div>

            <input type="hidden" name="update_settings" value="1">
            <button type="submit" class="add-btn">Save Changes</button> <!-- Was: send-results-btn -->
        </form>
    </div>

    <!-- Manage Users -->
    <div class="tab-content" id="manage-users">
        <h3>Manage Users</h3>
        <table>
            <thead>
            <tr>
                <th>UserID</th>
                <th>Username</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= $user['user_id'] ?></td>
                    <td><?= htmlspecialchars($user['username']) ?></td>
                    <td><?= htmlspecialchars($user['full_name']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td><?= $user['role'] ?></td>
                    <td>
                        <a href="edit_user.php?id=<?= $user['user_id'] ?>" class="edit-btn">Edit</a> |
                        <a href="delete_user.php?id=<?= $user['user_id'] ?>" class="cancel-btn" onclick="return confirm('Delete this user?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Add User -->
    <div class="tab-content" id="add-user">
        <h3>Add New User</h3>
        <form method="POST" action="add_user.php" class="search-form">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" required>
            </div>
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="full_name" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <div class="form-group">
                <label>Role</label>
                <select name="role" required>
                    <option value="admin">Admin</option>
                    <option value="doctor">Doctor</option>
                    <option value="receptionist">Receptionist</option>
                    <option value="labTech">Lab Technician</option>
                </select>
            </div>
            <button type="submit" class="add-btn">Add User</button>
        </form>
    </div>
    <div class="tab-content" id="notifications">
    <section class="section-patients"> <!-- reuse your consistent section class -->
        <h3>🔔 Notification & Email Settings</h3>
        <div class="info-message">
            Feature coming soon...
        </div>
    </section>
</div>

<div class="tab-content" id="backup">
    <section class="section-patients">
        <h3>💾 System Backup & Restore</h3>
        <div class="info-message">
            This section will allow you to backup or restore the database in the future.
        </div>
    </section>
</div>

 </div>

<script>
function showTab(tabId) {
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.classList.remove('active');
    });
    document.getElementById(tabId).classList.add('active');
}

setTimeout(() => {
    const alertBox = document.querySelector('.alert-message');
    if (alertBox) {
        alertBox.style.opacity = '0';
        alertBox.style.transform = 'translateY(-10px)';
        setTimeout(() => alertBox.remove(), 500);
    }
}, 4000);
</script>
</body>
</html>
