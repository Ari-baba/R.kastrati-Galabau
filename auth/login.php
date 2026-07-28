<?php
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/session.php';
require_once __DIR__ . '/csrf.php';

if (is_logged_in()) {
    header('Location: ' . rtrim(BASE_URL, '/') . ADMIN_PATH . '/dashboard.php');
    exit;
}

$pageTitle = 'Login | ' . APP_NAME;
$error = $_GET['error'] ?? null;
$errorMessage = '';
if ($error === 'timeout') {
    $errorMessage = 'Sesioni juaj është skaduar. Ju lutemi identifikohu përsëri.';
} elseif ($error) {
    $errorMessage = htmlspecialchars($error, ENT_QUOTES, 'UTF-8');
}
?>
<!doctype html>
<html lang="sq">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8'); ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo htmlspecialchars(rtrim(BASE_URL, '/') . '/admin/assets/css/admin.css'); ?>">
    <style>
        .auth-layout {
            min-height: 100vh;
            display: grid;
            grid-template-columns: 1fr 1fr;
            background: linear-gradient(135deg, #f8faf9 0%, #e8f5e9 100%);
        }
        .auth-visual {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            position: relative;
            overflow: hidden;
        }
        .auth-visual::before {
            content: '';
            position: absolute;
            top: -30%;
            right: -20%;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(76,175,80,0.1) 0%, transparent 60%);
            border-radius: 50%;
        }
        .auth-visual-content {
            position: relative;
            z-index: 1;
            text-align: center;
            max-width: 480px;
        }
        .auth-visual-logo {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 24px;
            display: inline-flex;
            align-items: center;
            gap: 12px;
        }
        .auth-visual-text {
            font-size: 1.1rem;
            color: var(--text-muted);
            line-height: 1.7;
        }
        .auth-form-side {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            background: var(--surface);
            border-left: 1px solid var(--border-light);
        }
        .auth-card {
            width: 100%;
            max-width: 420px;
            background: var(--surface);
        }
        .auth-header {
            margin-bottom: 32px;
        }
        .auth-header h1 {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 8px;
            color: var(--text);
        }
        .auth-header p {
            color: var(--text-muted);
            font-size: 0.95rem;
        }
        .auth-form {
            display: grid;
            gap: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            font-size: 0.9rem;
            color: var(--text);
        }
        .form-group input {
            width: 100%;
            padding: 14px 16px;
            border: 1.5px solid var(--border);
            border-radius: 10px;
            background: var(--background);
            font-family: inherit;
            font-size: 1rem;
            color: var(--text);
            transition: all 0.2s ease;
            outline: none;
        }
        .form-group input:focus {
            border-color: var(--primary-lighter);
            background: var(--surface);
            box-shadow: 0 0 0 4px rgba(76, 175, 80, 0.08);
        }
        .btn-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 14px 24px;
            background: var(--primary);
            color: #fff;
            font-weight: 600;
            font-size: 0.95rem;
            border-radius: 999px;
            border: none;
            cursor: pointer;
            transition: all 0.25s ease;
            box-shadow: 0 10px 30px rgba(46, 125, 50, 0.12);
            font-family: inherit;
            width: 100%;
        }
        .btn-primary:hover {
            background: var(--primary-hover);
            transform: translateY(-1px);
            box-shadow: 0 14px 35px rgba(46, 125, 50, 0.2);
        }
        .alert {
            padding: 14px 16px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-weight: 500;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .alert-danger {
            background: #fef2f2;
            color: #b91c1c;
            border: 1px solid #fecaca;
        }
        .alert svg {
            width: 18px;
            height: 18px;
            flex-shrink: 0;
        }
        .auth-footer {
            margin-top: 24px;
            text-align: center;
            font-size: 0.85rem;
            color: var(--text-muted);
        }
        .auth-footer a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
        }
        .auth-footer a:hover {
            text-decoration: underline;
        }
        @media (max-width: 900px) {
            .auth-layout {
                grid-template-columns: 1fr;
            }
            .auth-visual {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="auth-layout">
        <div class="auth-visual">
            <div class="auth-visual-content">
                <div class="auth-visual-logo">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/></svg>
                    <?php echo htmlspecialchars(APP_NAME); ?>
                </div>
                <p class="auth-visual-text">Paneli i administrimit për menaxhimin e rezervimeve, galerisë dhe përmbajtjes së faqes.</p>
            </div>
        </div>
        <div class="auth-form-side">
            <div class="auth-card">
                <div class="auth-header">
                    <h1>Mirësevini</h1>
                    <p>Hyni në panelin e administratorit</p>
                </div>
                <?php if ($errorMessage): ?>
                    <div class="alert alert-danger">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        <?php echo $errorMessage; ?>
                    </div>
                <?php endif; ?>
                <form method="post" action="authenticate.php" class="auth-form">
                    <?php echo csrf_input_field(); ?>
                    <div class="form-group">
                        <label for="username">Emri i përdoruesit</label>
                        <input type="text" id="username" name="username" required maxlength="100" autocomplete="username" autofocus>
                    </div>
                    <div class="form-group">
                        <label for="password">Fjalëkalimi</label>
                        <input type="password" id="password" name="password" required autocomplete="current-password">
                    </div>
                    <button type="submit">Hyr</button>
                </form>
                <div class="auth-footer">
                    <a href="<?php echo htmlspecialchars(rtrim(BASE_URL, '/')); ?>">← Kthehu në faqen kryesore</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
