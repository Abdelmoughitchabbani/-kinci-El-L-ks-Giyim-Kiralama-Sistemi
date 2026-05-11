<?php
include 'header.php';

// Güvenlik: Sadece Ürün Sahibi girebilir
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'Ürün Sahibi') {
    header("Location: login.php");
    exit;
}

$success_msg = "";
$error_msg = "";

// --- PHP Upload & Kayıt Scripti ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_product'])) {
    $brand = $_POST['brand'];
    $model = $_POST['model'];
    $rate  = $_POST['rate'];
    $size  = $_POST['size'];
    $cat_id = $_POST['category_id'];
    $user_id = $_SESSION['user_id'];
    
    // Dosya Hazırlığı
    $image_name = "";
    if (isset($_FILES['urun_resmi']) && $_FILES['urun_resmi']['error'] == 0) {
        $allowed_ext = ['jpg', 'jpeg', 'png', 'webp'];
        $file_name = $_FILES['urun_resmi']['name'];
        $file_tmp  = $_FILES['urun_resmi']['tmp_name'];
        $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        
        if (in_array($ext, $allowed_ext)) {
            // Benzersiz isim oluştur (Timestamp + Random ID)
            $image_name = "luxury_" . time() . "_" . uniqid() . "." . $ext;
            
            // Upload klasörünü kontrol et
            if (!is_dir('uploads')) {
                mkdir('uploads', 0777, true);
            }
            
            // Dosyayı taşı
            if (move_uploaded_file($file_tmp, 'uploads/' . $image_name)) {
                // Veritabanına kaydet
                try {
                    $stmt = $pdo->prepare("INSERT INTO products_new (Brand, Model, DailyRate, Size, CategoryID, OwnerID, image) VALUES (?, ?, ?, ?, ?, ?, ?)");
                    $stmt->execute([$brand, $model, $rate, $size, $cat_id, $user_id, $image_name]);
                    $success_msg = "Ürününüz başarıyla yüklendi! Kataloğunuzda görebilirsiniz.";
                } catch (PDOException $e) {
                    $error_msg = "Veritabanı Hatası: " . $e->getMessage();
                }
            } else {
                $error_msg = "Fotoğraf yüklenirken teknik bir hata oluştu.";
            }
        } else {
            $error_msg = "Sadece JPG, PNG veya WEBP formatındaki resimleri yükleyebilirsiniz.";
        }
    } else {
        $error_msg = "Lütfen ürünün bir fotoğrafını yükleyin.";
    }
}

// Kategorileri çek
$categories = $pdo->query("SELECT * FROM categories ORDER BY KategoriAdi ASC")->fetchAll();
?>

<main class="container" style="margin-top: 60px;">
    <div style="display: grid; grid-template-columns: 250px 1fr; gap: 60px;">
        <!-- Sidebar -->
        <aside>
            <ul style="list-style: none; font-size: 14px; line-height: 3;">
                <li style="border-bottom: 1px solid #eee;"><a href="dashboard.php">Genel Bakış</a></li>
                <li style="border-bottom: 1px solid #eee;"><a href="my_products.php">Ürünlerim</a></li>
                <li style="border-bottom: 1px solid #eee;"><a href="add_product.php" style="font-weight: 600; color: #000;">● Yeni Ürün Ekle</a></li>
                <li style="border-bottom: 1px solid #eee;"><a href="logout.php">Güvenli Çıkış</a></li>
            </ul>
        </aside>

        <!-- Form Section -->
        <section>
            <h1 class="serif" style="font-size: 32px; margin-bottom: 10px;">Lüks Ürün Ekle</h1>
            <p style="color: #999; margin-bottom: 40px;">Ürününüzün tüm detaylarını girin ve kaliteli bir görsel yükleyin.</p>

            <?php if($success_msg): ?>
                <div style="background: #e8f5e9; color: #2e7d32; padding: 20px; margin-bottom: 30px; border-left: 5px solid #2e7d32;">
                    <?php echo $success_msg; ?>
                </div>
            <?php endif; ?>
            
            <?php if($error_msg): ?>
                <div style="background: #ffebee; color: #c62828; padding: 20px; margin-bottom: 30px; border-left: 5px solid #c62828;">
                    <?php echo $error_msg; ?>
                </div>
            <?php endif; ?>

            <form action="add_product.php" method="POST" enctype="multipart/form-data" style="max-width: 600px;">
                <div class="form-group" style="margin-bottom: 30px; border: 2px dashed #eee; padding: 30px; text-align: center;">
                    <label style="display: block; cursor: pointer;">
                        <span style="font-size: 13px; color: #666; display: block; margin-bottom: 10px;">ÜRÜN FOTOĞRAFI SEÇİN</span>
                        <input type="file" name="urun_resmi" required style="font-size: 12px;">
                    </label>
                </div>
                
                <div class="form-group">
                    <label>MARKA</label>
                    <input type="text" name="brand" placeholder="Örn: Gucci, Chanel, Prada" required>
                </div>

                <div class="form-group">
                    <label>MODEL / ÜRÜN ADI</label>
                    <input type="text" name="model" placeholder="Örn: Marmont Shoulder Bag" required>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label>BEDEN</label>
                        <input type="text" name="size" placeholder="Örn: 38, M, Standart" required>
                    </div>
                    <div class="form-group">
                        <label>KATEGORİ</label>
                        <select name="category_id" required>
                            <option value="">Seçiniz...</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo $cat['CategoryID']; ?>"><?php echo htmlspecialchars($cat['KategoriAdi']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label>GÜNLÜK KİRALAMA BEDELİ (TL)</label>
                    <input type="number" name="rate" step="0.01" placeholder="0.00" required>
                </div>

                <button type="submit" name="submit_product" class="btn-primary" style="margin-top: 20px;">ÜRÜNÜ YAYINLA</button>
            </form>
        </section>
    </div>
</main>

<?php include 'footer.php'; ?>
