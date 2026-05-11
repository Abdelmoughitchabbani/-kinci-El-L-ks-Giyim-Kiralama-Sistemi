<?php
include 'header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // In a real app, you would save this to the database
    // $name = $_POST['full_name'];
    // $payment = $_POST['payment_method'];
    
    // Clear cart
    $_SESSION['cart'] = [];
} else {
    header("Location: index.php");
    exit;
}
?>

<main class="container" style="margin-top: 100px; text-align: center; margin-bottom: 100px;">
    <div style="font-size: 80px; color: green; margin-bottom: 20px;">✓</div>
    <h1 class="serif" style="font-size: 40px; margin-bottom: 20px;">Siparişiniz Alındı!</h1>
    <p style="color: #666; font-size: 18px; margin-bottom: 40px;">Bizi tercih ettiğiniz için teşekkür ederiz. Sipariş detayları e-posta adresinize gönderildi.</p>
    <a href="index.php" class="btn-primary" style="display: inline-block; padding: 15px 40px;">ALIŞVERİŞE DEVAM ET</a>
</main>

<?php include 'footer.php'; ?>
