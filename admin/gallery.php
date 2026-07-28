<?php
require_once __DIR__ . '/../auth/check_auth.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../auth/csrf.php';

$pageTitle = 'Galeria | ' . APP_NAME;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_FILES['image']['name'])) {
    $token = $_POST['csrf_token'] ?? '';
    if (!validate_csrf_token($token)) { die('Invalid CSRF'); }
    $allowed = unserialize(ALLOWED_IMAGE_TYPES);
    $max = MAX_UPLOAD_SIZE;
    $uploaddir = GALLERY_PATH;
    if (!is_dir($uploaddir)) mkdir($uploaddir, 0755, true);
    $f = $_FILES['image'];
    if ($f['size'] > $max) { die('File too large'); }

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

    $allowedMimes = array_map('strtolower', $allowed);
    $normalizedMime = strtolower((string)$mime);
    $allowedByMime = in_array($normalizedMime, $allowedMimes, true);

    $ext = strtolower(pathinfo($f['name'], PATHINFO_EXTENSION));
    $allowedByExtension = in_array($ext, ['jpg', 'jpeg', 'png', 'webp'], true);

    if (!$allowedByMime && !$allowedByExtension) {
        die('Invalid file type');
    }
    if ($ext === '') {
        $ext = 'jpg';
    }
    $newname = 'gallery_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
    $dest = $uploaddir . '/' . $newname;

    if (!empty($f['tmp_name']) && is_uploaded_file($f['tmp_name']) && move_uploaded_file($f['tmp_name'], $dest)) {
        $imagePath = 'gallery/' . $newname;
    } else {
        $tmpPath = $f['tmp_name'] ?? '';
        if (!empty($tmpPath) && file_exists($tmpPath) && copy($tmpPath, $dest)) {
            @unlink($tmpPath);
            $imagePath = 'gallery/' . $newname;
        } else {
            error_log('Gallery upload failed. Temp file: ' . ($tmpPath ?: 'none') . ' Destination: ' . $dest . ' Error: ' . ($f['error'] ?? 'unknown'));
            die('Upload failed');
        }
    }
    db_query('INSERT INTO gallery (image) VALUES (:i)', ['i'=>$imagePath]);
    header('Location: gallery.php?uploaded=1');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['delete_id'])) {
    $token = $_POST['csrf_token'] ?? '';
    if (!validate_csrf_token($token)) { die('Invalid CSRF'); }
    $id = (int)$_POST['delete_id'];
    $row = db_query('SELECT image FROM gallery WHERE id = :id LIMIT 1', ['id'=>$id])->fetch();
    if ($row) {
        $filepath = rtrim(UPLOAD_PATH, '/') . '/' . $row['image'];
        if (file_exists($filepath)) unlink($filepath);
        db_query('DELETE FROM gallery WHERE id = :id', ['id'=>$id]);
    }
    header('Location: gallery.php?deleted=1');
    exit;
}

$images = db_query('SELECT * FROM gallery ORDER BY uploaded_at DESC')->fetchAll();

require_once __DIR__ . '/includes/admin-header.php';
$base = rtrim(BASE_URL, '/');
?>
<div class="admin-animate">
    <?php if (!empty($_GET['uploaded'])): ?>
        <div class="alert alert-success">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            Fotoja u ngarkua me sukses.
        </div>
    <?php endif; ?>
    <?php if (!empty($_GET['deleted'])): ?>
        <div class="alert alert-success">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            Fotoja u fshi me sukses.
        </div>
    <?php endif; ?>

    <div class="content-card">
        <div class="content-card-header">
            <h2>Ngarko foto të re</h2>
        </div>
        <div class="content-card-body">
            <form method="post" enctype="multipart/form-data" class="admin-form" style="max-width: 600px;">
                <?php echo csrf_input_field(); ?>
                <div class="form-group">
                    <label for="image">Zgjidh foto</label>
                    <input type="file" id="image" name="image" accept="image/*" required>
                </div>
                <div class="form-actions" style="border-top: none; padding-top: 0;">
                    <button type="submit" class="btn btn-primary">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:16px;height:16px"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                        Ngarko
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="content-card admin-animate admin-animate-delay-1">
        <div class="content-card-header">
            <h2>Fotot e fundit</h2>
        </div>
        <div class="content-card-body no-padding">
            <div class="admin-table-wrapper">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Foto</th>
                            <th>Ngarkuar</th>
                            <th>Veprime</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($images)): ?>
                            <tr>
                                <td colspan="4" style="text-align: center; color: var(--text-muted); padding: 40px;">Nuk ka foto në galeri</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($images as $img): ?>
                                <tr>
                                    <td>#<?php echo (int)$img['id']; ?></td>
                                    <td>
                                        <img src="<?php echo htmlspecialchars(rtrim(BASE_URL, '/') . '/uploads/' . $img['image']); ?>" alt="" style="width: 80px; height: 60px; object-fit: cover; border-radius: 8px; border: 1px solid var(--border);">
                                    </td>
                                    <td><?php echo htmlspecialchars($img['uploaded_at']); ?></td>
                                    <td>
                                        <form method="post" onsubmit="return confirm('A jeni i sigurt që dëshironi të fshini këtë foto?');" style="display:inline;">
                                            <?php echo csrf_input_field(); ?>
                                            <input type="hidden" name="delete_id" value="<?php echo (int)$img['id']; ?>">
                                            <button type="submit" class="btn btn-danger btn-sm">Fshi</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/includes/admin-footer.php'; ?>
