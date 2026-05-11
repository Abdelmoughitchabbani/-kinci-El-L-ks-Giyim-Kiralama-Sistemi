<?php
include 'header.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'Ürün Sahibi') {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT p.*, c.KategoriAdi FROM products_new p LEFT JOIN categories c ON p.CategoryID = c.CategoryID WHERE p.OwnerID = ? ORDER BY p.ProductID DESC");
$stmt->execute([$user_id]);
$products = $stmt->fetchAll();
?>

<main class="container" style="margin-top: 60px;">
    <div style="display: grid; grid-template-columns: 250px 1fr; gap: 60px;">
        <aside>
            <ul style="list-style: none; font-size: 14px; line-height: 3;">
                <li style="border-bottom: 1px solid #eee;"><a href="dashboard.php">Genel Bakış</a></li>
                <li style="border-bottom: 1px solid #eee;"><a href="urunlerim.php" style="font-weight: 600;">Ürünlerim</a></li>
                <li style="border-bottom: 1px solid #eee;"><a href="urun_ekle.php">Ürün Ekle</a></li>
                <li style="border-bottom: 1px solid #eee;"><a href="logout.php">Güvenli Çıkış</a></li>
            </ul>
        </aside>

        <section>
            <h1 class="serif" style="font-size: 32px; margin-bottom: 30px;">Katalogdaki Ürünlerim</h1>

            <?php if(empty($products)): ?>
                <p>Henüz bir ürün eklemediniz. <a href="urun_ekle.php">Hemen bir tane ekleyin.</a></p>
            <?php else: ?>
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="border-bottom: 2px solid #000; text-align: left; font-size: 12px; text-transform: uppercase;">
                            <th style="padding: 10px 0;">Resim</th>
                            <th style="padding: 10px 0;">Marka/Model</th>
                            <th style="padding: 10px 0;">Fiyat</th>
                            <th style="padding: 10px 0;">Durum</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($products as $p): ?>
                            <tr style="border-bottom: 1px solid #eee;">
                                <td style="padding: 15px 0;">
                                    <?php if($p['image']): ?>
                                        <img src="uploads/<?php echo $p['image']; ?>" width="60" height="80" style="object-fit: cover;">
                                    <?php else: ?>
                                        <div style="width: 60px; height: 80px; background: #eee;"></div>
                                    <?php endif; ?>
                                </td>
                                <td style="padding: 15px 0;">
                                    <strong><?php echo htmlspecialchars($p['Brand']); ?></strong><br>
                                    <?php echo htmlspecialchars($p['Model']); ?>
                                </td>
                                <td style="padding: 15px 0;"><?php echo number_format($p['DailyRate'], 2); ?> TL</td>
                                <td style="padding: 15px 0;"><span style="color: green;">Aktif</span></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </section>
    </div>
</main>

<?php include 'footer.php'; ?>
