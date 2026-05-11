<?php
include 'header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$msg = "";

// Ürün Yükleme
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['ekle'])) {
    $brand = $_POST['brand'];
    $model = $_POST['model'];
    $price = $_POST['price'];
    $size = $_POST['size'];
    
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $img = "lux_" . time() . "_" . uniqid() . "." . pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        if (!is_dir('uploads')) { mkdir('uploads', 0777, true); }
        move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/' . $img);
        
        $stmt = $pdo->prepare("INSERT INTO products_lux (Brand, Model, DailyRate, Size, OwnerID, image) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$brand, $model, $price, $size, $user_id, $img]);
        $msg = "<p style='color:green;'>Ürün başarıyla yayına alındı!</p>";
    }
}

$my_items = $pdo->prepare("SELECT * FROM products_lux WHERE OwnerID = ?");
$my_items->execute([$user_id]);
$items = $my_items->fetchAll();
?>

<main class="container" style="margin-top: 40px;">
    <h1 class="serif">Kontrol Paneli</h1>
    <?php echo $msg; ?>

    <div style="display: grid; grid-template-columns: 1fr 1.5fr; gap: 40px; margin-top: 30px;">
        <!-- Form -->
        <div style="background: #f9f9f9; padding: 30px; border: 1px solid #eee;">
            <h2 class="serif">Yeni Ürün Ekle</h2>
            <form action="dashboard.php" method="POST" enctype="multipart/form-data">
                <div style="margin-bottom:15px;"><label style="display:block; font-size:12px;">RESİM</label><input type="file" name="image" required></div>
                <div style="margin-bottom:15px;"><label style="display:block; font-size:12px;">MARKA</label><input type="text" name="brand" required style="width:100%; padding:10px;"></div>
                <div style="margin-bottom:15px;"><label style="display:block; font-size:12px;">MODEL</label><input type="text" name="model" required style="width:100%; padding:10px;"></div>
                <div style="margin-bottom:15px;"><label style="display:block; font-size:12px;">GÜNLÜK ÜCRET</label><input type="number" name="price" required style="width:100%; padding:10px;"></div>
                <div style="margin-bottom:15px;"><label style="display:block; font-size:12px;">BEDEN</label><input type="text" name="size" required style="width:100%; padding:10px;"></div>
                <button type="submit" name="ekle" style="background:#000; color:#fff; padding:15px; width:100%; border:none; cursor:pointer; text-transform:uppercase;">Ürünü Kaydet</button>
            </form>
        </div>

        <!-- Liste -->
        <div>
            <h2 class="serif">Ürünlerim</h2>
            <?php foreach($items as $i): ?>
                <div style="display:flex; gap:15px; align-items:center; border-bottom:1px solid #eee; padding:15px 0;">
                    <img src="uploads/<?php echo $i['image']; ?>" width="60" height="80" style="object-fit:cover;">
                    <div>
                        <strong><?php echo $i['Brand']; ?></strong><br>
                        <span style="font-size:13px; color:#666;"><?php echo $i['Model']; ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</main>
<?php include 'footer.php'; ?>
