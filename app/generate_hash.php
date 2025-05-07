<?php
include '../app/db.php';  
/*
$newPassword = "bob2025";  
$username = "bob";  
*/
/*

$newPassword = "alice2025";  
$username = "alice"; 
*/
$newPassword = "charlie2025";  
$username = "charlie"; 


$hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);


$stmt = $conn->prepare("UPDATE users SET password = ? WHERE username = ?");
$stmt->bindParam(1, $hashedPassword);
$stmt->bindParam(2, $username);


if ($stmt->execute()) {
    echo $username . "<br>"; 
    echo "Password updated successfully!";
} else {
    echo "Error updating password.";
}
?>
