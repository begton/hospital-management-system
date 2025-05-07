<?php
session_start();

// Unset all session variables
$_SESSION = [];

// Destroy the session
session_destroy();

// Optional: Clear the session cookie (if using cookies)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Redirect to login page
header("Location: ../login.php?message=" . urlencode("You have been logged out."));
exit();
?>

<script>
  setTimeout(() => {
    const alertBox = document.querySelector('.alert-message');
    if (alertBox) {
      alertBox.style.opacity = '0';
      alertBox.style.transform = 'translateY(-10px)';
      setTimeout(() => alertBox.remove(), 500);
    }
  }, 4000);
</script>
