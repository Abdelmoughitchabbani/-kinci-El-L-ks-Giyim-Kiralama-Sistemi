<?php
include 'header.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$stmt = $pdo->prepare("SELECT p.*, c.KategoriAdi FROM products_lux p LEFT JOIN categories c ON p.CategoryID = c.CategoryID WHERE p.ProductID = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) {
    header("Location: index.php");
    exit;
}
?>

<main class="container" style="margin-top: 60px;">
    <div style="display: grid; grid-template-columns: 1fr 450px; gap: 60px;">
        <!-- Product Images -->
        <div class="product-gallery">
            <?php if (!empty($product['image']) && file_exists('uploads/' . $product['image'])): ?>
                <img src="uploads/<?php echo htmlspecialchars($product['image']); ?>" 
                     style="width: 100%; height: auto; display: block;" alt="">
            <?php else: ?>
                <img src="https://images.unsplash.com/photo-1539109132314-34a77bd68d9b?q=80&w=800&auto=format&fit=crop" 
                     style="width: 100%; height: auto; display: block;" alt="">
            <?php endif; ?>
        </div>

        <!-- Product Details & Form -->
        <div class="product-details">
            <p style="text-transform: uppercase; letter-spacing: 2px; color: #999; font-size: 12px; margin-bottom: 10px;">
                <?php echo htmlspecialchars($product['Brand']); ?>
            </p>
            <h1 class="serif" style="font-size: 36px; margin-bottom: 20px;"><?php echo htmlspecialchars($product['Model']); ?></h1>
            <p style="font-size: 20px; font-weight: 600; margin-bottom: 30px;"><?php echo number_format($product['DailyRate'], 2); ?> TL <span style="font-weight: 300; font-size: 14px;">/ Günlük</span></p>
            
            <div style="border-top: 1px solid #eee; padding-top: 30px; margin-top: 30px;">
                <p style="font-size: 14px; color: #666; margin-bottom: 20px;">
                    <strong>Beden:</strong> <?php echo htmlspecialchars($product['Size']); ?><br>
                    <strong>Kategori:</strong> <?php echo htmlspecialchars($product['KategoriAdi']); ?>
                </p>
                
                <form action="add_to_cart.php" method="POST">
                    <input type="hidden" name="product_id" value="<?php echo $product['ProductID']; ?>">
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px;">
                        <div class="form-group">
                            <label>Başlangıç Tarihi</label>
                            <input type="date" name="start_date" id="start_date" required style="border: 1px solid #eee; padding: 10px;">
                        </div>
                        <div class="form-group">
                            <label>Bitiş Tarihi</label>
                            <input type="date" name="end_date" id="end_date" required style="border: 1px solid #eee; padding: 10px;">
                        </div>
                    </div>

                    <div id="price-summary" style="background: #f9f9f9; padding: 20px; margin-bottom: 30px; display: none;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                            <span>Gün Sayısı:</span>
                            <span id="days-count">0</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; font-weight: 600; font-size: 18px;">
                            <span>Toplam Tutar:</span>
                            <span id="total-price">0 TL</span>
                        </div>
                    </div>

                    <button type="submit" class="btn-primary" style="margin-top: 0; width: 100%; border-radius: 0;">SEPETE EKLE</button>
                </form>
            </div>

            <div style="margin-top: 40px; font-size: 13px; line-height: 1.8; color: #666;">
                <p><strong>Açıklama:</strong> Bu ürün hijyen standartlarına uygun olarak temizlenmiş ve kontrol edilmiştir. Kiralama süresi sonunda orijinal paketi ile iade edilmelidir.</p>
            </div>
        </div>
    </div>
</main>

<script>
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');
    const priceSummary = document.getElementById('price-summary');
    const daysCount = document.getElementById('days-count');
    const totalPrice = document.getElementById('total-price');
    const dailyRate = <?php echo $product['DailyRate']; ?>;

    function calculatePrice() {
        if (startDateInput.value && endDateInput.value) {
            const start = new Date(startDateInput.value);
            const end = new Date(endDateInput.value);
            
            if (end > start) {
                const diffTime = Math.abs(end - start);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                
                priceSummary.style.display = 'block';
                daysCount.innerText = diffDays;
                totalPrice.innerText = (diffDays * dailyRate).toLocaleString('tr-TR') + ' TL';
            } else {
                priceSummary.style.display = 'none';
            }
        }
    }

    startDateInput.addEventListener('change', calculatePrice);
    endDateInput.addEventListener('change', calculatePrice);
</script>

<?php include 'footer.php'; ?>
