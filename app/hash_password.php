<?php
$passwords = [
    'charlie2025',
    'prof.alice2025',
    'Dr.aime2025',
    'Dr.Nadia2025',
    'prof.eugene2025',
    'prof.diane2025',
    'prof.clement2025',
    'dr.ingabire2025',
    'dr.uwase2025',
    'dr.claude2025',
    'prof.uwineza2025',
    'Dr.Alice2025'
];

foreach ($passwords as $password) {
    $hash = password_hash($password, PASSWORD_BCRYPT);
    echo "Password: $password<br>Hash: $hash<br><br>";
}
?>

<!--
INSERT INTO users (user_id, username, email, password, role, full_name, created_at) VALUES
(3, 'prof.eugene', 'eugene.hakizimana@gmail.com', '$2y$10$C7KGbp9JpGsZvVwrDTE/PeM9WtksUaLUP7eyoBpkHvpSLKTEeqh7i', 'doctor', 'Prof. Eugene Hakizimana', NOW()), 
(4, 'prof.diane', 'diane.mukamana@gmail.com', '$2y$10$aP1O4O6hQc2HeCS7wnCnhuSnJHti6SoZIN7KaUuWE2Gaa3SvZZCj6', 'doctor', 'Prof. Diane Mukamana', NOW()), 
(6, 'prof.clement', 'jean.clement@gmail.com', '$2y$10$f1yw/R9H94FG3WuNA0LyOuct25dFkXyKfCNrqeo6HM8BQtKqmwJZq', 'doctor', 'Prof. Jean Clement', NOW()), 
(7, 'dr.ingabire', 'clarisse.ingabire@gmail.com', '$2y$10$iHVmGd3U1LZSZGSDe013/OgEdteo4FMV68jC8.FgZIdvZH8tu3ZA. ', 'doctor', 'Dr. Clarisse Ingabire', NOW()), 
(8, 'dr.uwase', 'nadia.uwase@gmail.com', '$2y$10$xlhcEOR1xR6WNdz27A.Dg.xKtEjbYpzX3UY7wR7wHNcUOzer.wt2i', 'doctor', 'Dr. Nadia Uwase', NOW()), 
(9, 'dr.claude', 'jean.claude@gmail.com', '$2y$10$mGD5DQ23PHK2L.JcPvX3Me4GFaP.m9FTBbSK0TJw8AeIRsYZg1taG', 'doctor', 'Dr. Jean Claude Niyonsenga', NOW()), 
(10, 'prof.uwineza', 'jacques.uwineza@gmail.com', '$2y$10$OUMGafN3h2tX6S9d4Sf9OuO1rA1iAst8Wi1P6ZYvKdqgY5Enkuvuu', 'doctor', 'Prof. Jacques Uwineza', NOW()); 