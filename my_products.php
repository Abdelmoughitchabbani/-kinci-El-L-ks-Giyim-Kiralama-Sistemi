<?php
include 'header.php';

// Güvenlik: Sadece Ürün Sahibi girebilir
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'Ürün Sahibi') {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Sahibine ait ürünleri getir
try {
    $stmt = $pdo->prepare("SELECT p.*, c.KategoriAdi 
                           FROM products_new p 
                           LEFT JOIN categories c ON p.CategoryID = c.CategoryID 
                           WHERE p.OwnerID = ? 
                           ORDER BY p.ProductID DESC");
    $stmt->execute([$user_id]);
    $my_products = $stmt->fetchAll();
} catch (PDOException $e) {
    $my_products = [];
}
?>

<main class="container" style="margin-top: 60px;">
    <div style="display: grid; grid-template-columns: 250px 1fr; gap: 60px;">
        <!-- Sidebar Navigation -->
        <aside>
            <ul style="list-style: none; font-size: 14px; line-height: 3;">
                <li style="border-bottom: 1px solid #eee;"><a href="dashboard.php">Genel Bakış</a></li>
                <li style="border-bottom: 1px solid #eee;"><a href="my_products.php" style="font-weight: 600; color: #000;">● Ürünlerim</a></li>
                <li style="border-bottom: 1px solid #eee;"><a href="add_product.php">Yeni Ürün Ekle</a></li>
                <li style="border-bottom: 1px solid #eee;"><a href="logout.php">Güvenli Çıkış</a></li>
            </ul>
        </aside>

        <!-- Product List Section -->
        <section>
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px;">
                <h1 class="serif" style="font-size: 32px;">Katalogdaki Ürünlerim</h1>
                <a href="add_product.php" class="btn-primary" style="width: auto; padding: 10px 20px; font-size: 11px;">+ YENİ EKLE</a>
            </div>

            <?php if(empty($my_products)): ?>
                <div style="text-align: center; padding: 50px; border: 1px dashed #ccc;">
                    <p style="color: #666; margin-bottom: 20px;">Henüz sistemde kiralık bir ürününüz bulunmuyor.</p>
                    <a href="add_product.php" style="text-decoration: underline; font-weight: 600;">İlk ürününüzü hemen ekleyin →</a>
                </div>
            <?php else: ?>
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="border-bottom: 2px solid #000; text-align: left; font-size: 11px; text-transform: uppercase; letter-spacing: 1px;">
                            <th style="padding: 15px 0;">Ürün Görseli</th>
                            <th style="padding: 15px 0;">Detaylar</th>
                            <th style="padding: 15px 0;">Günlük Fiyat</th>
                            <th style="padding: 15px 0;">Durum</th>
                            <th style="padding: 15px 0; text-align: right;">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($my_products as $p): ?>
                            <tr style="border-bottom: 1px solid #eee;">
                                <td style="padding: 20px 0;">
                                    <?php if(!empty($p['image']) && file_exists('uploads/' . $p['image'])): ?>
                                        <img src="uploads/<?php echo $p['image']; ?>" style="width: 70px; height: 90px; object-fit: cover; border: 1px solid #eee;">
                                    <?php else: ?>
                                        <div style="width: 70px; height: 90px; background: #f5f5f5; display: flex; align-items: center; justify-content: center; font-size: 10px; color: #999;">FOTO YOK</div>
                                    <?php endif; ?>
                                </td>
                                <td style="padding: 20px 0;">
                                    <strong style="display: block; font-size: 14px;"><?php echo htmlspecialchars($p['Brand']); ?></strong>
                                    <span style="font-size: 13px; color: #666;"><?php echo htmlspecialchars($p['Model']); ?></span>
                                    <span style="display: block; font-size: 12px; color: #999; margin-top: 5px;">Beden: <?php echo htmlspecialchars($p['Size']); ?></span>
                                </td>
                                <td style="padding: 20px 0; font-weight: 600;"><?php echo number_format($p['DailyRate'], 2); ?> TL</td>
                                <td style="padding: 20px 0;">
                                    <span style="display: inline-block; padding: 4px 10px; background: #e8f5e9; color: #2e7d32; font-size: 10px; border-radius: 20px; font-weight: 600;">AKTİF</span>
                                </td>
                                <td style="padding: 20px 0; text-align: right;">
                                    <a href="#" style="font-size: 12px; text-decoration: underline;">Düzenle</a>
                                    <span style="margin: 0 10px; color: #eee;">|</span>
                                    <a href="#" style="font-size: 12px; color: #c62828;">Sil</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </section>
    </div>
</main>

<?php include 'footer.php'; ?>
