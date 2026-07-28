<?php
require_once __DIR__ . '/../auth/check_auth.php';
require_once __DIR__ . '/../config/database.php';

$pageTitle = 'Detajet e Rezervimit | ' . APP_NAME;

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    header('Location: reservations.php');
    exit;
}

$r = db_query('SELECT id, first_name, last_name, phone, location, created_at FROM reservations WHERE id = :id LIMIT 1', ['id' => $id])->fetch();
if (!$r) {
    header('Location: reservations.php');
    exit;
}

require_once __DIR__ . '/includes/admin-header.php';
$base = rtrim(BASE_URL, '/') . ADMIN_PATH;
?>
<div class="admin-animate">
    <a href="<?php echo htmlspecialchars($base . '/reservations.php'); ?>" class="back-link">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
        Kthehu te lista e rezervimeve
    </a>

    <div class="content-card">
        <div class="content-card-header">
            <h2>Rezervimi #<?php echo (int)$r['id']; ?></h2>
            <span style="font-size: 0.85rem; color: var(--text-muted); font-weight: 500;"><?php echo htmlspecialchars($r['created_at']); ?></span>
        </div>
        <div class="content-card-body">
            <div class="admin-form" style="max-width: 640px;">
                <div class="form-group">
                    <label>Emri i plotë</label>
                    <input type="text" value="<?php echo htmlspecialchars($r['first_name'] . ' ' . $r['last_name']); ?>" disabled>
                </div>
                <div class="form-group">
                    <label>Telefon</label>
                    <input type="text" value="<?php echo htmlspecialchars($r['phone']); ?>" disabled>
                </div>
                <div class="form-group">
                    <label>Lokacioni</label>
                    <input type="text" value="<?php echo htmlspecialchars($r['location']); ?>" disabled>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/includes/admin-footer.php'; ?>
