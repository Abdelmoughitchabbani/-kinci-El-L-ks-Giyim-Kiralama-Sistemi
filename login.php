<?php
require_once 'db.php';
session_start();

$error = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $sifre = $_POST['sifre'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE Email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($sifre, $user['Sifre'])) {
        $_SESSION['user_id'] = $user['UserID'];
        $_SESSION['user_name'] = $user['Ad'];
        $_SESSION['user_role'] = $user['Role'];
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "E-posta veya şifre hatalı.";
    }
}
include 'header.php';
?>

<div class="auth-container">
    <h1 class="auth-title">Giriş Yap</h1>
    
    <?php if(isset($_GET['success'])): ?>
        <p style="color: green; text-align: center; margin-bottom: 20px;">Hesabınız başarıyla oluşturuldu. Giriş yapabilirsiniz.</p>
    <?php endif; ?>

    <?php if($error): ?>
        <p style="color: red; text-align: center; margin-bottom: 20px;"><?php echo $error; ?></p>
    <?php endif; ?>

    <form method="POST">
        <div class="form-group">
            <label>E-posta</label>
            <input type="email" name="email" required>
        </div>
        <div class="form-group">
            <label>Şifre</label>
            <input type="password" name="sifre" required>
        </div>

        <button type="submit" class="btn-primary">Giriş Yap</button>
    </form>
    
    <p style="text-align: center; margin-top: 20px; font-size: 13px;">
        Hesabınız yok mu? <a href="register.php" style="text-decoration: underline;">Hemen Kaydolun</a>
    </p>
</div>

<?php include 'footer.php'; ?>
