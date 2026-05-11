<?php
require_once 'db.php';

$error = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ad = $_POST['ad'];
    $soyad = $_POST['soyad'];
    $email = $_POST['email'];
    $sifre = password_hash($_POST['sifre'], PASSWORD_DEFAULT);
    $role = $_POST['role']; // Kiralayan or Ürün Sahibi

    // Check if email exists
    $check = $pdo->prepare("SELECT UserID FROM users WHERE Email = ?");
    $check->execute([$email]);
    
    if ($check->rowCount() > 0) {
        $error = "Bu e-posta adresi zaten kayıtlı.";
    } else {
        // Insert user (Assuming Role column exists)
        try {
            $stmt = $pdo->prepare("INSERT INTO users (Ad, Soyad, Email, Sifre, Role) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$ad, $soyad, $email, $sifre, $role]);
            header("Location: login.php?success=1");
            exit;
        } catch (PDOException $e) {
            $error = "Kayıt sırasında bir hata oluştu. (Lütfen Role sütununun veritabanında olduğundan emin olun)";
        }
    }
}
include 'header.php';
?>

<div class="auth-container">
    <h1 class="auth-title">Hesap Oluştur</h1>
    
    <?php if($error): ?>
        <p style="color: red; text-align: center; margin-bottom: 20px;"><?php echo $error; ?></p>
    <?php endif; ?>

    <form method="POST">
        <div class="form-group">
            <label>Adınız</label>
            <input type="text" name="ad" required>
        </div>
        <div class="form-group">
            <label>Soyadınız</label>
            <input type="text" name="soyad" required>
        </div>
        <div class="form-group">
            <label>E-posta</label>
            <input type="email" name="email" required>
        </div>
        <div class="form-group">
            <label>Şifre</label>
            <input type="password" name="sifre" required>
        </div>

        <div class="form-group">
            <label>Hesap Türü</label>
            <select name="role" required>
                <option value="Kiralayan">Kiralayan (Ürün Kiralamak İstiyorum)</option>
                <option value="Ürün Sahibi">Ürün Sahibi (Ürünlerimi Kiraya Vermek İstiyorum)</option>
            </select>
        </div>

        <button type="submit" class="btn-primary">Kayıt Ol</button>
    </form>
    
    <p style="text-align: center; margin-top: 20px; font-size: 13px;">
        Zaten hesabınız var mı? <a href="login.php" style="text-decoration: underline;">Giriş Yap</a>
    </p>
</div>

<?php include 'footer.php'; ?>
