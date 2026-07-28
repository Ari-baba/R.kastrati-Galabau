<?php
if (php_sapi_name() !== 'cli' && realpath(__FILE__) === realpath($_SERVER['SCRIPT_FILENAME'])) {
    http_response_code(403);
    exit('Forbidden');
}

require_once __DIR__ . '/../config/constants.php';

$pageTitle = 'Uploads | ' . APP_NAME;
require_once __DIR__ . '/includes/admin-header.php';
$base = rtrim(BASE_URL, '/') . ADMIN_PATH;
?>
<div class="admin-animate">
    <div class="content-card">
        <div class="content-card-header">
            <h2>Struktura e uploads</h2>
        </div>
        <div class="content-card-body">
            <div class="admin-form" style="max-width: 640px;">
                <div class="form-group">
                    <label>Rruga bazë</label>
                    <input type="text" value="<?php echo htmlspecialchars(UPLOAD_PATH); ?>" disabled>
                </div>
                <div class="form-group">
                    <label>Nën-direktoriumet</label>
                    <ul style="list-style: disc; padding-left: 20px; color: var(--text-muted);">
                        <li><code>about/</code> - Fotot për About Us</li>
                        <li><code>gallery/</code> - Fotot e galerisë</li>
                        <li><code>homepage/</code> - Fotot e homepage-së</li>
                    </ul>
                </div>
                <div class="form-group">
                    <label>Siguria</label>
                    <p style="color: var(--text-muted); font-size: 0.9rem;">Siguroni që serveri të mos ekzekutoje skripte nga ky folder. Konfiguroni serverin të negoc PHP execution në këtë direktori.</p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/includes/admin-footer.php'; ?>
