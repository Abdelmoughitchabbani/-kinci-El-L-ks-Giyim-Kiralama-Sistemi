<?php
include 'header.php';

// Handle item removal
if (isset($_GET['remove'])) {
    $index = (int)$_GET['remove'];
    if (isset($_SESSION['cart'][$index])) {
        unset($_SESSION['cart'][$index]);
        $_SESSION['cart'] = array_values($_SESSION['cart']); // Re-index array
    }
    header("Location: cart.php");
    exit;
}

$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$grand_total = 0;
?>

<main class="container" style="margin-top: 50px;">
    <h1 class="serif" style="font-size: 32px; margin-bottom: 30px;">Sepetim</h1>

    <?php if (empty($cart)): ?>
        <div style="text-align: center; padding: 100px 0; border: 1px solid #eee;">
            <p style="color: #999;">Sepetiniz şu an boş.</p>
            <a href="index.php" class="btn-primary" style="display: inline-block; margin-top: 20px;">Alışverişe Başla</a>
        </div>
    <?php else: ?>
        <div style="display: grid; grid-template-columns: 1fr 350px; gap: 40px;">
            <!-- Cart Items -->
            <div>
                <?php foreach ($cart as $index => $item): 
                    $start = new DateTime($item['start_date']);
                    $end = new DateTime($item['end_date']);
                    $days = $start->diff($end)->days;
                    if ($days <= 0) $days = 1;
                    $item_total = $days * $item['daily_rate'];
                    $grand_total += $item_total;
                ?>
                    <div style="display: flex; gap: 20px; padding: 20px 0; border-bottom: 1px solid #eee;">
                        <img src="uploads/<?php echo $item['image']; ?>" style="width: 100px; height: 150px; object-fit: cover; border: 1px solid #eee;">
                        <div style="flex: 1;">
                            <p style="font-size: 11px; color: #999; text-transform: uppercase; margin: 0;"><?php echo $item['brand']; ?></p>
                            <h3 style="font-family: serif; font-size: 20px; margin: 5px 0;"><?php echo $item['model']; ?></h3>
                            <p style="font-size: 13px; color: #666; margin: 10px 0;">
                                <strong>Beden:</strong> <?php echo $item['size']; ?><br>
                                <strong>Tarihler:</strong> <?php echo date('d.m.Y', strtotime($item['start_date'])); ?> - <?php echo date('d.m.Y', strtotime($item['end_date'])); ?> (<?php echo $days; ?> Gün)
                            </p>
                            <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-top: 20px;">
                                <a href="cart.php?remove=<?php echo $index; ?>" style="color: #c00; font-size: 12px; text-decoration: underline;">Kaldır</a>
                                <p style="font-weight: 600; font-size: 18px;"><?php echo number_format($item_total, 2); ?> TL</p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Summary -->
            <div style="background: #f9f9f9; padding: 30px; height: fit-content; border: 1px solid #eee;">
                <h3 style="font-family: serif; font-size: 22px; margin-bottom: 25px;">Sipariş Özeti</h3>
                <div style="display: flex; justify-content: space-between; margin-bottom: 15px; font-size: 14px;">
                    <span>Ara Toplam</span>
                    <span><?php echo number_format($grand_total, 2); ?> TL</span>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 15px; font-size: 14px;">
                    <span>Kargo</span>
                    <span style="color: green;">Ücretsiz</span>
                </div>
                <hr style="border: 0; border-top: 1px solid #ddd; margin: 20px 0;">
                <div style="display: flex; justify-content: space-between; font-weight: bold; font-size: 20px; margin-bottom: 30px;">
                    <span>Toplam</span>
                    <span><?php echo number_format($grand_total, 2); ?> TL</span>
                </div>
                <a href="checkout.php" class="btn-primary" style="display: block; text-align: center; text-decoration: none; border-radius: 0;">ÖDEMEYE GEÇ</a>
                
                <div style="margin-top: 20px; text-align: center;">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/5e/Visa_Inc._logo.svg/2560px-Visa_Inc._logo.svg.png" style="height: 15px; margin: 0 5px;">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/2a/Mastercard-logo.svg/1280px-Mastercard-logo.svg.png" style="height: 15px; margin: 0 5px;">
                </div>
            </div>
        </div>
    <?php endif; ?>
</main>

<?php include 'footer.php'; ?>
