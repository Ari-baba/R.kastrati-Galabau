<?php
if (php_sapi_name() !== 'cli' && realpath(__FILE__) === realpath($_SERVER['SCRIPT_FILENAME'])) {
    http_response_code(403);
    exit('Forbidden');
}

require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../auth/csrf.php';

$pageTitle = $pageTitle ?? APP_NAME;
$metaDescription = $metaDescription ?? 'Admin panel - ' . APP_NAME;
$currentPath = $_SERVER['REQUEST_URI'] ?? '/';
$base = rtrim(BASE_URL, '/');
$adminBase = $base . ADMIN_PATH;
?>
<!doctype html>
<html lang="sq">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?php echo htmlspecialchars($metaDescription, ENT_QUOTES, 'UTF-8'); ?>">
    <title><?php echo htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8'); ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo htmlspecialchars($adminBase . '/assets/css/admin.css'); ?>">
</head>
<body>
    <div class="admin-mobile-backdrop" id="adminMobileBackdrop" aria-hidden="true"></div>
    <div class="admin-layout">
        <aside class="admin-sidebar" id="adminSidebar">
            <div class="admin-sidebar-header">
                <a href="<?php echo htmlspecialchars($base); ?>" class="admin-sidebar-brand">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/></svg>
                    <?php echo htmlspecialchars(APP_NAME); ?>
                </a>
            </div>
            <nav class="admin-sidebar-nav" aria-label="Admin navigation">
                <div class="admin-nav-section">
                    <div class="admin-nav-label">Menaxhment</div>
                    <a href="<?php echo htmlspecialchars($adminBase); ?>/dashboard.php" class="admin-nav-item<?php echo (strpos($currentPath, '/dashboard.php') !== false) ? ' active' : ''; ?>">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                        Dashboard
                    </a>
                    <a href="<?php echo htmlspecialchars($adminBase); ?>/reservations.php" class="admin-nav-item<?php echo (strpos($currentPath, '/reservations.php') !== false) ? ' active' : ''; ?>">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><rect x="8" y="2" width="8" height="4" rx="1" ry="1"/></svg>
                        Rezervimet
                    </a>
                    <a href="<?php echo htmlspecialchars($adminBase); ?>/gallery.php" class="admin-nav-item<?php echo (strpos($currentPath, '/gallery.php') !== false) ? ' active' : ''; ?>">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                        Galeria
                    </a>
                </div>
                <div class="admin-nav-section">
                    <div class="admin-nav-label">Përmbajtja</div>
                    <a href="<?php echo htmlspecialchars($adminBase); ?>/about.php" class="admin-nav-item<?php echo (strpos($currentPath, '/about.php') !== false) ? ' active' : ''; ?>">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                        About Us
                    </a>
                    <a href="<?php echo htmlspecialchars($adminBase); ?>/homepage.php" class="admin-nav-item<?php echo (strpos($currentPath, '/homepage.php') !== false) ? ' active' : ''; ?>">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                        Homepage
                    </a>
                </div>
            </nav>
            <div class="admin-sidebar-footer">
                <div class="admin-user">
                    <div class="admin-avatar">A</div>
                    <div class="admin-user-info">
                        <div class="admin-user-name">Admin</div>
                        <div class="admin-user-role">Administrator</div>
                    </div>
                </div>
                <form method="post" action="<?php echo htmlspecialchars($adminBase); ?>/logout.php" style="margin-top: 12px;">
                    <button type="submit" class="admin-logout">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                        Çkyçu
                    </button>
                </form>
            </div>
        </aside>
        <main class="admin-main">
            <header class="admin-topbar">
                <div class="admin-topbar-left">
                    <button class="admin-menu-toggle" id="adminMenuToggle" aria-label="Toggle sidebar" aria-expanded="false">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
                    </button>
                    <h1 class="admin-page-title"><?php echo htmlspecialchars($pageTitle); ?></h1>
                </div>
            </header>
            <div class="admin-content">
                <noscript>
                    <div class="alert alert-danger">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        Ky aplikacion kërkon JavaScript për të funksionuar mirë. Ju lutemi aktivizoni JavaScript.
                    </div>
                </noscript>
