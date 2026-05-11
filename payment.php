<?php
include 'header.php';

$product_id = isset($_GET['product_id']) ? (int)$_GET['product_id'] : 0;
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';

// Basic date validation & calculation
$days = 0;
$total = 0;
$product = null;

if ($product_id && $start_date && $end_date) {
    $stmt = $pdo->prepare("SELECT * FROM products_new WHERE ProductID = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch();

    if ($product) {
        $start = new DateTime($start_date);
        $end = new DateTime($end_date);
        $interval = $start->diff($end);
        $days = $interval->days;
        if ($days <= 0) $days = 1; // Minimum 1 day
        $total = $days * $product['DailyRate'];
    }
} else {
    header("Location: index.php");
    exit;
}
?>

<main class="container">
    <div class="checkout-layout">
        <!-- Payment Form -->
        <div class="payment-details">
            <h1 class="serif" style="font-size: 32px; margin-bottom: 40px;">Ödeme Bilgileri</h1>
            
            <form action="process_rental.php" method="POST">
                <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                <input type="hidden" name="start_date" value="<?php echo $start_date; ?>">
                <input type="hidden" name="end_date" value="<?php echo $end_date; ?>">
                <input type="hidden" name="total_price" value="<?php echo $total; ?>">

                <div class="form-group">
                    <label>Kart Üzerindeki İsim</label>
                    <input type="text" placeholder="AD SOYAD" required>
                </div>
                
                <div class="form-group">
                    <label>Kart Numarası</label>
                    <input type="text" placeholder="0000 0000 0000 0000" required>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label>Son Kullanma Tarihi</label>
                        <input type="text" placeholder="AA / YY" required>
                    </div>
                    <div class="form-group">
                        <label>CVC</label>
                        <input type="text" placeholder="000" required>
                    </div>
                </div>

                <div style="margin-top: 40px;">
                    <button type="submit" class="btn-primary">Ödemeyi Tamamla (<?php echo number_format($total, 2); ?> TL)</button>
                </div>
            </form>
        </div>

        <!-- Summary -->
        <div class="summary-side">
            <div class="summary-card">
                <h3 class="summary-title">Sipariş Özeti</h3>
                
                <div style="display: flex; gap: 15px; margin-bottom: 25px;">
                    <img src="https://images.unsplash.com/photo-1539109132314-34a77bd68d9b?q=80&w=100&auto=format&fit=crop" 
                         style="width: 80px; height: 100px; object-fit: cover;" alt="">
                    <div>
                        <p style="font-size: 14px; font-weight: 600;"><?php echo htmlspecialchars($product['Brand']); ?></p>
                        <p style="font-size: 13px; color: #666;"><?php echo htmlspecialchars($product['Model']); ?></p>
                        <p style="font-size: 12px; color: #999; margin-top: 5px;">Beden: <?php echo htmlspecialchars($product['Size']); ?></p>
                    </div>
                </div>

                <div class="summary-row">
                    <span>Kiralama Süresi</span>
                    <span><?php echo $days; ?> Gün</span>
                </div>
                <div class="summary-row">
                    <span>Başlangıç</span>
                    <span><?php echo date('d.m.Y', strtotime($start_date)); ?></span>
                </div>
                <div class="summary-row">
                    <span>Bitiş</span>
                    <span><?php echo date('d.m.Y', strtotime($end_date)); ?></span>
                </div>
                
                <div class="summary-row total-row">
                    <span>Toplam</span>
                    <span><?php echo number_format($total, 2); ?> TL</span>
                </div>

                <p style="font-size: 11px; color: #999; margin-top: 20px; line-height: 1.5;">
                    * Ödemeniz onaylandığında kiralama sözleşmesi e-posta adresinize gönderilecektir.
                </p>
            </div>
        </div>
    </div>
</main>

<?php include 'footer.php'; ?>
