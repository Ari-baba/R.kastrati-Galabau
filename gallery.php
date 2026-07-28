<?php
require_once __DIR__ . '/config/constants.php';
require_once __DIR__ . '/config/database.php';

$pageTitle = 'Galeria | ' . APP_NAME;
$galleryItems = db_query('SELECT * FROM gallery ORDER BY uploaded_at DESC')->fetchAll();

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>
<main>
    <section class="section">
        <div class="container">
            <div class="section-title animate-fade-in">
                <span class="eyebrow">Galeria</span>
                <h1>Fotografi të punimeve tona</h1>
                <p>Shih punimet tona të fundit dhe frymëzohuni për oborrin tuaj të ardhshëm.</p>
            </div>
            <div class="gallery-grid">
                <?php if (empty($galleryItems)): ?>
                    <div class="gallery-card animate-fade-in animate-delay-1"><span>Nuk ka foto të ngarkuara ende</span></div>
                <?php else: ?>
                    <?php foreach ($galleryItems as $index => $item): ?>
                        <div class="gallery-card animate-fade-in animate-delay-<?php echo ($index % 3) + 1; ?>">
                            <?php if (!empty($item['image'])): ?>
                                <img src="<?php echo htmlspecialchars(rtrim(BASE_URL, '/') . '/uploads/' . $item['image']); ?>" alt="Foto e galerisë" style="width:100%; height:240px; object-fit:cover; border-radius:16px;">
                            <?php else: ?>
                                <span>Foto e punimit</span>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>
</main>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
