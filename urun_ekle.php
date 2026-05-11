<?php
include 'header.php';

// Güvenlik: Sadece Ürün Sahibi girebilir
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'Ürün Sahibi') {
    header("Location: login.php");
    exit;
}

$success = "";
$error = "";

// --- PHP FORM İŞLEME (FUNCTIONAL PHP) ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['kaydet'])) {
    $brand = $_POST['marka'];
    $model = $_POST['model'];
    $rate  = $_POST['fiyat'];
    $size  = $_POST['beden'];
    $cat_id = $_POST['kategori'];
    $user_id = $_SESSION['user_id'];
    
    // Fotoğraf Yükleme Mantığı
    $image_name = "";
    if (isset($_FILES['urun_resmi']) && $_FILES['urun_resmi']['error'] == 0) {
        $upload_dir = 'uploads/';
        if (!is_dir($upload_dir)) { mkdir($upload_dir, 0777, true); }
        
        $ext = strtolower(pathinfo($_FILES['urun_resmi']['name'], PATHINFO_EXTENSION));
        $image_name = "luxury_" . time() . "_" . uniqid() . "." . $ext;
        $target_file = $upload_dir . $image_name;
        
        if (move_uploaded_file($_FILES['urun_resmi']['tmp_name'], $target_file)) {
            // Veritabanına Kayıt
            try {
                $stmt = $pdo->prepare("INSERT INTO products_new (Brand, Model, DailyRate, Size, CategoryID, OwnerID, image) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$brand, $model, $rate, $size, $cat_id, $user_id, $image_name]);
                $success = "Ürün başarıyla yüklendi!";
            } catch (PDOException $e) {
                $error = "Veritabanı Hatası: " . $e->getMessage() . " (Lütfen 'image' sütununu kontrol edin)";
            }
        } else {
            $error = "Dosya sunucuya taşınamadı.";
        }
    } else {
        $error = "Lütfen bir fotoğraf seçin.";
    }
}

$categories = $pdo->query("SELECT * FROM categories")->fetchAll();
?>

<main class="container" style="margin-top: 60px;">
    <div style="display: grid; grid-template-columns: 250px 1fr; gap: 60px;">
        <aside>
            <ul style="list-style: none; font-size: 14px; line-height: 3;">
                <li style="border-bottom: 1px solid #eee;"><a href="dashboard.php">Genel Bakış</a></li>
                <li style="border-bottom: 1px solid #eee;"><a href="urunlerim.php">Ürünlerim</a></li>
                <li style="border-bottom: 1px solid #eee;"><a href="urun_ekle.php" style="font-weight: 600;">Ürün Ekle</a></li>
                <li style="border-bottom: 1px solid #eee;"><a href="logout.php">Güvenli Çıkış</a></li>
            </ul>
        </aside>

        <section>
            <h1 class="serif" style="font-size: 32px; margin-bottom: 30px;">Yeni Ürün Ekle</h1>

            <?php if($success): ?>
                <div style="background: #e8f5e9; color: #2e7d32; padding: 15px; margin-bottom: 20px;"><?php echo $success; ?></div>
            <?php endif; ?>
            <?php if($error): ?>
                <div style="background: #ffebee; color: #c62828; padding: 15px; margin-bottom: 20px;"><?php echo $error; ?></div>
            <?php endif; ?>

            <form action="urun_ekle.php" method="POST" enctype="multipart/form-data" style="max-width: 500px;">
                <div class="form-group">
                    <label>Ürün Fotoğrafı</label>
                    <input type="file" name="urun_resmi" required style="border: none; padding: 10px 0;">
                </div>
                
                <div class="form-group">
                    <label>Marka</label>
                    <input type="text" name="marka" placeholder="Örn: Gucci" required>
                </div>

                <div class="form-group">
                    <label>Model</label>
                    <input type="text" name="model" placeholder="Örn: Marmont Çanta" required>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label>Beden</label>
                        <input type="text" name="beden" placeholder="Örn: 38" required>
                    </div>
                    <div class="form-group">
                        <label>Kategori</label>
                        <select name="kategori" required>
                            <?php foreach($categories as $c): ?>
                                <option value="<?php echo $c['CategoryID']; ?>"><?php echo htmlspecialchars($c['KategoriAdi']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label>Günlük Kiralama Ücreti (TL)</label>
                    <input type="number" name="fiyat" required>
                </div>

                <button type="submit" name="kaydet" class="btn-primary">ÜRÜNÜ YAYINLA</button>
            </form>
        </section>
    </div>
</main>

<?php include 'footer.php'; ?>
