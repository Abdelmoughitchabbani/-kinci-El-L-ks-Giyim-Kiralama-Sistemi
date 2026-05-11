<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['product_id'])) {
    $product_id = (int)$_POST['product_id'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    // Fetch product details to store in cart (to minimize DB calls later)
    $stmt = $pdo->prepare("SELECT * FROM products_lux WHERE ProductID = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($product) {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Add to cart session
        $_SESSION['cart'][] = [
            'id' => $product_id,
            'brand' => $product['Brand'],
            'model' => $product['Model'],
            'image' => $product['image'],
            'daily_rate' => $product['DailyRate'],
            'start_date' => $start_date,
            'end_date' => $end_date,
            'size' => $product['Size']
        ];
    }
}

header("Location: cart.php");
exit;
?>
