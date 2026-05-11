<?php
include 'header.php';

$search = isset($_GET['search']) ? $_GET['search'] : '';
$brand = isset($_GET['brand']) ? $_GET['brand'] : '';

$query = "SELECT * FROM products_lux WHERE 1=1";
$params = [];

if ($search) {
    $query .= " AND (Model LIKE ? OR Brand LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if ($brand) {
    $query .= " AND Brand = ?";
    $params[] = $brand;
}

$query .= " ORDER BY ProductID DESC";

try {
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Get unique brands for the filter
    $brands = $pdo->query("SELECT DISTINCT Brand FROM products_lux")->fetchAll(PDO::FETCH_COLUMN);
} catch (Exception $e) {
    $products = [];
}
?>
<div style="height: 40vh; background: #f9f9f9; display: flex; align-items: center; justify-content: center; background: url('https://images.unsplash.com/photo-1490481651871-ab68de25d43d?q=80&w=2070') center/cover; margin-bottom: 30px;">
    <div style="background: rgba(255,255,255,0.9); padding: 40px; text-align: center; border: 1px solid #000; backdrop-filter: blur(5px);">
        <h1 style="font-family:serif; font-size: 42px; margin: 0; letter-spacing: 5px;">LÜKSÜ KEŞFET</h1>
        <p style="letter-spacing: 3px; font-size: 11px; margin-top: 10px; color: #333;">DÜNYA MARKALARI ŞİMDİ SİZİNLE</p>
    </div>
</div>

<main class="container">
    <!-- Filter Section -->
    <section style="margin-bottom: 40px; background: #fff; padding: 20px; border: 1px solid #eee;">
        <form action="index.php" method="GET" style="display: flex; gap: 15px; flex-wrap: wrap; align-items: flex-end;">
            <div style="flex: 1; min-width: 200px;">
                <label style="display: block; font-size: 11px; text-transform: uppercase; margin-bottom: 5px; color: #999;">Ürün veya Model Ara</label>
                <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Örn: Gucci, Elbise..." style="width: 100%; padding: 12px; border: 1px solid #ddd; outline: none;">
            </div>
            <div style="width: 200px;">
                <label style="display: block; font-size: 11px; text-transform: uppercase; margin-bottom: 5px; color: #999;">Marka Seç</label>
                <select name="brand" style="width: 100%; padding: 12px; border: 1px solid #ddd; outline: none; background: #fff;">
                    <option value="">Tüm Markalar</option>
                    <?php foreach($brands as $b): ?>
                        <option value="<?php echo $b; ?>" <?php echo $brand == $b ? 'selected' : ''; ?>><?php echo $b; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" style="background: #000; color: #fff; border: none; padding: 13px 30px; cursor: pointer; text-transform: uppercase; font-size: 12px; letter-spacing: 1px;">Filtrele</button>
            <?php if($search || $brand): ?>
                <a href="index.php" style="padding: 13px 0; color: #666; font-size: 12px; text-decoration: underline;">Sıfırla</a>
            <?php endif; ?>
        </form>
    </section>

    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 30px;">
        <?php foreach($products as $p): ?>
            <a href="product.php?id=<?php echo $p['ProductID']; ?>" style="text-decoration: none; color: inherit; display: block; margin-bottom: 30px; transition: transform 0.3s ease;" class="product-card">
                <div style="aspect-ratio: 2/3; background: #eee; margin-bottom: 15px; overflow: hidden;">
                    <img src="uploads/<?php echo $p['image']; ?>" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease;">
                </div>
                <p style="font-size: 11px; color: #999; text-transform: uppercase; margin-bottom: 5px;"><?php echo $p['Brand']; ?></p>
                <h3 style="font-family:serif; font-size: 18px; margin: 0;"><?php echo $p['Model']; ?></h3>
                <p style="font-weight: bold; margin-top: 10px;"><?php echo number_format($p['DailyRate'], 2); ?> TL <span style="font-weight: 300; font-size: 12px;">/ gün</span></p>
            </a>
        <?php endforeach; ?>
        
        <?php if(empty($products)): ?>
            <p style="grid-column: 1/-1; text-align: center; color: #999; padding: 50px;">Henüz ürün eklenmemiş. Panelden ekleyebilirsiniz.</p>
        <?php endif; ?>
    </div>
</main>

<?php include 'footer.php'; ?>
