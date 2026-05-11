<?php include 'header.php'; ?>

<main class="container" style="margin-top: 60px; margin-bottom: 100px;">
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 80px;">
        <!-- Contact Form -->
        <div>
            <h1 class="serif" style="font-size: 36px; margin-bottom: 20px;">İletişim</h1>
            <p style="color: #666; margin-bottom: 40px;">Sorularınız veya iş birliği talepleriniz için bize mesaj gönderin.</p>
            
            <form action="#" method="POST">
                <div class="form-group">
                    <label>Adınız</label>
                    <input type="text" name="name" required style="width: 100%; border-bottom: 1px solid #ddd; padding: 10px 0; outline: none;">
                </div>
                <div class="form-group">
                    <label>E-posta Adresiniz</label>
                    <input type="email" name="email" required style="width: 100%; border-bottom: 1px solid #ddd; padding: 10px 0; outline: none;">
                </div>
                <div class="form-group">
                    <label>Mesajınız</label>
                    <textarea name="message" rows="5" required style="width: 100%; border: 1px solid #eee; padding: 10px; margin-top: 10px; outline: none; resize: none;"></textarea>
                </div>
                <button type="submit" class="btn-primary" style="margin-top: 30px;">GÖNDER</button>
            </form>
        </div>

        <!-- Contact Details -->
        <div style="background: #f9f9f9; padding: 50px;">
            <h3 style="font-family: serif; font-size: 24px; margin-bottom: 30px;">Bize Ulaşın</h3>
            
            <div style="margin-bottom: 30px;">
                <p style="font-size: 11px; text-transform: uppercase; color: #999; margin-bottom: 5px;">E-posta</p>
                <p style="font-size: 16px;">hello@luxerent.com</p>
            </div>

            <div style="margin-bottom: 30px;">
                <p style="font-size: 11px; text-transform: uppercase; color: #999; margin-bottom: 5px;">Adres</p>
                <p style="font-size: 16px; line-height: 1.6;">Nişantaşı, Abdi İpekçi Cd. No:45<br>Şişli / İstanbul</p>
            </div>

            <div style="margin-bottom: 30px;">
                <p style="font-size: 11px; text-transform: uppercase; color: #999; margin-bottom: 5px;">Sosyal Medya</p>
                <div style="display: flex; gap: 15px; margin-top: 10px;">
                    <a href="#" style="font-size: 14px; text-decoration: underline;">Instagram</a>
                    <a href="#" style="font-size: 14px; text-decoration: underline;">Pinterest</a>
                    <a href="#" style="font-size: 14px; text-decoration: underline;">LinkedIn</a>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include 'footer.php'; ?>
