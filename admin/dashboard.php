<?php
require_once __DIR__ . '/../auth/check_auth.php';
require_once __DIR__ . '/../config/database.php';

$pageTitle = 'Dashboard | ' . APP_NAME;

$totalResStmt = db_query('SELECT COUNT(*) AS c FROM reservations');
$totalReservations = (int)$totalResStmt->fetchColumn();

$recentStmt = db_query('SELECT id, first_name, last_name, phone, location, created_at FROM reservations ORDER BY created_at DESC LIMIT 5');
$recentReservations = $recentStmt->fetchAll();

$totalGallery = (int)db_query('SELECT COUNT(*) FROM gallery')->fetchColumn();

require_once __DIR__ . '/includes/admin-header.php';
?>
<div class="admin-animate">
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon green">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><rect x="8" y="2" width="8" height="4" rx="1" ry="1"/></svg>
            </div>
            <div class="stat-info">
                <h3><?php echo $totalReservations; ?></h3>
                <p>Rezervime totale</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon blue">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
            </div>
            <div class="stat-info">
                <h3><?php echo $totalGallery; ?></h3>
                <p>Foto galeri</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon purple">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
            </div>
            <div class="stat-info">
                <h3>5</h3>
                <p>Faqe aktive</p>
            </div>
        </div>
    </div>

    <div class="content-card admin-animate admin-animate-delay-1">
        <div class="content-card-header">
            <h2>Rezervimet e fundit</h2>
            <?php if ($totalReservations > 0): ?>
                <span style="font-size: 0.85rem; color: var(--text-muted); font-weight: 500;"><?php echo $totalReservations; ?> rezervime në total</span>
            <?php endif; ?>
        </div>
        <div class="content-card-body no-padding">
            <div class="admin-table-wrapper">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Emri i plotë</th>
                            <th>Telefon</th>
                            <th>Lokacioni</th>
                            <th>Data</th>
                            <th>Veprime</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($recentReservations)): ?>
                            <tr>
                                <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 48px 20px;">
                                    <div style="display: flex; flex-direction: column; align-items: center; gap: 12px;">
                                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="opacity: 0.4;"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><rect x="8" y="2" width="8" height="4" rx="1" ry="1"/></svg>
                                        <span>Nuk ka ende rezervime. Rezervimet do të shfaqen këtu automatikisht.</span>
                                        <a href="<?php echo htmlspecialchars($base . ADMIN_PATH . '/reservations.php'); ?>" class="btn btn-secondary btn-sm" style="margin-top: 8px;">Shiko listen e plotë të rezervimeve</a>
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($recentReservations as $r): ?>
                                <tr>
                                    <td>#<?php echo (int)$r['id']; ?></td>
                                    <td>
                                        <?php echo htmlspecialchars($r['first_name'] . ' ' . $r['last_name']); ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($r['phone']); ?></td>
                                    <td style="max-width: 220px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="<?php echo htmlspecialchars($r['location']); ?>"><?php echo htmlspecialchars($r['location']); ?></td>
                                    <td><?php echo htmlspecialchars($r['created_at']); ?></td>
                                    <td>
                                        <div class="actions">
                                            <a href="reservation_details.php?id=<?php echo (int)$r['id']; ?>" class="btn btn-secondary btn-sm">Shiko</a>
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

    <div style="display: flex; gap: 12px; margin-top: 24px; flex-wrap: wrap;" class="admin-animate admin-animate-delay-2">
        <a href="<?php echo htmlspecialchars($base . ADMIN_PATH . '/reservations.php'); ?>" class="btn btn-primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:16px;height:16px"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><rect x="8" y="2" width="8" height="4" rx="1" ry="1"/></svg>
            Menaxho Rezervimet
            <?php if ($totalReservations > 0): ?>
                <span style="background: rgba(255,255,255,0.2); padding: 2px 8px; border-radius: 9999px; font-size: 0.75rem;"><?php echo $totalReservations; ?></span>
            <?php endif; ?>
        </a>
        <a href="<?php echo htmlspecialchars($base . ADMIN_PATH . '/gallery.php'); ?>" class="btn btn-secondary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:16px;height:16px"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
            Menaxho Galerinë
        </a>
        <a href="<?php echo htmlspecialchars($base . ADMIN_PATH . '/about.php'); ?>" class="btn btn-secondary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:16px;height:16px"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
            Edito About Us
        </a>
        <a href="<?php echo htmlspecialchars($base . ADMIN_PATH . '/homepage.php'); ?>" class="btn btn-secondary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:16px;height:16px"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
            Edito Homepage
        </a>
    </div>
</div>
<?php require_once __DIR__ . '/includes/admin-footer.php'; ?>
