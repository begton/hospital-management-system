<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="./css/login_style.css">
    <title>Log In | Hospital Management System</title>
</head>
<body>
<div class="login-container">
    <h1>Log In to access</h1>
    <p>Welcome</p>
    <form method="POST" action="app/login.php">
        <?php if (isset($_GET['error'])) echo '<p class="error">'.htmlspecialchars($_GET['error']).'</p>'; ?>
        <?php if (isset($_GET['success'])) echo '<p class="success">'.htmlspecialchars($_GET['success']).'</p>'; ?>
        <?php if (isset($_GET['message'])) echo '<p class="message">'.htmlspecialchars($_GET['message']).'</p>'; ?>


        <input type="text" name="username" placeholder="Enter Username" required>
        <input type="password" name="password" placeholder="Enter Password" required>
        <select name="role" required>
            <option value="">-- Select Role --</option>
            <option value="admin">Admin</option>
            <option value="doctor">Doctor</option>
            <option value="receptionist">Receptionist</option>
            <option value="labTech">Lab Technician</option>
        </select>
        <button type="submit" name="btn">Login</button>
    </form>
    <a href="#">Forgot Password?</a>
</div>
</body>
</html>
