<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once 'db.php';
$cart_count = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>LuxeRent | P2P Luxury</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header style="border-bottom: 1px solid #eee; padding: 15px 0;">
    <div class="container" style="display: flex; justify-content: space-between; align-items: center;">
        <a href="index.php" style="font-family: serif; font-size: 26px; font-weight: bold; letter-spacing: 3px;">LUXERENT</a>
        <nav style="display: flex; gap: 20px; font-size: 12px; text-transform: uppercase;">
            <a href="index.php">Koleksiyon</a>
            <a href="cart.php" style="position: relative;">
                Sepetim
                <?php if($cart_count > 0): ?>
                    <span style="background: #000; color: #fff; border-radius: 50%; padding: 2px 6px; font-size: 9px; position: absolute; top: -10px; right: -15px;"><?php echo $cart_count; ?></span>
                <?php endif; ?>
            </a>
            <?php if(isset($_SESSION['user_id'])): ?>
                <a href="dashboard.php">Panelim</a>
                <a href="logout.php">Çıkış</a>
            <?php else: ?>
                <a href="login.php">Giriş</a>
                <a href="register.php">Kayıt</a>
            <?php endif; ?>
        </nav>
    </div>
</header>
