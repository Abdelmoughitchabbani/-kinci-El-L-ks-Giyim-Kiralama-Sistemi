<?php
include 'header.php';

$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
if (empty($cart)) {
    header("Location: index.php");
    exit;
}

$grand_total = 0;
foreach ($cart as $item) {
    $start = new DateTime($item['start_date']);
    $end = new DateTime($item['end_date']);
    $days = $start->diff($end)->days;
    if ($days <= 0) $days = 1;
    $grand_total += ($days * $item['daily_rate']);
}
?>

<main class="container" style="margin-top: 50px; margin-bottom: 100px;">
    <div style="max-width: 900px; margin: 0 auto;">
        <h1 class="serif" style="font-size: 32px; margin-bottom: 40px; text-align: center;">Güvenli Ödeme</h1>

        <form action="process_checkout.php" method="POST" style="display: grid; grid-template-columns: 1fr 350px; gap: 50px;">
            <!-- Left Side: Shipping & Info -->
            <div>
                <section style="margin-bottom: 40px;">
                    <h3 style="font-family: serif; border-bottom: 1px solid #000; padding-bottom: 10px; margin-bottom: 25px;">1. Kişisel Bilgiler</h3>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div class="form-group">
                            <label style="display: block; font-size: 12px; margin-bottom: 8px;">Ad Soyad (Semia/Knia)</label>
                            <input type="text" name="full_name" required style="width: 100%; padding: 12px; border: 1px solid #ddd; outline: none;">
                        </div>
                        <div class="form-group">
                            <label style="display: block; font-size: 12px; margin-bottom: 8px;">Telefon (Raqem)</label>
                            <input type="tel" name="phone" required style="width: 100%; padding: 12px; border: 1px solid #ddd; outline: none;">
                        </div>
                    </div>
                </section>

                <section style="margin-bottom: 40px;">
                    <h3 style="font-family: serif; border-bottom: 1px solid #000; padding-bottom: 10px; margin-bottom: 25px;">2. Teslimat Adresi</h3>
                    <div class="form-group">
                        <label style="display: block; font-size: 12px; margin-bottom: 8px;">Açık Adres</label>
                        <textarea name="address" required rows="4" style="width: 100%; padding: 12px; border: 1px solid #ddd; outline: none; resize: none;"></textarea>
                    </div>
                </section>

                <section>
                    <h3 style="font-family: serif; border-bottom: 1px solid #000; padding-bottom: 10px; margin-bottom: 25px;">3. Ödeme Yöntemi</h3>
                    <div style="display: flex; gap: 20px;">
                        <label style="flex: 1; border: 1px solid #ddd; padding: 20px; display: flex; align-items: center; cursor: pointer;">
                            <input type="radio" name="payment_method" value="credit_card" checked style="margin-right: 10px;">
                            <div>
                                <strong style="display: block; font-size: 14px;">Kredi Kartı</strong>
                                <span style="font-size: 12px; color: #999;">Güvenli Iyzico altyapısı ile</span>
                            </div>
                        </label>
                        <label style="flex: 1; border: 1px solid #ddd; padding: 20px; display: flex; align-items: center; cursor: pointer;">
                            <input type="radio" name="payment_method" value="cod" style="margin-right: 10px;">
                            <div>
                                <strong style="display: block; font-size: 14px;">Kapıda Ödeme</strong>
                                <span style="font-size: 12px; color: #999;">Teslimat sırasında öde</span>
                            </div>
                        </label>
                    </div>
                </section>
            </div>

            <!-- Right Side: Order Summary -->
            <div style="background: #f9f9f9; padding: 30px; height: fit-content; position: sticky; top: 20px; border: 1px solid #eee;">
                <h3 style="font-family: serif; font-size: 20px; margin-bottom: 20px;">Siparişiniz</h3>
                
                <?php foreach ($cart as $item): ?>
                    <div style="display: flex; justify-content: space-between; font-size: 13px; margin-bottom: 10px; color: #666;">
                        <span><?php echo $item['brand']; ?> - <?php echo $item['model']; ?></span>
                        <span><?php echo number_format($item['daily_rate'], 0); ?> TL</span>
                    </div>
                <?php endforeach; ?>

                <hr style="border: 0; border-top: 1px solid #ddd; margin: 20px 0;">
                
                <div style="display: flex; justify-content: space-between; font-weight: bold; font-size: 18px; margin-bottom: 30px;">
                    <span>Ödenecek Tutar</span>
                    <span><?php echo number_format($grand_total, 2); ?> TL</span>
                </div>

                <button type="submit" class="btn-primary" style="width: 100%; border-radius: 0;">SİPARİŞİ ONAYLA</button>
                
                <p style="font-size: 11px; color: #999; margin-top: 20px; text-align: center; line-height: 1.4;">
                    "Siparişi Onayla" butonuna basarak Mesafeli Satış Sözleşmesi'ni kabul etmiş sayılırsınız.
                </p>
            </div>
        </form>
    </div>
</main>

<?php include 'footer.php'; ?>
