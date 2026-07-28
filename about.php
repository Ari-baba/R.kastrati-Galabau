<?php
require_once __DIR__ . '/config/constants.php';
require_once __DIR__ . '/config/database.php';

$pageTitle = 'About Us | ' . APP_NAME;
$about = db_query('SELECT * FROM about ORDER BY id LIMIT 1')->fetch();
$aboutTitle = $about['title'] ?? 'Ne krijojmë oborre të gjelbërta dhe të qëndrueshme';
$aboutDescription = $about['description'] ?? 'Me përvojë në rregullimin e oborreve dhe dizajnin natyral, ne krijojmë hapësira që reflektojnë qetësinë e natyrës.';
$aboutImage = !empty($about['image']) ? rtrim(BASE_URL, '/') . '/uploads/' . $about['image'] : '';

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>
<main>
    <section class="section about-section">
        <div class="container about-grid">
            <div class="about-text animate-fade-in">
                <span class="eyebrow">About Us</span>
                <h1><?php echo htmlspecialchars($aboutTitle); ?></h1>
                <p><?php echo nl2br(htmlspecialchars($aboutDescription)); ?></p>
                <ul class="feature-list">
                    <li>Planifikim i detajuar i oborrit</li>
                    <li>Përdorim i bimëve dhe pemëve natyrale</li>
                    <li>Mirëmbajtje dhe pastrim i oborreve</li>
                </ul>
            </div>
            <div class="about-card about-image animate-fade-in animate-delay-2">
                <?php if ($aboutImage !== ''): ?>
                    <img src="<?php echo htmlspecialchars($aboutImage); ?>" alt="<?php echo htmlspecialchars($aboutTitle); ?>" style="width:100%; height:100%; object-fit:cover; border-radius:16px;">
                <?php else: ?>
                    <span>Foto About</span>
                <?php endif; ?>
            </div>
        </div>
    </section>
</main>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
