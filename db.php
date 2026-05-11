<?php
$host = 'localhost';
$dbname = 'luksgiyimdb';
$username = 'root';
$password = '';
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    // Veritabanı yoksa hata vermemesi için sessiz kal (install.php oluşturacak)
}
?>
