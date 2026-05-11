<?php
$host = 'localhost';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>LuxeRent Kurulumu Başlatıldı...</h2>";

    // 1. Veritabanını oluştur
    $pdo->exec("CREATE DATABASE IF NOT EXISTS luksgiyimdb");
    $pdo->exec("USE luksgiyimdb");
    
    // 2. Tabloları temizle ve oluştur
    $pdo->exec("DROP TABLE IF EXISTS products_lux"); // Eski tabloyu sil (Temiz başlangıç)
    $pdo->exec("CREATE TABLE products_lux (
        ProductID INT AUTO_INCREMENT PRIMARY KEY,
        Brand VARCHAR(100),
        Model VARCHAR(100),
        DailyRate DECIMAL(10,2),
        Size VARCHAR(20),
        CategoryID INT,
        OwnerID INT,
        image VARCHAR(255)
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS categories (CategoryID INT AUTO_INCREMENT PRIMARY KEY, KategoriAdi VARCHAR(100))");
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (UserID INT AUTO_INCREMENT PRIMARY KEY, Ad VARCHAR(50), Soyad VARCHAR(50), Email VARCHAR(100) UNIQUE, Sifre VARCHAR(255), Role ENUM('Kiralayan', 'Ürün Sahibi'))");

    echo "<h3 style='color:green;'>✅ KURULUM BAŞARILI!</h3>";
    echo "Veritabanı ve tablolar hazırlandı.<br><br>";
    echo "<a href='index.php' style='padding:10px 20px; background:#000; color:#fff; text-decoration:none;'>SİTEYE GİT</a>";

} catch (Exception $e) {
    echo "<h3 style='color:red;'>❌ KURULUM HATASI:</h3> " . $e->getMessage();
}
?>
