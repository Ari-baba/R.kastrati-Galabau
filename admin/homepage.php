<?php
require_once __DIR__ . '/../auth/check_auth.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../auth/csrf.php';

$pageTitle = 'Edito Homepage | ' . APP_NAME;

$stmt = db_query('SELECT * FROM homepage ORDER BY id LIMIT 1');
$home = $stmt->fetch();

$uploadError = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? '';
    if (!validate_csrf_token($token)) { die('Invalid CSRF'); }
    $title = trim($_POST['hero_title'] ?? '');
    $desc = trim($_POST['hero_description'] ?? '');

    if ($title === '' || $desc === '') {
        $uploadError = 'Të gjitha fushat janë të detyrueshme.';
    } else {
        $imagePath = $home['hero_image'] ?? null;

        if (!empty($_FILES['hero_image']['name']) && $_FILES['hero_image']['error'] === UPLOAD_ERR_OK) {
            $allowed = unserialize(ALLOWED_IMAGE_TYPES);
            $max = MAX_UPLOAD_SIZE;
            $uploaddir = HOMEPAGE_PATH;
            if (!is_dir($uploaddir)) mkdir($uploaddir, 0755, true);
            $f = $_FILES['hero_image'];
            if ($f['size'] > $max) {
                $uploadError = 'Fotoja është shumë e madhe. Maksimumi është ' . ($max / 1024 / 1024) . 'MB.';
            } else {
                $mime = null;
                if (function_exists('finfo_open') && function_exists('finfo_file')) {
                    $finfo = finfo_open(FILEINFO_MIME_TYPE);
                    if ($finfo !== false) {
                        $mime = finfo_file($finfo, $f['tmp_name']);
                        finfo_close($finfo);
                    }
                }

                if ($mime === null) {
                    $mime = $f['type'] ?? '';
                }

                if (!in_array($mime, $allowed, true)) {
                    $uploadError = 'Formati i file-it nuk është i lejuar. Lejohet: JPG, PNG, WEBP.';
                } else {
                    $ext = strtolower(pathinfo($f['name'], PATHINFO_EXTENSION));
                    if ($ext === '') {
                        $ext = 'jpg';
                    }
                    $newname = 'hero_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
                    $dest = $uploaddir . '/' . $newname;
                    if (move_uploaded_file($f['tmp_name'], $dest)) {
                        $imagePath = 'homepage/' . $newname;
                    } else {
                        $uploadError = 'Dështoi ngarkimi i fotos.';
                    }
                }
            }
        }

        if ($uploadError === '') {
            if ($home) {
                db_query('UPDATE homepage SET hero_title = :t, hero_description = :d, hero_image = :i WHERE id = :id', ['t'=>$title,'d'=>$desc,'i'=>$imagePath,'id'=>$home['id']]);
            } else {
                db_query('INSERT INTO homepage (hero_title, hero_description, hero_image) VALUES (:t,:d,:i)', ['t'=>$title,'d'=>$desc,'i'=>$imagePath]);
            }
            header('Location: homepage.php?updated=1');
            exit;
        }
    }
}

require_once __DIR__ . '/includes/admin-header.php';
$base = rtrim(BASE_URL, '/');
?>
<div class="admin-animate">
    <?php if (!empty($_GET['updated'])): ?>
        <div class="alert alert-success">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            Ndryshimet u ruajtën me sukses.
        </div>
    <?php endif; ?>
    <?php if ($uploadError !== ''): ?>
        <div class="alert alert-danger">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <?php echo htmlspecialchars($uploadError); ?>
        </div>
    <?php endif; ?>

    <div class="content-card">
        <div class="content-card-header">
            <h2>Edito përmbajtjen e Homepage-së</h2>
        </div>
        <div class="content-card-body">
            <form method="post" enctype="multipart/form-data" class="admin-form">
                <?php echo csrf_input_field(); ?>
                <div class="form-group">
                    <label for="hero_title">Hero Titulli</label>
                    <input type="text" id="hero_title" name="hero_title" value="<?php echo htmlspecialchars($home['hero_title'] ?? ''); ?>" required>
                </div>
                <div class="form-group">
                    <label for="hero_description">Hero Përshkrimi</label>
                    <textarea id="hero_description" name="hero_description" required><?php echo htmlspecialchars($home['hero_description'] ?? ''); ?></textarea>
                </div>
                <div class="form-group">
                    <label for="hero_image">Foto Hero (opsionale)</label>
                    <input type="file" id="hero_image" name="hero_image" accept="image/*">
                    <?php if (!empty($home['hero_image'])): ?>
                        <div class="image-preview">
                            <img src="<?php echo htmlspecialchars(rtrim(BASE_URL, '/') . '/uploads/' . $home['hero_image']); ?>" alt="Homepage current">
                        </div>
                    <?php endif; ?>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Ruaj ndryshimet</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/includes/admin-footer.php'; ?>
