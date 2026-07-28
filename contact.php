<?php
require_once __DIR__ . '/config/constants.php';
$pageTitle = 'Kontakt | ' . APP_NAME;
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';

$messageSent = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');
    if ($name !== '' && $email !== '' && $message !== '') {
        $messageSent = true;
    }
}
?>
<main>
    <section class="section contact-section">
        <div class="container contact-grid">
            <div class="animate-fade-in">
                <span class="eyebrow">Kontakt</span>
                <h1>Na shkruaj për një ofrues të oborrit</h1>
                <p>Jemi të gatshëm të diskutojmë projektin tuaj dhe të ofrojmë një zgjidhje të personalizuar për rregullimin e oborrit.</p>
                <div class="contact-card">
                    <p><strong>Telefon:</strong> +355 4 123 4567</p>
                    <p><strong>Email:</strong> info@example.com</p>
                    <p><strong>Adresa:</strong> Tirane, Shqipëri</p>
                </div>
            </div>
            <div class="form-card animate-fade-in animate-delay-2">
                <?php if ($messageSent): ?>
                    <div class="form-message success">Faleminderit! Mesazhi juaj u dërgua me sukses.</div>
                <?php endif; ?>
                <form action="contact.php" method="post">
                    <div class="form-field">
                        <label for="name">Emri</label>
                        <input type="text" id="name" name="name" required value="<?php echo htmlspecialchars($_POST['name'] ?? '', ENT_QUOTES); ?>">
                    </div>
                    <div class="form-field">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required value="<?php echo htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES); ?>">
                    </div>
                    <div class="form-field">
                        <label for="message">Mesazhi</label>
                        <textarea id="message" name="message" required><?php echo htmlspecialchars($_POST['message'] ?? '', ENT_QUOTES); ?></textarea>
                    </div>
                    <button type="submit">Dërgo mesazhin</button>
                </form>
            </div>
        </div>
    </section>
</main>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
