<?php
require_once __DIR__ . '/../auth/check_auth.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../auth/csrf.php';

$pageTitle = 'Rezervimet | ' . APP_NAME;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['delete_id'])) {
    $token = $_POST['csrf_token'] ?? '';
    if (!validate_csrf_token($token)) {
        die('Invalid CSRF token');
    }
    $id = (int)$_POST['delete_id'];
    db_query('DELETE FROM reservations WHERE id = :id', ['id' => $id]);
    header('Location: reservations.php?deleted=1');
    exit;
}

$where = [];
$params = [];
$q = trim($_GET['q'] ?? '');
if ($q !== '') {
    $where[] = '(first_name LIKE :q OR last_name LIKE :q OR phone LIKE :q)';
    $params['q'] = '%' . $q . '%';
}
$date_from = $_GET['date_from'] ?? '';
$date_to = $_GET['date_to'] ?? '';
if ($date_from !== '') {
    $where[] = 'created_at >= :date_from';
    $params['date_from'] = $date_from . ' 00:00:00';
}
if ($date_to !== '') {
    $where[] = 'created_at <= :date_to';
    $params['date_to'] = $date_to . ' 23:59:59';
}

$sql = 'SELECT * FROM reservations' . (count($where) ? ' WHERE ' . implode(' AND ', $where) : '') . ' ORDER BY created_at DESC';
$stmt = db_query($sql, $params);
$reservations = $stmt->fetchAll();

require_once __DIR__ . '/includes/admin-header.php';
$base = rtrim(BASE_URL, '/') . ADMIN_PATH;
?>
<div class="admin-animate">
    <?php if (!empty($_GET['deleted'])): ?>
        <div class="alert alert-success">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            Rezervimi u fshi me sukses.
        </div>
    <?php endif; ?>

    <div class="content-card">
        <div class="content-card-header">
            <h2>Kërko dhe filtri</h2>
        </div>
        <div class="content-card-body">
            <form method="get" class="filter-bar">
                <div class="form-group">
                    <label for="q">Kërko</label>
                    <input type="text" id="q" name="q" value="<?php echo htmlspecialchars($q); ?>" placeholder="Emër ose telefon...">
                </div>
                <div class="form-group">
                    <label for="date_from">Nga data</label>
                    <input type="date" id="date_from" name="date_from" value="<?php echo htmlspecialchars($date_from); ?>">
                </div>
                <div class="form-group">
                    <label for="date_to">Deri data</label>
                    <input type="date" id="date_to" name="date_to" value="<?php echo htmlspecialchars($date_to); ?>">
                </div>
                <button type="submit" class="btn btn-primary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:16px;height:16px"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    Filtro
                </button>
            </form>
        </div>
    </div>

    <div class="content-card admin-animate admin-animate-delay-1">
        <div class="content-card-header">
            <h2>Të gjitha rezervimet (<?php echo count($reservations); ?>)</h2>
        </div>
        <div class="content-card-body no-padding">
            <div class="admin-table-wrapper">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Emri</th>
                            <th>Email</th>
                            <th>Telefon</th>
                            <th>Lokacioni</th>
                            <th>Data</th>
                            <th>Veprime</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($reservations)): ?>
                            <tr>
                                <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 40px;">Nuk u gjetën rezultate</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($reservations as $r): ?>
                                <tr>
                                    <td>#<?php echo (int)$r['id']; ?></td>
                                    <td><?php echo htmlspecialchars($r['first_name'] . ' ' . $r['last_name']); ?></td>
                                    <td><?php echo htmlspecialchars($r['email'] ?? '-'); ?></td>
                                    <td><?php echo htmlspecialchars($r['phone']); ?></td>
                                    <td style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="<?php echo htmlspecialchars($r['location']); ?>"><?php echo htmlspecialchars($r['location']); ?></td>
                                    <td><?php echo htmlspecialchars($r['created_at']); ?></td>
                                    <td>
                                        <div class="actions">
                                            <a href="reservation_details.php?id=<?php echo (int)$r['id']; ?>" class="btn btn-secondary btn-sm">Shiko</a>
                                            <form method="post" onsubmit="return confirm('A jeni i sigurt që dëshironi të fshini këtë rezervim?');" style="display:inline;">
                                                <?php echo csrf_input_field(); ?>
                                                <input type="hidden" name="delete_id" value="<?php echo (int)$r['id']; ?>">
                                                <button type="submit" class="btn btn-danger btn-sm">Fshi</button>
                                            </form>
                                        </div>
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
