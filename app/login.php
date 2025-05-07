<?php 
session_start();
include '../app/db.php'; // Adjust path if needed

if (isset($_POST['username'], $_POST['password'], $_POST['role'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $role = trim($_POST['role']);

    if (empty($username) || empty($password) || empty($role)) {
        header("Location: ../login.php?error=" . urlencode("All fields are required."));
        exit();
    }

    // Prepare query to fetch user
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username AND role = :role");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':role', $role);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        if (password_verify($password, $row['password'])) {
            // Set common session values
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['role'] = $row['role'];

            // If doctor, also set doctor_id
            if ($role === 'doctor') {
                $_SESSION['doctor_id'] = $row['user_id']; // Use user_id or doctor_id if exists
            }

            // Redirect based on role
            switch ($role) {
                case 'admin':
                case 'doctor':
                case 'receptionist':
                case 'labTech':
                    header("Location: ../dashboards/index.php");
                    break;
                default:
                    header("Location: ../login.php?error=" . urlencode("Invalid role."));
                    break;
            }
            exit();
        } else {
            header("Location: ../login.php?error=" . urlencode("Incorrect password."));
            exit();
        }
    } else {
        header("Location: ../login.php?error=" . urlencode("User not found or role mismatch."));
        exit();
    }
} else {
    header("Location: ../login.php?error=" . urlencode("Invalid request."));
    exit();
}
?>
